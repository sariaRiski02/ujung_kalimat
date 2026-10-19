<?php

namespace Tests\Feature;

use App\Models\MembershipPlans;
use App\Models\Payments;
use App\Models\Subscriptions;
use App\Models\User;
use App\Services\PaymentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentTest extends TestCase
{
    use RefreshDatabase;

    private function makePlan(string $name = 'monthly', int $price = 5000, int $months = 1): MembershipPlans
    {
        return MembershipPlans::create([
            'name' => $name,
            'price' => $price,
            'duration_months' => $months,
            'is_active' => true,
        ]);
    }

    private function makePendingPayment(User $user, MembershipPlans $plan): Payments
    {
        $subscription = Subscriptions::create([
            'user_id' => $user->id,
            'membership_plan_id' => $plan->id,
        ]);

        return Payments::create([
            'subscription_id' => $subscription->id,
            'order_id' => 'ORDER-TEST-'.$subscription->id,
            'status' => Payments::STATUS_PENDING,
            'payment_method' => 'qris',
            'amount' => $plan->price,
        ]);
    }

    /**
     * @return array<string, string>
     */
    private function notificationPayload(Payments $payment, string $status = 'settlement', ?string $serverKey = null): array
    {
        $statusCode = '200';
        $grossAmount = number_format((float) $payment->amount, 2, '.', '');
        $serverKey ??= config('services.midtrans.server_key');

        return [
            'order_id' => $payment->order_id,
            'status_code' => $statusCode,
            'gross_amount' => $grossAmount,
            'signature_key' => hash('sha512', $payment->order_id.$statusCode.$grossAmount.$serverKey),
            'transaction_status' => $status,
            'transaction_id' => 'trx-'.$payment->order_id,
            'payment_type' => 'qris',
        ];
    }

    public function test_guest_cannot_create_payment(): void
    {
        $this->post(route('pay'), ['plan' => 'monthly'])->assertRedirect(route('signin'));
        $this->assertDatabaseCount('payments', 0);
    }

    public function test_user_can_create_pending_payment_and_receive_snap_token(): void
    {
        $user = User::factory()->create();
        $this->makePlan();
        $this->partialMock(PaymentService::class, function ($mock) {
            $mock->shouldAllowMockingProtectedMethods()
                ->shouldReceive('requestSnapToken')
                ->once()
                ->andReturn('snap-token-123');
        });

        $response = $this->actingAs($user)->postJson(route('pay'), ['plan' => 'monthly']);

        $response->assertOk()->assertJsonPath('snap_token', 'snap-token-123');
        $payment = Payments::where('order_id', $response->json('order_id'))->firstOrFail();
        $this->assertSame(Payments::STATUS_PENDING, $payment->status);
        $this->assertSame('qris', $payment->payment_method);
        $this->assertNull($payment->subscription->started_at);
        $this->assertFalse($user->isSubscriber());
    }

    public function test_cannot_pay_for_unknown_or_inactive_plan(): void
    {
        $user = User::factory()->create();
        $this->makePlan()->update(['is_active' => false]);

        $this->actingAs($user)->postJson(route('pay'), ['plan' => 'monthly'])->assertUnprocessable();
        $this->actingAs($user)->postJson(route('pay'), ['plan' => 'nope'])->assertUnprocessable();
        $this->actingAs($user)->postJson(route('pay'), [])->assertUnprocessable();
        $this->assertDatabaseCount('payments', 0);
    }

    public function test_midtrans_failure_returns_error_and_stores_nothing(): void
    {
        $user = User::factory()->create();
        $this->makePlan();
        $this->partialMock(PaymentService::class, function ($mock) {
            $mock->shouldAllowMockingProtectedMethods()
                ->shouldReceive('requestSnapToken')
                ->andThrow(new \Exception('midtrans down'));
        });

        $this->actingAs($user)->postJson(route('pay'), ['plan' => 'monthly'])->assertStatus(500);

        $this->assertDatabaseCount('payments', 0);
        $this->assertDatabaseCount('subscriptions', 0);
    }

    public function test_settlement_notification_activates_subscription(): void
    {
        $user = User::factory()->create();
        $payment = $this->makePendingPayment($user, $this->makePlan('semiannual', 20000, 6));

        $this->postJson(route('midtrans.notification'), $this->notificationPayload($payment))->assertOk();

        $payment->refresh();
        $this->assertSame(Payments::STATUS_PAID, $payment->status);
        $this->assertNotNull($payment->paid_at);
        $this->assertSame('trx-'.$payment->order_id, $payment->transaction_id);
        $subscription = $payment->subscription;
        $this->assertTrue($subscription->isActive());
        $this->assertTrue($subscription->ended_at->between(now()->addMonths(6)->subMinute(), now()->addMonths(6)->addMinute()));
        $this->assertTrue($user->isSubscriber());
    }

    public function test_notification_is_idempotent(): void
    {
        $user = User::factory()->create();
        $payment = $this->makePendingPayment($user, $this->makePlan());
        $payload = $this->notificationPayload($payment);

        $this->postJson(route('midtrans.notification'), $payload)->assertOk();
        $endedAt = $payment->subscription->fresh()->ended_at;
        $this->postJson(route('midtrans.notification'), $payload)->assertOk();

        $this->assertTrue($payment->subscription->fresh()->ended_at->equalTo($endedAt));
    }

    public function test_notification_with_invalid_signature_is_rejected(): void
    {
        $user = User::factory()->create();
        $payment = $this->makePendingPayment($user, $this->makePlan());

        $this->postJson(route('midtrans.notification'), $this->notificationPayload($payment, 'settlement', 'wrong-key'))
            ->assertForbidden();

        $this->assertSame(Payments::STATUS_PENDING, $payment->fresh()->status);
        $this->assertFalse($user->isSubscriber());
    }

    public function test_notification_with_mismatched_amount_is_ignored(): void
    {
        $user = User::factory()->create();
        $payment = $this->makePendingPayment($user, $this->makePlan());
        $payload = $this->notificationPayload($payment);
        $payload['gross_amount'] = '1.00';
        $payload['signature_key'] = hash('sha512', $payment->order_id.'200'.'1.00'.config('services.midtrans.server_key'));

        $this->postJson(route('midtrans.notification'), $payload)->assertOk();

        $this->assertSame(Payments::STATUS_PENDING, $payment->fresh()->status);
    }

    public function test_expired_and_failed_notifications_do_not_activate_subscription(): void
    {
        $user = User::factory()->create();
        $plan = $this->makePlan();

        foreach (['expire' => Payments::STATUS_EXPIRED, 'cancel' => Payments::STATUS_FAILED] as $midtransStatus => $expected) {
            $payment = $this->makePendingPayment($user, $plan);
            $this->postJson(route('midtrans.notification'), $this->notificationPayload($payment, $midtransStatus))->assertOk();

            $this->assertSame($expected, $payment->fresh()->status);
        }

        $this->assertFalse($user->isSubscriber());
    }

    public function test_paid_payment_is_not_downgraded_by_late_notification(): void
    {
        $user = User::factory()->create();
        $payment = $this->makePendingPayment($user, $this->makePlan());

        $this->postJson(route('midtrans.notification'), $this->notificationPayload($payment))->assertOk();
        $this->postJson(route('midtrans.notification'), $this->notificationPayload($payment, 'expire'))->assertOk();

        $this->assertSame(Payments::STATUS_PAID, $payment->fresh()->status);
    }

    public function test_new_subscription_extends_from_current_active_end_date(): void
    {
        $user = User::factory()->create();
        $current = Subscriptions::factory()->active()->create(['user_id' => $user->id]);
        $payment = $this->makePendingPayment($user, $this->makePlan());

        $this->postJson(route('midtrans.notification'), $this->notificationPayload($payment))->assertOk();

        $this->assertTrue($payment->subscription->fresh()->ended_at->equalTo($current->ended_at->copy()->addMonth()));
    }

    public function test_status_endpoint_syncs_pending_payment_with_midtrans(): void
    {
        $user = User::factory()->create();
        $payment = $this->makePendingPayment($user, $this->makePlan());
        $status = $this->notificationPayload($payment);
        $status['gross_amount'] = '5000.00';
        $this->partialMock(PaymentService::class, function ($mock) use ($status) {
            $mock->shouldAllowMockingProtectedMethods()
                ->shouldReceive('fetchTransactionStatus')
                ->with($status['order_id'])
                ->andReturn($status);
        });

        $this->actingAs($user)
            ->getJson(route('pay.status', $payment))
            ->assertOk()
            ->assertJson(['status' => Payments::STATUS_PAID]);

        $this->assertTrue($user->isSubscriber());
    }

    public function test_status_endpoint_stays_pending_when_midtrans_has_no_transaction_yet(): void
    {
        $user = User::factory()->create();
        $payment = $this->makePendingPayment($user, $this->makePlan());
        $this->partialMock(PaymentService::class, function ($mock) {
            $mock->shouldAllowMockingProtectedMethods()
                ->shouldReceive('fetchTransactionStatus')
                ->andThrow(new \Exception('404'));
        });

        $this->actingAs($user)
            ->getJson(route('pay.status', $payment))
            ->assertOk()
            ->assertJson(['status' => Payments::STATUS_PENDING]);
    }

    public function test_user_cannot_view_status_of_another_users_payment(): void
    {
        $payment = $this->makePendingPayment(User::factory()->create(), $this->makePlan());

        $this->actingAs(User::factory()->create())
            ->getJson(route('pay.status', $payment))
            ->assertForbidden();
    }

    public function test_expired_subscription_is_not_subscriber(): void
    {
        $user = User::factory()->create();
        Subscriptions::factory()->expired()->create(['user_id' => $user->id]);

        $this->assertFalse($user->isSubscriber());
    }
}
