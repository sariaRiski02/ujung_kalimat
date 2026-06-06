@extends('layouts.workspace')

@section('content')
<main class="min-h-screen transition-all duration-300 p-8 bg-gray-50" :class="open ? 'ml-56' : 'ml-14'">

  {{-- Top Bar --}}
  <div class="flex items-start justify-between mb-6">
    <div>
      <h1 class="text-2xl font-medium text-gray-900">Monetization</h1>
      <p class="text-sm text-gray-400 mt-0.5">Earnings, pool share, and creator payouts — June 2026</p>
    </div>
    <div class="flex items-center gap-2">
      <button type="button" class="flex items-center gap-1.5 text-sm px-4 py-2 rounded-lg border border-gray-200 bg-white text-gray-700 hover:border-gray-400 transition">
        <i class="ti ti-plus text-base"></i> New article
      </button>
      <button class="flex items-center gap-1.5 text-sm px-4 py-2 rounded-lg bg-gray-900 text-white hover:bg-gray-700 transition">
        <i class="ti ti-arrow-up text-base"></i> Withdraw
      </button>
    </div>
  </div>

  {{-- Stat Cards --}}
  <div class="grid grid-cols-3 gap-3 mb-4">
    <div class="bg-gray-100 rounded-xl p-4">
      <p class="text-[11px] font-medium tracking-widest text-gray-400 uppercase mb-2">Estimated Earnings</p>
      <p class="text-3xl font-medium text-gray-900 leading-none mb-1">$842.30</p>
      <p class="text-xs text-gray-400">June 2026</p>
    </div>
    <div class="bg-gray-100 rounded-xl p-4">
      <p class="text-[11px] font-medium tracking-widest text-gray-400 uppercase mb-2">Member Reading Time</p>
      <p class="text-3xl font-medium text-gray-900 leading-none mb-1">42.5 hrs</p>
      <p class="text-xs text-gray-400">From paying members this month</p>
    </div>
    <div class="bg-gray-100 rounded-xl p-4">
      <p class="text-[11px] font-medium tracking-widest text-gray-400 uppercase mb-2">Contribution Share</p>
      <p class="text-3xl font-medium text-gray-900 leading-none mb-1">1.84%</p>
      <p class="text-xs text-gray-400">Of creator revenue pool</p>
    </div>
  </div>

  {{-- Creator Revenue Pool --}}
  <div class="bg-white border border-gray-200 rounded-2xl p-5 mb-4">
    <div class="flex items-center justify-between mb-4">
      <h2 class="text-sm font-medium text-gray-900">Creator revenue pool</h2>
      <span class="text-xs text-green-600 bg-green-50 rounded-lg px-2 py-1">June 2026</span>
    </div>
    <div class="grid grid-cols-4 border border-gray-200 rounded-xl overflow-hidden mb-4">
      <div class="p-4 border-r border-gray-200">
        <p class="text-[11px] uppercase tracking-wider text-gray-400 mb-1.5">Total Pool</p>
        <p class="text-lg font-medium text-gray-900">$45,780</p>
        <p class="text-[11px] text-gray-400 mt-0.5">From member subscriptions</p>
      </div>
      <div class="p-4 border-r border-gray-200">
        <p class="text-[11px] uppercase tracking-wider text-gray-400 mb-1.5">Platform Share</p>
        <p class="text-lg font-medium text-gray-900">30%</p>
        <p class="text-[11px] text-gray-400 mt-0.5">$13,734 retained</p>
      </div>
      <div class="p-4 border-r border-gray-200">
        <p class="text-[11px] uppercase tracking-wider text-gray-400 mb-1.5">Your Share</p>
        <p class="text-lg font-medium text-green-600">1.84%</p>
        <p class="text-[11px] text-gray-400 mt-0.5">Of $32,046 creator pool</p>
      </div>
      <div class="p-4">
        <p class="text-[11px] uppercase tracking-wider text-gray-400 mb-1.5">Your Earnings</p>
        <p class="text-lg font-medium text-green-600">$842.30</p>
        <p class="text-[11px] text-gray-400 mt-0.5">Available to withdraw</p>
      </div>
    </div>
    {{-- Pool bars --}}
    <p class="text-[11px] font-medium tracking-widest text-gray-400 uppercase mb-2">Pool allocation</p>
    <div class="flex items-center gap-3 mb-2">
      <span class="text-xs text-gray-500 w-36 shrink-0">Platform (30%)</span>
      <div class="flex-1 h-1.5 bg-gray-100 rounded-full overflow-hidden">
        <div class="bg-gray-400 h-full rounded-full" style="width: 30%;"></div>
      </div>
      <span class="text-xs text-gray-400 w-10 text-right">$13.7k</span>
    </div>
    <div class="flex items-center gap-3 mb-2">
      <span class="text-xs text-gray-500 w-36 shrink-0">All creators (70%)</span>
      <div class="flex-1 h-1.5 bg-gray-100 rounded-full overflow-hidden">
        <div class="bg-green-500 h-full rounded-full" style="width: 70%;"></div>
      </div>
      <span class="text-xs text-gray-400 w-10 text-right">$32.0k</span>
    </div>
    <div class="flex items-center gap-3 mb-2">
      <span class="text-xs text-gray-500 w-36 shrink-0">Your share (1.84%)</span>
      <div class="flex-1 h-1.5 bg-gray-100 rounded-full overflow-hidden">
        <div class="bg-green-300 h-full rounded-full" style="width: 2%;"></div>
      </div>
      <span class="text-xs text-gray-400 w-10 text-right">$842</span>
    </div>
  </div>

  {{-- Weekly Revenue Allocation Chart --}}
  <div class="bg-white border border-gray-200 rounded-2xl p-5 mb-4">
    <div class="flex items-center justify-between mb-3">
      <h2 class="text-sm font-medium text-gray-900">Weekly revenue allocation</h2>
      <span class="text-xs text-gray-400">Earnings + contribution share per week</span>
    </div>
    <div class="flex gap-4 mb-3 text-xs text-gray-400">
      <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-sm bg-green-500 inline-block"></span>Current week</span>
      <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-sm bg-green-200 inline-block"></span>Previous weeks</span>
    </div>
    <div class="relative w-full h-48">
      <canvas id="weeklyChart" role="img" aria-label="Bar chart of weekly earnings for June 2026: W1 $148, W2 $224, W3 $189, W4 $281">W1 $148, W2 $224, W3 $189, W4 $281</canvas>
    </div>
    <div class="grid grid-cols-4 text-center mt-2 text-[11px] text-gray-400">
      <div>W1 <span class="text-gray-300">0.62%</span></div>
      <div>W2 <span class="text-gray-300">0.88%</span></div>
      <div>W3 <span class="text-gray-300">0.79%</span></div>
      <div class="text-green-600 font-medium">W4 1.84%</div>
    </div>
  </div>

  {{-- Article Performance --}}
  <div class="bg-white border border-gray-200 rounded-2xl p-5 mb-4">
    <h2 class="text-sm font-medium text-gray-900 mb-4">Article performance</h2>
    <table class="w-full text-sm">
      <thead>
        <tr class="border-b border-gray-100">
          <th class="text-left text-[11px] font-medium text-gray-400 uppercase tracking-wider pb-2 w-2/5">Title</th>
          <th class="text-right text-[11px] font-medium text-gray-400 uppercase tracking-wider pb-2">Status</th>
          <th class="text-right text-[11px] font-medium text-gray-400 uppercase tracking-wider pb-2">Member reads</th>
          <th class="text-right text-[11px] font-medium text-gray-400 uppercase tracking-wider pb-2">Reading time</th>
          <th class="text-right text-[11px] font-medium text-gray-400 uppercase tracking-wider pb-2">Contribution</th>
          <th class="text-right text-[11px] font-medium text-gray-400 uppercase tracking-wider pb-2">Earnings</th>
          <th class="text-right text-[11px] font-medium text-gray-400 uppercase tracking-wider pb-2">Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr class="border-b border-gray-100 last:border-0">
          <td class="py-3 font-medium text-gray-900">The Future of Payments</td>
          <td class="py-3 text-right">
            <span class="text-[11px] font-medium bg-amber-50 text-amber-700 rounded-md px-2 py-0.5">Premium</span>
          </td>
          <td class="py-3 text-right text-gray-600">3,250</td>
          <td class="py-3 text-right text-gray-600">12.4 hrs</td>
          <td class="py-3 text-right font-medium text-green-600">2.4%</td>
          <td class="py-3 text-right font-medium text-green-600">$312.40</td>
          <td class="py-3 text-right">
            <div class="flex items-center justify-end gap-1">
              <button class="w-7 h-7 flex items-center justify-center border border-gray-200 rounded-lg text-gray-400 hover:text-gray-700 transition">
                <i class="ti ti-edit text-sm"></i>
              </button>
              <button class="w-7 h-7 flex items-center justify-center border border-gray-200 rounded-lg text-gray-400 hover:text-gray-700 transition">
                <i class="ti ti-chart-bar text-sm"></i>
              </button>
              <button type="button" class="relative inline-flex h-5 w-8 items-center rounded-full transition bg-green-500">
                <span class="inline-block h-3.5 w-3.5 transform rounded-full bg-white transition translate-x-4"></span>
              </button>
            </div>
          </td>
        </tr>
        <tr class="border-b border-gray-100 last:border-0">
          <td class="py-3 font-medium text-gray-900">Writer's Block Fix</td>
          <td class="py-3 text-right">
            <span class="text-[11px] font-medium bg-gray-100 text-gray-500 rounded-md px-2 py-0.5">Free</span>
          </td>
          <td class="py-3 text-right text-gray-600">970</td>
          <td class="py-3 text-right text-gray-600">5.1 hrs</td>
          <td class="py-3 text-right font-medium text-gray-300">—</td>
          <td class="py-3 text-right font-medium text-gray-300">—</td>
          <td class="py-3 text-right">
            <div class="flex items-center justify-end gap-1">
              <button class="w-7 h-7 flex items-center justify-center border border-gray-200 rounded-lg text-gray-400 hover:text-gray-700 transition">
                <i class="ti ti-edit text-sm"></i>
              </button>
              <button class="w-7 h-7 flex items-center justify-center border border-gray-200 rounded-lg text-gray-400 hover:text-gray-700 transition">
                <i class="ti ti-chart-bar text-sm"></i>
              </button>
              <button type="button" class="relative inline-flex h-5 w-8 items-center rounded-full transition bg-gray-200">
                <span class="inline-block h-3.5 w-3.5 transform rounded-full bg-white transition translate-x-0.5"></span>
              </button>
            </div>
          </td>
        </tr>
        <tr class="border-b border-gray-100 last:border-0">
          <td class="py-3 font-medium text-gray-900">Community Growth</td>
          <td class="py-3 text-right">
            <span class="text-[11px] font-medium bg-amber-50 text-amber-700 rounded-md px-2 py-0.5">Premium</span>
          </td>
          <td class="py-3 text-right text-gray-600">1,420</td>
          <td class="py-3 text-right text-gray-600">8.3 hrs</td>
          <td class="py-3 text-right font-medium text-green-600">1.2%</td>
          <td class="py-3 text-right font-medium text-green-600">$158.80</td>
          <td class="py-3 text-right">
            <div class="flex items-center justify-end gap-1">
              <button class="w-7 h-7 flex items-center justify-center border border-gray-200 rounded-lg text-gray-400 hover:text-gray-700 transition">
                <i class="ti ti-edit text-sm"></i>
              </button>
              <button class="w-7 h-7 flex items-center justify-center border border-gray-200 rounded-lg text-gray-400 hover:text-gray-700 transition">
                <i class="ti ti-chart-bar text-sm"></i>
              </button>
              <button type="button" class="relative inline-flex h-5 w-8 items-center rounded-full transition bg-green-500">
                <span class="inline-block h-3.5 w-3.5 transform rounded-full bg-white transition translate-x-4"></span>
              </button>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
  </div>

  {{-- Bottom: Revenue Breakdown + Withdrawal Summary --}}
  <div class="grid grid-cols-2 gap-4 mb-4">
    <div class="bg-white border border-gray-200 rounded-2xl p-5">
      <h2 class="text-sm font-medium text-gray-900 mb-1">Revenue breakdown</h2>
      <p class="text-xs text-gray-400 mb-4 leading-relaxed">Your share is calculated from these weighted factors.</p>
      <div class="mb-3">
        <div class="flex items-center justify-between mb-1.5">
          <span class="text-sm text-gray-700">Reading time</span>
          <span class="text-sm font-medium text-gray-900">65%</span>
        </div>
        <div class="h-1 bg-gray-100 rounded-full overflow-hidden">
          <div class="bg-green-500 h-full rounded-full" style="width: 65%;"></div>
        </div>
      </div>
      <div class="mb-3">
        <div class="flex items-center justify-between mb-1.5">
          <span class="text-sm text-gray-700">Member reads</span>
          <span class="text-sm font-medium text-gray-900">25%</span>
        </div>
        <div class="h-1 bg-gray-100 rounded-full overflow-hidden">
          <div class="bg-blue-400 h-full rounded-full" style="width: 25%;"></div>
        </div>
      </div>
      <div class="mb-3">
        <div class="flex items-center justify-between mb-1.5">
          <span class="text-sm text-gray-700">Engagement score</span>
          <span class="text-sm font-medium text-gray-900">10%</span>
        </div>
        <div class="h-1 bg-gray-100 rounded-full overflow-hidden">
          <div class="bg-amber-400 h-full rounded-full" style="width: 10%;"></div>
        </div>
      </div>
    </div>

    <div class="bg-white border border-gray-200 rounded-2xl p-5">
      <h2 class="text-sm font-medium text-gray-900 mb-3">Withdrawal summary</h2>
      <div class="divide-y divide-gray-100">
        <div class="flex justify-between py-2.5">
          <span class="text-sm text-gray-500">Available balance</span>
          <span class="text-sm font-medium text-green-600">$842.30</span>
        </div>
        <div class="flex justify-between py-2.5">
          <span class="text-sm text-gray-500">Pending earnings</span>
          <span class="text-sm font-medium text-gray-900">$112.40</span>
        </div>
        <div class="flex justify-between py-2.5">
          <span class="text-sm text-gray-500">Total withdrawn</span>
          <span class="text-sm font-medium text-gray-900">$3,210.00</span>
        </div>
        <div class="flex justify-between items-center py-2.5">
          <span class="text-xs text-gray-400">BCA Transfer ···· 4567</span>
          <button class="text-xs border border-gray-200 rounded-lg px-3 py-1 hover:border-gray-400 transition">Change</button>
        </div>
        <div class="flex justify-between items-center py-2.5">
          <span class="text-xs text-gray-400">OVO +62 812 ··· 4890</span>
          <button class="text-xs border border-gray-200 rounded-lg px-3 py-1 hover:border-gray-400 transition">Change</button>
        </div>
      </div>
    </div>
  </div>

  {{-- Withdrawal History --}}
  <div class="bg-white border border-gray-200 rounded-2xl p-5">
    <h2 class="text-sm font-medium text-gray-900 mb-3">Withdrawal history</h2>
    <table class="w-full text-sm">
      <thead>
        <tr class="border-b border-gray-100">
          <th class="text-left text-[11px] font-medium text-gray-400 uppercase tracking-wider pb-2">Date</th>
          <th class="text-right text-[11px] font-medium text-gray-400 uppercase tracking-wider pb-2">Amount</th>
          <th class="text-right text-[11px] font-medium text-gray-400 uppercase tracking-wider pb-2">Method</th>
          <th class="text-right text-[11px] font-medium text-gray-400 uppercase tracking-wider pb-2">Status</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-100">
        <tr>
          <td class="py-2.5 text-gray-500">Jun 1, 2026</td>
          <td class="py-2.5 text-right font-medium text-gray-900">$250.00</td>
          <td class="py-2.5 text-right text-gray-500">BCA Transfer</td>
          <td class="py-2.5 text-right">
            <span class="text-xs text-green-600 flex items-center justify-end gap-1"><i class="ti ti-check text-xs"></i>Paid</span>
          </td>
        </tr>
        <tr>
          <td class="py-2.5 text-gray-500">May 24, 2026</td>
          <td class="py-2.5 text-right font-medium text-gray-900">$180.00</td>
          <td class="py-2.5 text-right text-gray-500">OVO</td>
          <td class="py-2.5 text-right">
            <span class="text-xs text-green-600 flex items-center justify-end gap-1"><i class="ti ti-check text-xs"></i>Paid</span>
          </td>
        </tr>
        <tr>
          <td class="py-2.5 text-gray-500">May 10, 2026</td>
          <td class="py-2.5 text-right font-medium text-gray-900">$120.00</td>
          <td class="py-2.5 text-right text-gray-500">BCA Transfer</td>
          <td class="py-2.5 text-right">
            <span class="text-xs text-amber-600 flex items-center justify-end gap-1"><i class="ti ti-clock text-xs"></i>Pending</span>
          </td>
        </tr>
      </tbody>
    </table>
  </div>

</main>

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.js"></script>
<script>
  new Chart(document.getElementById('weeklyChart'), {
    type: 'bar',
    data: {
      labels: ['W1', 'W2', 'W3', 'W4'],
      datasets: [{
        data: [148, 224, 189, 281],
        backgroundColor: ['#a8e6c1', '#a8e6c1', '#a8e6c1', '#1DB954'],
        borderRadius: 6,
        borderSkipped: false,
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: { legend: { display: false }, tooltip: { callbacks: { label: ctx => ' $' + ctx.parsed.y } } },
      scales: {
        x: { grid: { display: false }, ticks: { color: '#9ca3af', font: { size: 12 } }, border: { display: false } },
        y: { grid: { color: 'rgba(0,0,0,0.05)' }, ticks: { color: '#9ca3af', font: { size: 12 }, callback: v => '$' + v }, border: { display: false } }
      }
    }
  });
</script>
@endpush

@endsection