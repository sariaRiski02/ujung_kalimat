<?php

namespace App\Http\Controllers;

use App\Http\Requests\PayRequest;
use App\Models\MembershipPlans;
use App\Models\Payments;
use App\Services\PaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct(private PaymentService $payments) {}

    public function pay(PayRequest $request): JsonResponse
    {
        $plan = MembershipPlans::where('name', $request->validated('plan'))->where('is_active', true)->firstOrFail();

        try {
            $checkout = $this->payments->createCheckout($request->user(), $plan);
        } catch (\Throwable $e) {
            report($e);

            return response()->json(['message' => 'Gagal membuat pembayaran'], 500);
        }

        return response()->json([
            'snap_token' => $checkout['snap_token'],
            'order_id' => $checkout['payment']->order_id,
            'message' => 'Berhasil membuat pembayaran',
        ]);
    }

    public function status(Request $request, Payments $payment): JsonResponse
    {
        abort_unless($payment->subscription->user_id === $request->user()->id, 403);

        $payment = $this->payments->syncWithMidtrans($payment);

        return response()->json(['status' => $payment->status]);
    }

    public function notification(Request $request): JsonResponse
    {
        $payload = $request->all();

        if (! $this->payments->hasValidSignature($payload)) {
            return response()->json(['message' => 'Signature tidak valid'], 403);
        }

        $this->payments->applyTransactionStatus($payload);

        return response()->json(['message' => 'OK']);
    }
}
