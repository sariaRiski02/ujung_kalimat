<?php

namespace App\Services;

use App\Models\MembershipPlans;
use App\Models\Payments;
use App\Models\Subscriptions;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Midtrans\Snap;
use Midtrans\Transaction;

class PaymentService
{
    /**
     * Minta Snap token QRIS, lalu simpan subscription (belum aktif) dan payment pending.
     *
     * @return array{payment: Payments, snap_token: string}
     */
    public function createCheckout(User $user, MembershipPlans $plan): array
    {
        $orderId = 'ORDER-'.Str::upper(Str::ulid());

        $snapToken = $this->requestSnapToken([
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int) $plan->price,
            ],
            'customer_details' => [
                'first_name' => $user->name,
                'email' => $user->email,
            ],
            'item_details' => [[
                'id' => (string) $plan->id,
                'price' => (int) $plan->price,
                'quantity' => 1,
                'name' => 'Langganan '.$plan->name,
            ]],
            'enabled_payments' => ['other_qris'],
        ]);

        $payment = DB::transaction(function () use ($user, $plan, $orderId) {
            $subscription = Subscriptions::create([
                'user_id' => $user->id,
                'membership_plan_id' => $plan->id,
            ]);

            return Payments::create([
                'subscription_id' => $subscription->id,
                'order_id' => $orderId,
                'status' => Payments::STATUS_PENDING,
                'payment_method' => 'qris',
                'amount' => $plan->price,
            ]);
        });

        return ['payment' => $payment, 'snap_token' => $snapToken];
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public function hasValidSignature(array $payload): bool
    {
        $signature = $payload['signature_key'] ?? null;

        if (! is_string($signature) || ! isset($payload['order_id'], $payload['status_code'], $payload['gross_amount'])) {
            return false;
        }

        $expected = hash('sha512', $payload['order_id'].$payload['status_code'].$payload['gross_amount'].config('services.midtrans.server_key'));

        return hash_equals($expected, $signature);
    }

    /**
     * Terapkan status transaksi dari Midtrans (webhook atau pengecekan status) ke payment.
     *
     * @param  array<string, mixed>  $data
     */
    public function applyTransactionStatus(array $data): ?Payments
    {
        $payment = Payments::where('order_id', $data['order_id'] ?? null)->first();

        if (! $payment) {
            return null;
        }

        if ((int) ($data['gross_amount'] ?? 0) !== (int) $payment->amount) {
            Log::warning('Midtrans gross_amount tidak cocok dengan payment', ['order_id' => $payment->order_id]);

            return $payment;
        }

        $newStatus = $this->mapStatus($data['transaction_status'] ?? '', $data['fraud_status'] ?? null);

        if ($newStatus === null) {
            return $payment;
        }

        return DB::transaction(function () use ($payment, $newStatus, $data) {
            $payment = Payments::whereKey($payment->id)->lockForUpdate()->first();

            // Payment yang sudah lunas tidak boleh diturunkan atau diproses ulang.
            if ($payment->isPaid()) {
                return $payment;
            }

            $payment->fill([
                'status' => $newStatus,
                'transaction_id' => $data['transaction_id'] ?? $payment->transaction_id,
                'payment_method' => $data['payment_type'] ?? $payment->payment_method,
            ]);

            if ($newStatus === Payments::STATUS_PAID) {
                $payment->paid_at = now();
                $this->activateSubscription($payment->subscription);
            }

            $payment->save();

            return $payment;
        });
    }

    /**
     * Tanya status ke Midtrans (berguna saat webhook tidak bisa sampai, misal di localhost).
     */
    public function syncWithMidtrans(Payments $payment): Payments
    {
        if (! $payment->isPending()) {
            return $payment;
        }

        try {
            $status = $this->fetchTransactionStatus($payment->order_id);
        } catch (\Throwable $e) {
            // Midtrans membalas 404 selama pengguna belum memilih metode bayar.
            return $payment;
        }

        return $this->applyTransactionStatus($status) ?? $payment;
    }

    /**
     * @param  array<string, mixed>  $params
     */
    protected function requestSnapToken(array $params): string
    {
        return Snap::getSnapToken($params);
    }

    /**
     * @return array<string, mixed>
     */
    protected function fetchTransactionStatus(string $orderId): array
    {
        return json_decode(json_encode(Transaction::status($orderId)), true);
    }

    private function mapStatus(string $transactionStatus, ?string $fraudStatus): ?string
    {
        return match ($transactionStatus) {
            'settlement' => Payments::STATUS_PAID,
            'capture' => $fraudStatus === 'accept' ? Payments::STATUS_PAID : Payments::STATUS_PENDING,
            'pending' => Payments::STATUS_PENDING,
            'expire' => Payments::STATUS_EXPIRED,
            'deny', 'cancel', 'failure' => Payments::STATUS_FAILED,
            default => null,
        };
    }

    private function activateSubscription(Subscriptions $subscription): void
    {
        $months = (int) $subscription->plan?->duration_months;

        if ($months < 1) {
            Log::error('Subscription tanpa plan valid saat aktivasi', ['subscription_id' => $subscription->id]);

            return;
        }

        // Perpanjang dari akhir langganan aktif saat ini agar sisa masa aktif tidak hilang.
        $latestEnd = Subscriptions::where('user_id', $subscription->user_id)
            ->whereKeyNot($subscription->id)
            ->active()
            ->max('ended_at');

        $base = $latestEnd ? Carbon::parse($latestEnd) : now();

        $subscription->update([
            'started_at' => now(),
            'ended_at' => $base->copy()->addMonths($months),
        ]);
    }
}
