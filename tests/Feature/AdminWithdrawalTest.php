<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Withdrawal;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminWithdrawalTest extends TestCase
{
    use RefreshDatabase;

    private function makeAdmin(): User
    {
        $admin = User::factory()->create();
        $admin->forceFill(['role' => 'admin'])->save();

        return $admin;
    }

    public function test_guest_is_redirected_to_signin(): void
    {
        $this->get(route('admin.withdrawals'))->assertRedirect(route('signin'));
    }

    public function test_non_admin_cannot_view_or_pay_withdrawals(): void
    {
        $withdrawal = Withdrawal::factory()->create();
        $this->actingAs(User::factory()->create());

        $this->get(route('admin.withdrawals'))->assertForbidden();
        $this->patch(route('admin.withdrawals.paid', $withdrawal))->assertForbidden();

        $this->assertSame(Withdrawal::STATUS_PENDING, $withdrawal->fresh()->status);
    }

    public function test_admin_sees_pending_withdrawals(): void
    {
        $withdrawal = Withdrawal::factory()->create(['phone_number' => '081299998888']);
        $this->actingAs($this->makeAdmin());

        $this->get(route('admin.withdrawals'))
            ->assertOk()
            ->assertSee($withdrawal->user->name)
            ->assertSee('081299998888')
            ->assertSee('Mark as paid');
    }

    public function test_admin_can_mark_pending_withdrawal_as_paid(): void
    {
        $withdrawal = Withdrawal::factory()->create();
        $this->actingAs($this->makeAdmin());

        $this->patch(route('admin.withdrawals.paid', $withdrawal))
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertSame(Withdrawal::STATUS_PAID, $withdrawal->fresh()->status);
    }

    public function test_non_pending_withdrawal_cannot_be_marked_as_paid(): void
    {
        $rejected = Withdrawal::factory()->create(['status' => Withdrawal::STATUS_REJECTED]);
        $this->actingAs($this->makeAdmin());

        $this->patch(route('admin.withdrawals.paid', $rejected))
            ->assertSessionHasErrors('withdrawal');

        $this->assertSame(Withdrawal::STATUS_REJECTED, $rejected->fresh()->status);
    }

    public function test_sidebar_link_is_only_visible_to_admin(): void
    {
        $this->actingAs(User::factory()->create());
        $this->get(route('workspace.monetization'))->assertOk()->assertDontSee('Withdrawals');

        $this->actingAs($this->makeAdmin());
        $this->get(route('workspace.monetization'))->assertOk()->assertSee('Withdrawals');
    }

    public function test_paid_withdrawal_still_counts_as_withdrawn(): void
    {
        $withdrawal = Withdrawal::factory()->create(['amount' => 15000]);
        $this->actingAs($this->makeAdmin());

        $this->patch(route('admin.withdrawals.paid', $withdrawal));

        $this->assertSame(15000, $withdrawal->user->fresh()->totalWithdrawn());
    }

    public function test_admin_can_reject_pending_withdrawal_and_balance_is_restored(): void
    {
        $withdrawal = Withdrawal::factory()->create(['amount' => 15000]);
        $this->actingAs($this->makeAdmin());

        $this->patch(route('admin.withdrawals.reject', $withdrawal))
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertSame(Withdrawal::STATUS_REJECTED, $withdrawal->fresh()->status);
        $this->assertSame(0, $withdrawal->user->fresh()->totalWithdrawn());
    }

    public function test_paid_withdrawal_cannot_be_rejected(): void
    {
        $paid = Withdrawal::factory()->create(['status' => Withdrawal::STATUS_PAID]);
        $this->actingAs($this->makeAdmin());

        $this->patch(route('admin.withdrawals.reject', $paid))
            ->assertSessionHasErrors('withdrawal');

        $this->assertSame(Withdrawal::STATUS_PAID, $paid->fresh()->status);
    }

    public function test_non_admin_cannot_reject_withdrawal(): void
    {
        $withdrawal = Withdrawal::factory()->create();
        $this->actingAs(User::factory()->create());

        $this->patch(route('admin.withdrawals.reject', $withdrawal))->assertForbidden();

        $this->assertSame(Withdrawal::STATUS_PENDING, $withdrawal->fresh()->status);
    }
}
