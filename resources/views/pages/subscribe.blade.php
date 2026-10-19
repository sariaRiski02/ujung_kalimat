@extends('layouts.app')

@section('title', 'Berlangganan')

@section('content')
<div
    x-data="{ selectedPlan: null, paymentNotice: false }"
    class="min-h-screen bg-gradient-to-b from-indigo-50 via-white to-gray-50 py-16 px-4"
>
    <div class="max-w-4xl mx-auto">
        <div class="text-center mb-12">
            <span class="inline-block bg-indigo-100 text-indigo-700 text-xs font-semibold px-4 py-1.5 rounded-full mb-4">
                Premium
            </span>
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-3">Pilih Paket Berlangganan</h1>
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
                        @click="openMidtransCheckout(selectedPlan)" 
                        class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-indigo-600 text-white font-semibold hover:bg-indigo-700 transition focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                    >
                        <i class="ti ti-qrcode text-lg" aria-hidden="true"></i>
                        Bayar dengan QRIS
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

            
        </section>
    </div>
</div>

<script
    src="https://app.sandbox.midtrans.com/snap/snap.js"
    data-client-key="{{ config('services.midtrans.client_key') }}">
</script>


<script>
    function openMidtransCheckout(plan) {
        // plan berisi 'monthly' atau 'semiannual' sesuai pilihan pengguna

        fetch(@json(route('pay')), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ plan: plan }),
            })
            .then(response => {
                if (response.redirected) {
                    throw new Error('Diarahkan ke ' + response.url + ' (kemungkinan belum login / sesi habis)');
                }
                if (!response.ok) {
                    throw new Error('Gagal mengambil Snap Token, status ' + response.status);
                }
                return response.json();
            })
            .then(data => {
                console.log('message', data.message);
                
                
                const snapToken = data.snap_token;
                window.snap.pay(snapToken, {
                    onSuccess: function (result) {
                        console.log('Pembayaran berhasil:', result);
                    },
                    onPending: function (result) {
                        console.log('Pembayaran pending:', result);
                    },
                    onError: function (result) {
                        console.error('Pembayaran gagal:', result);
                    },
                    onClose: function () {
                        console.log('Pengguna menutup checkout.');
                    }
                });
            })
            .catch(error => {
                console.error(error);
            });
    }
</script>



@endsection
