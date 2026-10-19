<?php

namespace App\Http\Controllers;

use App\Models\MembershipPlans;
use App\Models\Payments;
use App\Models\Subscriptions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Midtrans\Snap;

class PaymentController extends Controller
{
    
    public function pay(Request $request){
        $plan = $request->input('plan');
        // ambil plan dari user
        // cocokan dengan yang ada di plan di database
        // lalu ambil plan yang cocok dan ambil harga dari plan tersebut
        // lalu masukan ke dalam table payments dengan status pending
        // masukan juga ke subscriptions untuk lama langganan sesuai plan 

        $plan = MembershipPlans::where('name', $plan)->first();
        if(!$plan || !$plan?->is_active) {
            return response()->json(['message' => 'Plan not found'], 404);
        }

        

        
        // isi data untuk subscription
        $subscription = Subscriptions::create([
            'user_id' => Auth::id(),
            'started_at' => now(),
            'ended_at' => now()->addMonths($plan->duration_months),
        ]);

        

        // isi data untuk payment
        $orderId = 'ORDER-' . time();
        Payments::create([
            'subscription_id' => $subscription->id,
            'order_id' => $orderId,
            'status' => 'pending',
            'paid_at' => null,
            'payment_method' => 'qris',
            'amount' => $plan->price,
        ]);

        // Seting snap token untuk midtrans
        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int) $plan->price,
            ],
            'customer_details' => [
                'first_name' => Auth::user()->name,
                'email' => Auth::user()->email,
            ],
            'enabled_payments' => ['other_qris'],
            
        ];
        $snapToken = null;
        try {
            $snapToken = Snap::getSnapToken($params);
        } catch (\Exception $e) {
            report($e);
            return response()->json(['message' => 'Gagal membuat pembayaran'], 500);
        }

        return response()->json(['snap_token' => $snapToken, 'message' => 'Berhasil membuat pembayaran']);

        
    }
}
