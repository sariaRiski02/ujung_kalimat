@extends('layouts.workspace')

@php
  $rupiah = fn (int $value): string => 'Rp '.number_format($value, 0, ',', '.');
@endphp

@section('content')
<main class="min-h-screen transition-all duration-300 p-8 bg-gray-50" :class="open ? 'ml-56' : 'ml-14'"
      x-data="{ confirming: null, toast: @js(session('success')), toastError: @js($errors->first('withdrawal') ?: null) }"
      x-init="setTimeout(() => { toast = null; toastError = null }, 5000)"
      @keydown.escape.window="confirming = null">

  <div class="mb-6">
    <h1 class="text-2xl font-medium text-gray-900">Withdrawal requests</h1>
    <p class="text-sm text-gray-400 mt-0.5">Transfer the money to the phone number first, then mark the request as paid.</p>
  </div>

  {{-- Toast --}}
  <div class="fixed top-5 right-5 z-50 space-y-2">
    <div x-show="toast" x-cloak x-transition.duration.200ms
         class="flex items-start gap-3 w-80 bg-white border border-green-100 rounded-xl shadow-lg px-4 py-3" role="status">
      <span class="flex items-center justify-center w-7 h-7 rounded-full bg-green-50 text-green-600 shrink-0">
        <i class="ti ti-check text-base"></i>
      </span>
      <p class="text-sm text-gray-700 flex-1 pt-0.5" x-text="toast"></p>
      <button type="button" @click="toast = null" class="text-gray-300 hover:text-gray-500" aria-label="Dismiss"><i class="ti ti-x text-sm"></i></button>
    </div>
    <div x-show="toastError" x-cloak x-transition.duration.200ms
         class="flex items-start gap-3 w-80 bg-white border border-red-100 rounded-xl shadow-lg px-4 py-3" role="alert">
      <span class="flex items-center justify-center w-7 h-7 rounded-full bg-red-50 text-red-500 shrink-0">
        <i class="ti ti-alert-circle text-base"></i>
      </span>
      <p class="text-sm text-gray-700 flex-1 pt-0.5" x-text="toastError"></p>
      <button type="button" @click="toastError = null" class="text-gray-300 hover:text-gray-500" aria-label="Dismiss"><i class="ti ti-x text-sm"></i></button>
    </div>
  </div>

  {{-- Pending --}}
  <div class="bg-white border border-gray-200 rounded-2xl p-5 mb-4">
    <h2 class="text-sm font-medium text-gray-900 mb-3">Pending ({{ $pendingWithdrawals->count() }})</h2>
    <table class="w-full text-sm">
      <thead>
        <tr class="border-b border-gray-100">
          <th class="text-left text-[11px] font-medium text-gray-400 uppercase tracking-wider pb-2">Requested</th>
          <th class="text-left text-[11px] font-medium text-gray-400 uppercase tracking-wider pb-2">Writer</th>
          <th class="text-right text-[11px] font-medium text-gray-400 uppercase tracking-wider pb-2">Amount</th>
          <th class="text-right text-[11px] font-medium text-gray-400 uppercase tracking-wider pb-2">Phone number</th>
          <th class="pb-2"></th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-100">
        @forelse($pendingWithdrawals as $withdrawal)
          <tr>
            <td class="py-3 text-gray-600">{{ $withdrawal->created_at->format('d M Y, H:i') }}</td>
            <td class="py-3 font-medium text-gray-900">{{ $withdrawal->user->name }}</td>
            <td class="py-3 text-right font-medium text-gray-900">{{ $rupiah($withdrawal->amount) }}</td>
            <td class="py-3 text-right text-gray-600">{{ $withdrawal->phone_number }}</td>
            <td class="py-3 text-right">
              <div class="flex items-center justify-end gap-2">
                <button type="button"
                        @click="confirming = {{ \Illuminate\Support\Js::from(['type' => 'reject', 'url' => route('admin.withdrawals.reject', $withdrawal), 'writer' => $withdrawal->user->name, 'amount' => $rupiah($withdrawal->amount), 'phone' => $withdrawal->phone_number]) }}"
                        class="flex items-center gap-1.5 text-xs px-3 py-1.5 rounded-lg border border-red-200 text-red-600 hover:bg-red-50 transition">
                  <i class="ti ti-x text-sm"></i> Reject
                </button>
                <button type="button"
                        @click="confirming = {{ \Illuminate\Support\Js::from(['type' => 'paid', 'url' => route('admin.withdrawals.paid', $withdrawal), 'writer' => $withdrawal->user->name, 'amount' => $rupiah($withdrawal->amount), 'phone' => $withdrawal->phone_number]) }}"
                        class="flex items-center gap-1.5 text-xs px-3 py-1.5 rounded-lg bg-gray-900 text-white hover:bg-gray-700 transition">
                  <i class="ti ti-check text-sm"></i> Mark as paid
                </button>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="5" class="py-6 text-center text-gray-400">No pending withdrawals.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  {{-- Recently paid --}}
  <div class="bg-white border border-gray-200 rounded-2xl p-5">
    <h2 class="text-sm font-medium text-gray-900 mb-3">Recently paid</h2>
    <table class="w-full text-sm">
      <thead>
        <tr class="border-b border-gray-100">
          <th class="text-left text-[11px] font-medium text-gray-400 uppercase tracking-wider pb-2">Paid at</th>
          <th class="text-left text-[11px] font-medium text-gray-400 uppercase tracking-wider pb-2">Writer</th>
          <th class="text-right text-[11px] font-medium text-gray-400 uppercase tracking-wider pb-2">Amount</th>
          <th class="text-right text-[11px] font-medium text-gray-400 uppercase tracking-wider pb-2">Phone number</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-100">
        @forelse($paidWithdrawals as $withdrawal)
          <tr>
            <td class="py-3 text-gray-600">{{ $withdrawal->updated_at->format('d M Y, H:i') }}</td>
            <td class="py-3 font-medium text-gray-900">{{ $withdrawal->user->name }}</td>
            <td class="py-3 text-right font-medium text-green-600">{{ $rupiah($withdrawal->amount) }}</td>
            <td class="py-3 text-right text-gray-600">{{ $withdrawal->phone_number }}</td>
          </tr>
        @empty
          <tr>
            <td colspan="4" class="py-6 text-center text-gray-400">No paid withdrawals yet.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  {{-- Confirmation modal --}}
  <div x-show="confirming" x-cloak x-transition.opacity.duration.150ms
       class="fixed inset-0 z-40 flex items-center justify-center bg-gray-900/40 p-4"
       @click.self="confirming = null" role="dialog" aria-modal="true">
    <div x-show="confirming" x-transition.scale.95.duration.150ms class="w-full max-w-sm bg-white rounded-2xl shadow-xl p-6">
      <template x-if="confirming">
        <div>
          <div class="flex items-center justify-center w-11 h-11 rounded-full mb-4"
               :class="confirming.type === 'paid' ? 'bg-green-50 text-green-600' : 'bg-red-50 text-red-500'">
            <i class="ti text-xl" :class="confirming.type === 'paid' ? 'ti-cash-banknote' : 'ti-alert-triangle'"></i>
          </div>
          <h3 class="text-base font-medium text-gray-900"
              x-text="confirming.type === 'paid' ? 'Mark as paid?' : 'Reject withdrawal?'"></h3>
          <p class="text-sm text-gray-500 mt-1"
             x-text="confirming.type === 'paid'
               ? 'Make sure the money has already been transferred. This cannot be undone.'
               : 'The amount will return to the writer\'s balance. This cannot be undone.'"></p>

          <div class="bg-gray-50 rounded-xl px-4 py-3 mt-4 text-sm space-y-1">
            <div class="flex justify-between"><span class="text-gray-400">Writer</span><span class="font-medium text-gray-900" x-text="confirming.writer"></span></div>
            <div class="flex justify-between"><span class="text-gray-400">Amount</span><span class="font-medium text-gray-900" x-text="confirming.amount"></span></div>
            <div class="flex justify-between"><span class="text-gray-400">Phone</span><span class="font-medium text-gray-900" x-text="confirming.phone"></span></div>
          </div>

          <form method="POST" :action="confirming.url" class="flex gap-2 mt-5">
            @csrf
            @method('PATCH')
            <button type="button" @click="confirming = null"
                    class="flex-1 text-sm px-4 py-2 rounded-lg border border-gray-200 text-gray-700 hover:border-gray-400 transition">Cancel</button>
            <button type="submit"
                    class="flex-1 text-sm px-4 py-2 rounded-lg text-white transition"
                    :class="confirming.type === 'paid' ? 'bg-gray-900 hover:bg-gray-700' : 'bg-red-600 hover:bg-red-500'"
                    x-text="confirming.type === 'paid' ? 'Yes, mark as paid' : 'Yes, reject'"></button>
          </form>
        </div>
      </template>
    </div>
  </div>
</main>
@endsection
