@extends('layouts.workspace')

@section('content')
<main class="min-h-screen transition-all duration-300 p-8 bg-gray-50" :class="open ? 'ml-56' : 'ml-14'">
<div class="max-w-5xl mx-auto">

    {{-- ===== HEADER ===== --}}
    <header class="mb-8 flex items-start justify-between gap-4 flex-wrap">
        <div>
            <h1 class="text-3xl font-serif font-bold text-gray-900 tracking-tight">Monetization</h1>
            <p class="text-gray-400 text-sm mt-1">Manage your earnings, payouts, and creator settings.</p>
        </div>
        <div class="flex gap-2">
            <a href="#"
               class="inline-flex items-center gap-1.5 px-4 py-2 text-sm border border-gray-200 rounded-full text-gray-700 hover:bg-gray-50 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                </svg>
                New article
            </a>
            <button class="inline-flex items-center gap-1.5 px-5 py-2 text-sm bg-gray-900 text-white rounded-full hover:bg-gray-700 transition-colors font-medium">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 10.5 12 3m0 0 7.5 7.5M12 3v18"/>
                </svg>
                Withdraw
            </button>
        </div>
    </header>

    {{-- ===== STAT CARDS ===== --}}
    <div class="grid grid-cols-3 gap-4 mb-6">
        <div class="bg-gray-100 rounded-2xl p-5">
            <p class="text-[10px] font-semibold uppercase tracking-widest text-gray-400 mb-1.5">Estimated earnings</p>
            <p class="text-2xl font-bold text-gray-900">$842.30</p>
            <p class="text-[11px] text-gray-400 mt-1">June 2026</p>
        </div>
        <div class="bg-gray-100 rounded-2xl p-5">
            <p class="text-[10px] font-semibold uppercase tracking-widest text-gray-400 mb-1.5">Total reading time</p>
            <p class="text-2xl font-bold text-gray-900">42.5 hrs</p>
            <p class="text-[11px] text-gray-400 mt-1">This month</p>
        </div>
        <div class="bg-gray-100 rounded-2xl p-5">
            <p class="text-[10px] font-semibold uppercase tracking-widest text-gray-400 mb-1.5">RPM</p>
            <p class="text-2xl font-bold text-gray-900">$2.40</p>
            <p class="text-[11px] text-gray-400 mt-1">Revenue per mille</p>
        </div>
    </div>

    {{-- ===== BAR CHART ===== --}}
    <div class="bg-white border border-gray-100 rounded-3xl p-6 shadow-sm mb-6">
        <div class="flex items-center justify-between mb-5">
            <p class="text-sm font-semibold text-gray-900">Weekly earnings</p>
            <span class="text-[10px] font-bold bg-emerald-50 text-emerald-700 px-2.5 py-1 rounded-full">June 2026</span>
        </div>
        <div class="flex items-end gap-2 h-40 px-2" id="bar-chart"
             data-weeks='[{"label":"W1","val":148},{"label":"W2","val":224},{"label":"W3","val":189},{"label":"W4","val":281}]'>
        </div>
    </div>

    {{-- ===== ARTICLE PERFORMANCE TABLE ===== --}}
    <div class="bg-white border border-gray-100 rounded-3xl shadow-sm mb-6 overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-50">
            <p class="text-sm font-semibold text-gray-900">Article performance</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-50">
                        <th class="text-left text-[10px] font-bold uppercase tracking-widest text-gray-400 px-6 py-3 w-[35%]">Title</th>
                        <th class="text-left text-[10px] font-bold uppercase tracking-widest text-gray-400 py-3 w-[13%]">Status</th>
                        <th class="text-left text-[10px] font-bold uppercase tracking-widest text-gray-400 py-3 w-[12%]">Views</th>
                        <th class="text-left text-[10px] font-bold uppercase tracking-widest text-gray-400 py-3 w-[13%]">Eng. time</th>
                        <th class="text-left text-[10px] font-bold uppercase tracking-widest text-gray-400 py-3 w-[12%]">Earnings</th>
                        <th class="text-left text-[10px] font-bold uppercase tracking-widest text-gray-400 py-3 pr-6 w-[15%]">Actions</th>
                    </tr>
                </thead>
                <tbody>

                    {{-- Row 1 --}}
                    <tr class="border-b border-gray-50 hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-3.5 font-medium text-gray-900">LamdaPHP: Building a Framework From Scratch</td>
                        <td class="py-3.5">
                            <span class="text-[10px] font-bold bg-amber-50 text-amber-700 px-2 py-0.5 rounded-full">Premium</span>
                        </td>
                        <td class="py-3.5 text-gray-600">2,841</td>
                        <td class="py-3.5 text-gray-600">7.2 min</td>
                        <td class="py-3.5 font-semibold text-emerald-600">$34.10</td>
                        <td class="py-3.5 pr-6">
                            <div class="flex items-center gap-1.5">
                                <button class="p-1.5 border border-gray-200 rounded-lg text-gray-500 hover:bg-gray-50 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125"/></svg>
                                </button>
                                <button class="p-1.5 border border-gray-200 rounded-lg text-gray-500 hover:bg-gray-50 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z"/></svg>
                                </button>
                                <button onclick="togglePaywall(this)" data-on="true"
                                        class="relative inline-flex h-5 w-8 cursor-pointer rounded-full border-2 border-transparent transition-colors bg-emerald-500">
                                    <span class="inline-block h-4 w-4 rounded-full bg-white shadow transform transition translate-x-3"></span>
                                </button>
                            </div>
                        </td>
                    </tr>

                    {{-- Row 2 --}}
                    <tr class="border-b border-gray-50 hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-3.5 font-medium text-gray-900">Real-time Rendering With SSE in PHP</td>
                        <td class="py-3.5">
                            <span class="text-[10px] font-bold bg-amber-50 text-amber-700 px-2 py-0.5 rounded-full">Premium</span>
                        </td>
                        <td class="py-3.5 text-gray-600">1,540</td>
                        <td class="py-3.5 text-gray-600">5.8 min</td>
                        <td class="py-3.5 font-semibold text-emerald-600">$18.48</td>
                        <td class="py-3.5 pr-6">
                            <div class="flex items-center gap-1.5">
                                <button class="p-1.5 border border-gray-200 rounded-lg text-gray-500 hover:bg-gray-50 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125"/></svg>
                                </button>
                                <button class="p-1.5 border border-gray-200 rounded-lg text-gray-500 hover:bg-gray-50 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z"/></svg>
                                </button>
                                <button onclick="togglePaywall(this)" data-on="true"
                                        class="relative inline-flex h-5 w-8 cursor-pointer rounded-full border-2 border-transparent transition-colors bg-emerald-500">
                                    <span class="inline-block h-4 w-4 rounded-full bg-white shadow transform transition translate-x-3"></span>
                                </button>
                            </div>
                        </td>
                    </tr>

                    {{-- Row 3 --}}
                    <tr class="border-b border-gray-50 hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-3.5 font-medium text-gray-900">My First Month Dockerizing a Laravel App</td>
                        <td class="py-3.5">
                            <span class="text-[10px] font-bold bg-gray-100 text-gray-500 px-2 py-0.5 rounded-full">Free</span>
                        </td>
                        <td class="py-3.5 text-gray-600">4,102</td>
                        <td class="py-3.5 text-gray-600">4.1 min</td>
                        <td class="py-3.5 font-semibold text-gray-300">—</td>
                        <td class="py-3.5 pr-6">
                            <div class="flex items-center gap-1.5">
                                <button class="p-1.5 border border-gray-200 rounded-lg text-gray-500 hover:bg-gray-50 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125"/></svg>
                                </button>
                                <button class="p-1.5 border border-gray-200 rounded-lg text-gray-500 hover:bg-gray-50 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z"/></svg>
                                </button>
                                <button onclick="togglePaywall(this)" data-on="false"
                                        class="relative inline-flex h-5 w-8 cursor-pointer rounded-full border-2 border-transparent transition-colors bg-gray-200">
                                    <span class="inline-block h-4 w-4 rounded-full bg-white shadow transform transition translate-x-0"></span>
                                </button>
                            </div>
                        </td>
                    </tr>

                    {{-- Row 4 --}}
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-3.5 font-medium text-gray-900">Understanding the Zend Engine Internals</td>
                        <td class="py-3.5">
                            <span class="text-[10px] font-bold bg-amber-50 text-amber-700 px-2 py-0.5 rounded-full">Premium</span>
                        </td>
                        <td class="py-3.5 text-gray-600">987</td>
                        <td class="py-3.5 text-gray-600">9.4 min</td>
                        <td class="py-3.5 font-semibold text-emerald-600">$11.84</td>
                        <td class="py-3.5 pr-6">
                            <div class="flex items-center gap-1.5">
                                <button class="p-1.5 border border-gray-200 rounded-lg text-gray-500 hover:bg-gray-50 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125"/></svg>
                                </button>
                                <button class="p-1.5 border border-gray-200 rounded-lg text-gray-500 hover:bg-gray-50 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z"/></svg>
                                </button>
                                <button onclick="togglePaywall(this)" data-on="true"
                                        class="relative inline-flex h-5 w-8 cursor-pointer rounded-full border-2 border-transparent transition-colors bg-emerald-500">
                                    <span class="inline-block h-4 w-4 rounded-full bg-white shadow transform transition translate-x-3"></span>
                                </button>
                            </div>
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>
    </div>

    {{-- ===== BOTTOM GRID ===== --}}
    <div class="grid grid-cols-2 gap-6">

        {{-- Monthly Target + Payout Method --}}
        <div class="bg-white border border-gray-100 rounded-3xl p-6 shadow-sm space-y-6">

            <div>
                <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-3">Monthly target</p>
                <div class="flex justify-between text-sm mb-1.5">
                    <span class="text-gray-500">$842.30 of $1,100</span>
                    <span class="font-semibold text-gray-900">77%</span>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-2">
                    <div class="bg-emerald-500 h-2 rounded-full" style="width: 77%"></div>
                </div>
                <p class="text-[11px] text-gray-400 mt-1.5">$257.70 remaining to hit target</p>
            </div>

            <div class="border-t border-gray-50 pt-5">
                <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-3">Payout method</p>
                <div class="space-y-3">

                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-gray-100 flex items-center justify-center">
                                <i class="ti ti-building-bank text-gray-500" style="font-size:16px"></i>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900">BCA Transfer</p>
                                <p class="text-xs text-gray-400">•••• 4567</p>
                            </div>
                        </div>
                        <button class="text-xs border border-gray-200 px-3 py-1.5 rounded-lg text-gray-500 hover:bg-gray-50 transition-colors">Change</button>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-gray-100 flex items-center justify-center">
                                <i class="ti ti-wallet text-gray-500" style="font-size:16px"></i>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900">OVO</p>
                                <p class="text-xs text-gray-400">+62 812 ••• 4890</p>
                            </div>
                        </div>
                        <button class="text-xs border border-gray-200 px-3 py-1.5 rounded-lg text-gray-500 hover:bg-gray-50 transition-colors">Change</button>
                    </div>

                </div>
            </div>
        </div>

        {{-- Withdrawal History --}}
        <div class="bg-white border border-gray-100 rounded-3xl p-6 shadow-sm">
            <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-4">Withdrawal history</p>
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-50">
                        <th class="text-left text-[10px] font-bold uppercase tracking-widest text-gray-400 pb-2.5">Date</th>
                        <th class="text-left text-[10px] font-bold uppercase tracking-widest text-gray-400 pb-2.5">Amount</th>
                        <th class="text-left text-[10px] font-bold uppercase tracking-widest text-gray-400 pb-2.5">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-b border-gray-50">
                        <td class="py-3 text-xs text-gray-400">Jun 1, 2026</td>
                        <td class="py-3 font-semibold text-gray-900">$600.00</td>
                        <td class="py-3"><span class="text-[10px] font-bold bg-emerald-50 text-emerald-700 px-2 py-0.5 rounded-full">Paid</span></td>
                    </tr>
                    <tr class="border-b border-gray-50">
                        <td class="py-3 text-xs text-gray-400">May 1, 2026</td>
                        <td class="py-3 font-semibold text-gray-900">$540.00</td>
                        <td class="py-3"><span class="text-[10px] font-bold bg-emerald-50 text-emerald-700 px-2 py-0.5 rounded-full">Paid</span></td>
                    </tr>
                    <tr class="border-b border-gray-50">
                        <td class="py-3 text-xs text-gray-400">Apr 3, 2026</td>
                        <td class="py-3 font-semibold text-gray-900">$310.00</td>
                        <td class="py-3"><span class="text-[10px] font-bold bg-emerald-50 text-emerald-700 px-2 py-0.5 rounded-full">Paid</span></td>
                    </tr>
                    <tr>
                        <td class="py-3 text-xs text-gray-400">Mar 1, 2026</td>
                        <td class="py-3 font-semibold text-gray-900">$842.30</td>
                        <td class="py-3"><span class="text-[10px] font-bold bg-amber-50 text-amber-700 px-2 py-0.5 rounded-full">Pending</span></td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>

