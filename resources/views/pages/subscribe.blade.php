@extends('layouts.app')

@section('title', 'Berlangganan')

@section('content')
<div
    x-data="{ selectedPlan: null, paymentNotice: false, paymentStatus: null, paymentMessage: '' }"
    @payment-status.window="paymentStatus = $event.detail.status; paymentMessage = $event.detail.message"
    class="min-h-screen bg-gradient-to-b from-indigo-50 via-white to-gray-50 py-16 px-4"
>
    <div class="max-w-4xl mx-auto">
        @if ($subscriptionEndsAt)
            <div class="mb-10 flex flex-col sm:flex-row sm:items-center gap-4 rounded-2xl border border-green-200 bg-green-50 p-6">
                <span class="flex items-center justify-center w-12 h-12 rounded-xl bg-green-100 text-green-700 shrink-0">
                    <i class="ti ti-circle-check text-2xl" aria-hidden="true"></i>
                </span>
                <div class="flex-1">
                    <h2 class="text-lg font-semibold text-green-900">Langganan premium Anda aktif</h2>
                    <p class="text-sm text-green-800 mt-1">
                        Berlaku sampai {{ $subscriptionEndsAt->locale('id')->translatedFormat('d F Y') }}
                        ({{ $subscriptionEndsAt->locale('id')->diffForHumans() }}). Perpanjang kapan saja, sisa masa aktif tidak hilang.
                    </p>
                </div>
                <a href="{{ route('welcome') }}" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-green-600 text-white text-sm font-semibold hover:bg-green-700 transition">
                    Mulai membaca
                </a>
            </div>
        @endif

        <div class="text-center mb-12">
            <span class="inline-block bg-indigo-100 text-indigo-700 text-xs font-semibold px-4 py-1.5 rounded-full mb-4">
                Premium
            </span>
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-3">{{ $subscriptionEndsAt ? 'Perpanjang Langganan' : 'Pilih Paket Berlangganan' }}</h1>
            <p class="text-gray-600 max-w-xl mx-auto">Nikmati akses penuh ke semua konten premium Ujung Kalimat tanpa batas.</p>
        </div>

        <div class="grid md:grid-cols-2 gap-8 items-stretch">
            {{-- 1 bulan --}}
            <article
                class="bg-white rounded-2xl shadow-md border-2 p-8 flex flex-col transition duration-300 hover:shadow-xl hover:-translate-y-1"
                :class="selectedPlan === 'monthly' ? 'border-indigo-600 ring-2 ring-indigo-100' : 'border-gray-200'"
            >
                <h2 class="text-xl font-semibold text-gray-900 mb-2">Bulanan</h2>
                <p class="text-gray-500 text-sm mb-6">Cocok untuk mencoba fitur premium.</p>
                <div class="mb-6">
                    <span class="text-4xl font-bold text-gray-900">Rp 5.000</span>
                    <span class="text-gray-500">/bulan</span>
                </div>
                <ul class="space-y-3 text-gray-600 text-sm mb-8 flex-1">
                    <li class="flex items-start gap-2">
                        <i class="ti ti-circle-check text-green-500 text-lg shrink-0" aria-hidden="true"></i>
                        Akses semua konten premium selama 1 bulan.
                    </li>
                </ul>
                <button
                    type="button"
                    
                    @click="selectedPlan = 'monthly'; paymentNotice = false"
                    :aria-pressed="selectedPlan === 'monthly'"
                    class="w-full text-center bg-gray-900 text-white font-semibold py-3 rounded-xl hover:bg-gray-800 transition focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                >
                    Pilih Bulanan
                </button>
            </article>

            {{-- 6 bulan --}}
            <article
                class="relative bg-white rounded-2xl shadow-xl border-2 p-8 flex flex-col transition duration-300 hover:shadow-2xl hover:-translate-y-1"
                :class="selectedPlan === 'semiannual' ? 'border-indigo-700 ring-2 ring-indigo-100' : 'border-indigo-600'"
            >
                <span class="absolute -top-3 left-1/2 -translate-x-1/2 bg-indigo-600 text-white text-xs font-semibold px-4 py-1 rounded-full shadow-md">
                    Paling Hemat
                </span>
                <h2 class="text-xl font-semibold text-gray-900 mb-2">6 Bulan</h2>
                <p class="text-gray-500 text-sm mb-6">Lebih hemat Rp 10.000 dibanding pembayaran bulanan selama 6 bulan.</p>
                <div class="mb-1">
                    <span class="text-sm text-gray-400 line-through">Rp 30.000</span>
                </div>
                <div class="mb-6">
                    <span class="text-4xl font-bold text-indigo-600">Rp 20.000</span>
                    <span class="text-gray-500">/6 bulan</span>
                </div>
                <ul class="space-y-3 text-gray-600 text-sm mb-8 flex-1">
                    <li class="flex items-start gap-2">
                        <i class="ti ti-circle-check text-green-500 text-lg shrink-0" aria-hidden="true"></i>
                        Akses semua konten premium selama 6 bulan.
                    </li>
                    <li class="flex items-start gap-2">
                        <i class="ti ti-circle-check text-green-500 text-lg shrink-0" aria-hidden="true"></i>
                        Hemat Rp 10.000 dibanding paket bulanan.
                    </li>
                </ul>
                <button
                    type="button"
                    @click="selectedPlan = 'semiannual'; paymentNotice = false"
                    :aria-pressed="selectedPlan === 'semiannual'"
                    class="w-full text-center bg-indigo-600 text-white font-semibold py-3 rounded-xl hover:bg-indigo-700 transition focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                >
                    Pilih 6 Bulan
                </button>
            </article>
        </div>

        <section
            id="payment-summary"
            x-show="selectedPlan"
            x-cloak 
            x-transition
            aria-live="polite"
            class="mt-10 bg-white rounded-2xl border border-gray-200 shadow-md p-6 md:p-8"
        >
            <div class="flex items-start gap-4 mb-6">
                <span class="flex items-center justify-center w-11 h-11 rounded-xl bg-indigo-100 text-indigo-700 shrink-0">
                    <i class="ti ti-receipt text-2xl" aria-hidden="true"></i>
                </span>
                <div>
                    <h2 class="text-xl font-semibold text-gray-900">Ringkasan langganan</h2>
                    <p class="text-sm text-gray-500 mt-1">Periksa paket dan harga sebelum melanjutkan.</p>
                </div>
            </div>

            <dl class="divide-y divide-gray-100 border-y border-gray-100">
                <div class="flex items-center justify-between gap-4 py-4">
                    <dt class="text-sm text-gray-500">Paket</dt>
                    <dd class="text-sm font-medium text-gray-900" x-text="selectedPlan === 'monthly' ? 'Bulanan' : '6 Bulan'"></dd>
                </div>
                <div class="flex items-center justify-between gap-4 py-4">
                    <dt class="text-sm text-gray-500">Masa aktif</dt>
                    <dd class="text-sm font-medium text-gray-900" x-text="selectedPlan === 'monthly' ? '1 bulan' : '6 bulan'"></dd>
                </div>
                <div class="flex items-center justify-between gap-4 py-4">
                    <dt class="text-sm font-medium text-gray-900">Total</dt>
                    <dd class="text-lg font-bold text-indigo-700" x-text="selectedPlan === 'monthly' ? 'Rp 5.000' : 'Rp 20.000'"></dd>
                </div>
            </dl>

            <div class="mt-6 flex items-start gap-3 rounded-xl bg-gray-50 p-4 text-sm text-gray-600">
                <i class="ti ti-qrcode text-xl text-indigo-600 shrink-0" aria-hidden="true"></i>
                <p>Pembayaran menggunakan QRIS. Kode QR akan ditampilkan pada langkah pembayaran.</p>
            </div>

            <div class="mt-6 flex flex-col-reverse sm:flex-row sm:justify-between gap-3">
                <button
                    type="button"
                    @click="selectedPlan = null; paymentNotice = false"
                    class="px-5 py-3 rounded-xl border border-gray-300 text-gray-700 font-medium hover:bg-gray-50 transition focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                >
                    Kembali pilih paket
                </button>

                @auth
                    <button
                        type="button"
                        @click="paymentStatus = null; openMidtransCheckout(selectedPlan)"
                        :disabled="paymentStatus === 'loading' || paymentStatus === 'paid'"
                        class="inline-flex disabled:opacity-60 disabled:cursor-not-allowed items-center justify-center gap-2 px-5 py-3 rounded-xl bg-indigo-600 text-white font-semibold hover:bg-indigo-700 transition focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                    >
                        <i class="ti ti-qrcode text-lg" aria-hidden="true"></i>
                        {{ $subscriptionEndsAt ? 'Perpanjang dengan QRIS' : 'Bayar dengan QRIS' }}
                    </button>
                @endauth

                @guest
                    <a
                        href="{{ route('signin') }}"
                        class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-indigo-600 text-white font-semibold hover:bg-indigo-700 transition focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                    >
                        <i class="ti ti-login text-lg" aria-hidden="true"></i>
                        Masuk untuk bayar
                    </a>
                @endguest
            </div>

            <p
                x-show="paymentMessage"
                x-cloak
                role="status"
                class="mt-6 rounded-xl p-4 text-sm"
                :class="{
                    'bg-green-50 text-green-700': paymentStatus === 'paid',
                    'bg-amber-50 text-amber-700': paymentStatus === 'pending' || paymentStatus === 'loading',
                    'bg-red-50 text-red-700': paymentStatus === 'failed' || paymentStatus === 'expired',
                }"
                x-text="paymentMessage"
            ></p>
        </section>
    </div>
