@extends('layouts.workspace')

@php
  $rupiah = fn (int $value): string => 'Rp '.number_format($value, 0, ',', '.');
  $canWithdraw = $availableBalance >= $minWithdrawal;
  $statusStyles = [
    'pending' => ['text-amber-600', 'ti-clock', 'Pending'],
    'paid' => ['text-green-600', 'ti-check', 'Paid'],
    'rejected' => ['text-red-500', 'ti-x', 'Rejected'],
  ];
@endphp

@section('content')
<main class="min-h-screen transition-all duration-300 p-8 bg-gray-50" :class="open ? 'ml-56' : 'ml-14'">

  {{-- Top Bar --}}
  <div class="flex items-start justify-between mb-6">
    <div>
      <h1 class="text-2xl font-medium text-gray-900">Monetization</h1>
      <p class="text-sm text-gray-400 mt-0.5">You earn {{ $rupiah($ratePerView) }} for every view on your premium articles.</p>
    </div>
    <a href="{{ route('workspace.write') }}" class="flex items-center gap-1.5 text-sm px-4 py-2 rounded-lg border border-gray-200 bg-white text-gray-700 hover:border-gray-400 transition">
      <i class="ti ti-plus text-base"></i> New article
    </a>
  </div>

  @if(session('success'))
    <div class="flex items-center gap-2 text-sm text-green-700 bg-green-50 border border-green-100 rounded-xl px-4 py-3 mb-4">
      <i class="ti ti-circle-check text-base"></i>
      {{ session('success') }}
    </div>
  @endif

  {{-- Stat Cards --}}
  <div class="grid grid-cols-3 gap-3 mb-4">
    <div class="bg-gray-100 rounded-xl p-4">
      <p class="text-[11px] font-medium tracking-widest text-gray-400 uppercase mb-2">Total Earnings</p>
      <p class="text-3xl font-medium text-gray-900 leading-none mb-1">{{ $rupiah($totalEarnings) }}</p>
      <p class="text-xs text-gray-400">{{ number_format($premiumViews, 0, ',', '.') }} premium views × {{ $rupiah($ratePerView) }}</p>
    </div>
    <div class="bg-gray-100 rounded-xl p-4">
      <p class="text-[11px] font-medium tracking-widest text-gray-400 uppercase mb-2">Withdrawn</p>
      <p class="text-3xl font-medium text-gray-900 leading-none mb-1">{{ $rupiah($totalWithdrawn) }}</p>
      <p class="text-xs text-gray-400">Including pending requests</p>
    </div>
    <div class="bg-gray-100 rounded-xl p-4">
      <p class="text-[11px] font-medium tracking-widest text-gray-400 uppercase mb-2">Available Balance</p>
      <p class="text-3xl font-medium text-green-600 leading-none mb-1">{{ $rupiah($availableBalance) }}</p>
      <p class="text-xs text-gray-400">Earnings minus withdrawn</p>
    </div>
  </div>

  {{-- Withdraw --}}
  <div class="bg-white border border-gray-200 rounded-2xl p-5 mb-4">
    <h2 class="text-sm font-medium text-gray-900 mb-1">Withdraw</h2>
    <p class="text-xs text-gray-400 mb-4">Minimum withdrawal is {{ $rupiah($minWithdrawal) }}. The money is transferred to the phone number below.</p>

    <form method="POST" action="{{ route('workspace.monetization.withdraw') }}" class="flex items-start gap-3">
      @csrf
      <div class="flex-1">
        <label for="amount" class="block text-xs text-gray-500 mb-1">Amount (Rp)</label>
        <input type="number" name="amount" id="amount" min="{{ $minWithdrawal }}" max="{{ $availableBalance }}" step="1"
               value="{{ old('amount', $canWithdraw ? $availableBalance : '') }}" @disabled(! $canWithdraw)
               class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:border-gray-400 disabled:bg-gray-50 disabled:text-gray-400 @error('amount') border-red-300 @enderror">
        @error('amount')
          <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
        @enderror
      </div>
      <div class="flex-1">
        <label for="phone_number" class="block text-xs text-gray-500 mb-1">Phone number</label>
        <input type="tel" name="phone_number" id="phone_number" inputmode="numeric" placeholder="081234567890"
               value="{{ old('phone_number') }}" @disabled(! $canWithdraw)
               class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:border-gray-400 disabled:bg-gray-50 disabled:text-gray-400 @error('phone_number') border-red-300 @enderror">
        @error('phone_number')
          <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
        @enderror
      </div>
      <div class="pt-5">
        <button type="submit" @disabled(! $canWithdraw)
                class="flex items-center gap-1.5 text-sm px-4 py-2 rounded-lg bg-gray-900 text-white hover:bg-gray-700 transition disabled:bg-gray-200 disabled:text-gray-400 disabled:cursor-not-allowed">
          <i class="ti ti-arrow-up text-base"></i> Withdraw
        </button>
      </div>
    </form>
  </div>

  {{-- Article Performance --}}
  <div class="bg-white border border-gray-200 rounded-2xl p-5 mb-4">
    <h2 class="text-sm font-medium text-gray-900 mb-4">Article earnings</h2>
    <table class="w-full text-sm">
      <thead>
        <tr class="border-b border-gray-100">
          <th class="text-left text-[11px] font-medium text-gray-400 uppercase tracking-wider pb-2 w-1/2">Title</th>
          <th class="text-right text-[11px] font-medium text-gray-400 uppercase tracking-wider pb-2">Type</th>
          <th class="text-right text-[11px] font-medium text-gray-400 uppercase tracking-wider pb-2">Views</th>
          <th class="text-right text-[11px] font-medium text-gray-400 uppercase tracking-wider pb-2">Earnings</th>
        </tr>
      </thead>
      <tbody>
        @forelse($articles as $article)
          <tr class="border-b border-gray-100 last:border-0">
            <td class="py-3 font-medium text-gray-900">
              <a href="{{ route('article.show', $article) }}" class="hover:underline">{{ $article->clean_title }}</a>
            </td>
            <td class="py-3 text-right">
              @if($article->is_premium)
                <span class="text-[11px] font-medium bg-amber-50 text-amber-700 rounded-md px-2 py-0.5">Premium</span>
              @else
                <span class="text-[11px] font-medium bg-gray-100 text-gray-500 rounded-md px-2 py-0.5">Free</span>
              @endif
            </td>
            <td class="py-3 text-right text-gray-600">{{ number_format($article->views, 0, ',', '.') }}</td>
            @if($article->is_premium)
              <td class="py-3 text-right font-medium text-green-600">{{ $rupiah($article->views * $ratePerView) }}</td>
            @else
              <td class="py-3 text-right font-medium text-gray-300">—</td>
            @endif
          </tr>
        @empty
          <tr>
            <td colspan="4" class="py-6 text-center text-gray-400">You have no articles yet.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  {{-- Withdrawal History --}}
  <div class="bg-white border border-gray-200 rounded-2xl p-5">
    <h2 class="text-sm font-medium text-gray-900 mb-3">Withdrawal history</h2>
    <table class="w-full text-sm">
      <thead>
        <tr class="border-b border-gray-100">
          <th class="text-left text-[11px] font-medium text-gray-400 uppercase tracking-wider pb-2">Date</th>
          <th class="text-right text-[11px] font-medium text-gray-400 uppercase tracking-wider pb-2">Amount</th>
          <th class="text-right text-[11px] font-medium text-gray-400 uppercase tracking-wider pb-2">Phone number</th>
          <th class="text-right text-[11px] font-medium text-gray-400 uppercase tracking-wider pb-2">Status</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-100">
        @forelse($withdrawals as $withdrawal)
          @php [$color, $icon, $label] = $statusStyles[$withdrawal->status] ?? $statusStyles['pending']; @endphp
          <tr>
            <td class="py-2.5 text-gray-500">{{ $withdrawal->created_at->format('M j, Y') }}</td>
            <td class="py-2.5 text-right font-medium text-gray-900">{{ $rupiah($withdrawal->amount) }}</td>
            <td class="py-2.5 text-right text-gray-500">{{ $withdrawal->phone_number }}</td>
            <td class="py-2.5 text-right">
              <span class="text-xs {{ $color }} flex items-center justify-end gap-1"><i class="ti {{ $icon }} text-xs"></i>{{ $label }}</span>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="4" class="py-6 text-center text-gray-400">No withdrawals yet.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

</main>
@endsection