</div>
</main>
@endsection

{{-- @push('scripts') --}}
<script>
document.addEventListener('DOMContentLoaded', () => {
    const chart = document.getElementById('bar-chart');
    if (!chart) return;
    const weeks = JSON.parse(chart.dataset.weeks);
    const max = Math.max(...weeks.map(w => w.val));
    chart.innerHTML = weeks.map((w, i) => {
        const pct = Math.round((w.val / max) * 100);
        const isLast = i === weeks.length - 1;
        return `<div style="flex:1;display:flex;flex-direction:column;align-items:center;gap:4px;height:100%;justify-content:flex-end">
            <span class="text-xs ${isLast ? 'font-semibold text-emerald-600' : 'text-gray-400'}">\$${w.val}</span>
            <div style="width:100%;border-radius:6px 6px 0 0;height:${pct}%;background:${isLast ? '#16a34a' : '#d1fae5'}"></div>
            <span class="text-xs ${isLast ? 'font-semibold text-gray-900' : 'text-gray-400'}">${w.label}</span>
        </div>`;
    }).join('');
});

function togglePaywall(btn) {
    const isOn = btn.dataset.on === 'true';
    btn.dataset.on = !isOn;
    btn.classList.toggle('bg-emerald-500', !isOn);
    btn.classList.toggle('bg-gray-200', isOn);
    const dot = btn.querySelector('span');
    dot.classList.toggle('translate-x-3', !isOn);
    dot.classList.toggle('translate-x-0', isOn);
}
</script>
{{-- @endpush --}}