</div>

<script
    src="https://app.sandbox.midtrans.com/snap/snap.js"
    data-client-key="{{ config('services.midtrans.client_key') }}">
</script>


<script>
    function announcePayment(status, message) {
        window.dispatchEvent(new CustomEvent('payment-status', { detail: { status, message } }));
    }

    let pollingOrderId = null;

    async function checkPaymentStatus(orderId, attempt = 0) {
        if (attempt === 0 && pollingOrderId === orderId) {
            return;
        }
        pollingOrderId = orderId;

        const messages = {
            paid: 'Pembayaran berhasil. Langganan premium Anda sudah aktif.',
            pending: 'Menunggu pembayaran. Selesaikan pembayaran QRIS Anda, status akan diperbarui otomatis.',
            failed: 'Pembayaran gagal atau dibatalkan. Silakan coba lagi.',
            expired: 'Pembayaran kedaluwarsa. Silakan buat pembayaran baru.',
        };

        if (attempt === 0) {
            announcePayment('loading', 'Memeriksa status pembayaran...');
        }

        try {
            const response = await fetch(@json(route('pay.status', ['payment' => 'ORDER_ID'])).replace('ORDER_ID', encodeURIComponent(orderId)), {
                headers: { 'Accept': 'application/json' },
            });
            if (!response.ok) {
                throw new Error('Status ' + response.status);
            }
            const { status } = await response.json();
            announcePayment(status, messages[status] ?? 'Status pembayaran: ' + status);

            if (status === 'paid') {
                setTimeout(() => { window.location.href = @json(route('subscribe')); }, 2000);
            } else if (status === 'pending' && attempt < 60) {
                setTimeout(() => checkPaymentStatus(orderId, attempt + 1), 5000);
            } else {
                pollingOrderId = null;
            }
        } catch (error) {
            console.error(error);
            pollingOrderId = null;
            announcePayment('pending', 'Pembayaran sedang diproses. Muat ulang halaman beberapa saat lagi untuk melihat statusnya.');
        }
    }

    async function openMidtransCheckout(plan) {
        announcePayment('loading', 'Menyiapkan pembayaran...');

        try {
            const response = await fetch(@json(route('pay')), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': @json(csrf_token()),
                },
                body: JSON.stringify({ plan }),
            });

            if (!response.ok) {
                throw new Error('Gagal membuat pembayaran, status ' + response.status);
            }

            const data = await response.json();
            announcePayment(null, '');

            window.snap.pay(data.snap_token, {
                onSuccess: () => checkPaymentStatus(data.order_id),
                onPending: () => checkPaymentStatus(data.order_id),
                onError: () => announcePayment('failed', 'Pembayaran gagal. Silakan coba lagi.'),
                onClose: () => checkPaymentStatus(data.order_id),
            });
        } catch (error) {
            console.error(error);
            announcePayment('failed', 'Tidak dapat membuat pembayaran. Silakan coba lagi.');
        }
    }
</script>



@endsection
