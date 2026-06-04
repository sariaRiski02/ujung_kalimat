@extends('layouts.workspace')

@section('content')
<div
    x-data="articleAnalytics()"
    x-init="init()"
    class="min-h-screen font-serif transition-all duration-300 p-8 bg-gray-50" :class="open ? 'ml-56' : 'ml-14'""
>
    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- HEADER --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="border-b border-gray-200 bg-white rounded-2xl shadow-xl sticky top-0 z-30">
        <div class="max-w-7xl mx-auto px-6 py-4 flex items-center gap-3">
            <a href="#" class="text-slate-600 hover:text-slate-900 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
                </svg>
            </a>
            <span class="text-slate-600 text-sm font-sans">Semua Artikel</span>
            
            <span class="text-sm font-sans font-medium truncate max-w-xs" x-text="article.title"></span>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-8 space-y-6 font-sans">

        {{-- ══════════════════════════════════════════════════════ --}}
        {{-- 1. ARTICLE HEADER CARD --}}
        {{-- ══════════════════════════════════════════════════════ --}}
        <div class="bg-white border border-[#E8E5DE] rounded-2xl shadow-sm hover:shadow-md transition-shadow p-6 md:p-8">
            <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                {{-- Left: meta --}}
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 mb-3">
                        <span
                            :class="article.status === 'Premium' ? 'bg-gradient-to-br from-amber-400 to-amber-300 text-white text-xs font-semibold px-3 py-1.5 rounded-full' : 'bg-emerald-100 text-emerald-700 text-xs font-semibold px-3 py-1.5 rounded-full'"
                            x-text="article.status"
                        ></span>
                        <span class="text-xs text-slate-600 font-mono" x-text="article.category"></span>
                    </div>
                    <h1 class="font-serif text-2xl md:text-3xl leading-snug mb-2" x-text="article.title"></h1>
                    <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-sm text-slate-600">
                        <span class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                            Dipublikasi <span class="font-medium text-slate-900 ml-1" x-text="article.publishedAt"></span>
                        </span>
                        <span class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                            Waktu baca <span class="font-medium text-slate-900 ml-1" x-text="article.readTime + ' menit'"></span>
                        </span>
                        <span class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                            Penulis: <span class="font-medium text-slate-900 ml-1" x-text="article.author"></span>
                        </span>
                    </div>
                </div>

                {{-- Period selector --}}
                <div class="flex items-center gap-2 flex-shrink-0">
                    <span class="text-xs text-slate-600">Periode:</span>
                    <div class="flex bg-gray-100 rounded-xl p-1 gap-1">
                        <template x-for="p in ['7H','30H','90H']" :key="p">
                            <button
                                @click="period = p; refreshData()"
                                :class="period === p
                                    ? 'bg-white shadow text-slate-900 font-semibold'
                                    : 'text-slate-600 hover:text-slate-900'"
                                class="text-xs px-3 py-1.5 rounded-lg transition-all duration-200"
                                x-text="p"
                            ></button>
                        </template>
                    </div>
                </div>
            </div>

            {{-- Divider --}}
            <div class="border-t border-gray-200 my-6"></div>

            {{-- KPI Row --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 md:gap-6">
                <template x-for="kpi in kpis" :key="kpi.label">
                    <div class="flex flex-col gap-1">
                        <span class="text-xs text-slate-600 uppercase tracking-widest font-semibold" x-text="kpi.label"></span>
                        <div class="flex items-end gap-2">
                            <span class="font-serif text-3xl font-bold" x-text="kpi.value"></span>
                            <span :class="kpi.trend >= 0 ? 'text-[12px] font-semibold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-700 mb-1' : 'text-[12px] font-semibold px-2 py-0.5 rounded-full bg-rose-100 text-rose-700 mb-1'">
                                <span x-text="(kpi.trend >= 0 ? '↑' : '↓') + Math.abs(kpi.trend) + '%'"></span>
                            </span>
                        </div>
                        <span class="text-xs text-slate-600">vs periode sebelumnya</span>
                    </div>
                </template>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════ --}}
        {{-- 2. CHARTS ROW --}}
        {{-- ══════════════════════════════════════════════════════ --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            {{-- Reading Time Line Chart --}}
            <div class="bg-white border border-[#E8E5DE] rounded-2xl shadow-sm hover:shadow-md transition-shadow p-6" style="animation-delay:.1s">
                <div class="flex items-center justify-between mb-1">
                    <h2 class="font-semibold text-slate-900">Tren Waktu Baca Harian</h2>
                    <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-700" x-text="'↑ ' + readingTimeTrend + '%'"></span>
                </div>
                <p class="text-xs text-slate-600 mb-5">Rata-rata menit yang dihabiskan pembaca per hari</p>
                <div style="height:220px" class="relative overflow-hidden">
                    <canvas id="readingChart" class="w-full h-full"></canvas>
                    <div id="readingTooltip" class="absolute bg-slate-900 text-white font-mono text-xs px-2 py-1 rounded pointer-events-none whitespace-nowrap" style="opacity:0; transform:translate(-50%,-110%);"></div>
                </div>
            </div>

            {{-- Earnings Trend --}}
            <div class="bg-white border border-[#E8E5DE] rounded-2xl shadow-sm hover:shadow-md transition-shadow p-6" style="animation-delay:.18s">
                <div class="flex items-center justify-between mb-1">
                    <h2 class="font-semibold text-slate-900">Tren Pendapatan Harian</h2>
                    <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-700" x-text="'↑ ' + earningsTrend + '%'"></span>
                </div>
                <p class="text-xs text-slate-600 mb-5">Estimasi pendapatan dari artikel ini (Rp)</p>
                <div style="height:220px" class="relative overflow-hidden">
                    <canvas id="earningsChart" class="w-full h-full"></canvas>
                    <div id="earningsTooltip" class="absolute bg-slate-900 text-white font-mono text-xs px-2 py-1 rounded pointer-events-none whitespace-nowrap" style="opacity:0; transform:translate(-50%,-110%);"></div>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════ --}}
        {{-- 3. AUDIENCE & SOURCE INSIGHT --}}
        {{-- ══════════════════════════════════════════════════════ --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            {{-- Traffic Source --}}
            <div class="bg-white border border-[#E8E5DE] rounded-2xl shadow-sm hover:shadow-md transition-shadow p-6" style="animation-delay:.24s">
                <h2 class="font-semibold mb-1">Sumber Traffic</h2>
                <p class="text-xs text-slate-600 mb-6">Dari mana pembaca menemukan artikel ini</p>
                <div class="space-y-4">
                    <template x-for="src in trafficSources" :key="src.name">
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <div class="flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full flex-shrink-0" :style="'background:' + src.color"></span>
                                    <span class="text-sm font-medium" x-text="src.name"></span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="font-mono text-sm font-semibold" x-text="src.pct + '%'"></span>
                                    <span :class="src.change >= 0 ? 'text-[10px] px-1.5 rounded-full bg-emerald-100 text-emerald-700 font-semibold' : 'text-[10px] px-1.5 rounded-full bg-rose-100 text-rose-700 font-semibold'" x-text="(src.change >= 0 ? '+' : '') + src.change + '%'"></span>
                                </div>
                            </div>
                            <div class="h-2 rounded-full bg-gray-100 overflow-hidden">
                                <div class="h-2 rounded-full transition-all duration-1000" :style="'width:' + src.pct + '%; background:' + src.color"></div>
                            </div>
                            <p class="text-xs text-slate-600 mt-1" x-text="src.visits.toLocaleString('id-ID') + ' kunjungan'"></p>
                        </div>
                    </template>
                </div>
            </div>

            {{-- Reader Retention --}}
            <div class="bg-white border border-[#E8E5DE] rounded-2xl shadow-sm hover:shadow-md transition-shadow p-6" style="animation-delay:.3s">
                <h2 class="font-semibold mb-1">Reader Retention</h2>
                <p class="text-xs text-slate-600 mb-6">Seberapa jauh pembaca membaca artikel ini</p>

                {{-- Big number --}}
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-16 h-16 rounded-2xl flex items-center justify-center flex-shrink-0 bg-emerald-100">
                        <svg class="w-7 h-7 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <div>
                        <p class="font-serif text-4xl font-bold" x-text="retentionOverall + '%'"></p>
                        <p class="text-xs text-slate-600">Membaca sampai selesai</p>
                    </div>
                    <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-700 ml-2" x-text="'↑ 4.2%'"></span>
                </div>

                {{-- Funnel --}}
                <div class="space-y-3">
                    <template x-for="seg in retentionSegments" :key="seg.label">
                        <div>
                            <div class="flex justify-between text-xs mb-1">
                                <span class="text-slate-600" x-text="seg.label"></span>
                                <span class="font-mono font-semibold" x-text="seg.pct + '%'"></span>
                            </div>
                            <div class="h-2 rounded-full bg-gray-100 overflow-hidden">
                                <div class="h-2 rounded-full transition-all duration-1000 bg-emerald-600" :style="'width:' + seg.pct + '%'"></div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════ --}}
        {{-- 4. ACTIONABLE INSIGHTS --}}
        {{-- ══════════════════════════════════════════════════════ --}}
        <div class="bg-white border border-[#E8E5DE] rounded-2xl shadow-sm hover:shadow-md transition-shadow p-6" style="animation-delay:.36s">
            <div class="flex items-center gap-2 mb-1">
                <svg class="w-5 h-5 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M11 3a1 1 0 10-2 0v1a1 1 0 102 0V3zm4.243 2.757a1 1 0 10-1.414-1.414l-.707.707a1 1 0 101.414 1.414l.707-.707zM18 10a1 1 0 01-1 1h-1a1 1 0 110-2h1a1 1 0 011 1zM5.05 6.464A1 1 0 106.464 5.05l-.707-.707a1 1 0 00-1.414 1.414l.707.707zM5 10a1 1 0 01-1 1H3a1 1 0 110-2h1a1 1 0 011 1zm3 6v-1h4v1a2 2 0 11-4 0zm4-2a4 4 0 10-4 0h4z"/>
                </svg>
                <h2 class="font-semibold">Tips untuk Meningkatkan Performa</h2>
            </div>
            <p class="text-xs text-slate-600 mb-5">Rekomendasi berdasarkan data artikel ini</p>
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                <template x-for="tip in insights" :key="tip.id">
                    <div class="border-l-4 border-amber-400 rounded-xl p-4 bg-gray-50">
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 text-lg"
                                 :style="'background:' + tip.bgColor">
                                <span x-text="tip.icon"></span>
                            </div>
                            <div>
                                <p class="text-sm font-semibold mb-1" x-text="tip.title"></p>
                                <p class="text-xs text-slate-600 leading-relaxed" x-text="tip.desc"></p>
                            </div>
                        </div>
                        <div class="mt-3 pt-3 border-t border-gray-200">
                            <span class="text-[10px] font-semibold uppercase tracking-widest px-2 py-0.5 rounded-full"
                                  :class="tip.priority === 'Tinggi' ? 'bg-rose-100 text-rose-700' : tip.priority === 'Sedang' ? 'bg-amber-100 text-amber-700' : 'bg-green-100 text-green-700'"
                                  x-text="'Prioritas ' + tip.priority"></span>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════ --}}
        {{-- 5. DATA TABLE --}}
        {{-- ══════════════════════════════════════════════════════ --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 pb-10">

            {{-- Top Referrers --}}
            <div class="bg-white border border-[#E8E5DE] rounded-2xl shadow-sm hover:shadow-md transition-shadow p-6" style="animation-delay:.42s">
                <h2 class="font-semibold mb-1">Top Referrers</h2>
                <p class="text-xs text-slate-600 mb-5">Situs yang paling banyak mengirim pembaca</p>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr>
                                <th class="text-left text-xs uppercase tracking-wider text-slate-600 font-semibold px-3 py-2">#</th>
                                <th class="text-left text-xs uppercase tracking-wider text-slate-600 font-semibold px-3 py-2">Sumber</th>
                                <th class="text-right text-xs uppercase tracking-wider text-slate-600 font-semibold px-3 py-2">Kunjungan</th>
                                <th class="text-right text-xs uppercase tracking-wider text-slate-600 font-semibold px-3 py-2">Konversi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="(ref, i) in referrers" :key="ref.source">
                                <tr class="border-t border-gray-100 hover:bg-gray-50 transition-colors">
                                    <td class="px-3 py-3 font-mono text-xs text-slate-600" x-text="i + 1"></td>
                                    <td class="px-3 py-3">
                                        <div class="flex items-center gap-2">
                                            <div class="w-6 h-6 rounded bg-gray-100 flex items-center justify-center text-xs font-bold text-slate-600"
                                                 x-text="ref.source.charAt(0).toUpperCase()"></div>
                                            <span class="font-medium" x-text="ref.source"></span>
                                        </div>
                                    </td>
                                    <td class="px-3 py-3 text-right font-mono font-semibold" x-text="ref.visits.toLocaleString('id-ID')"></td>
                                    <td class="px-3 py-3 text-right">
                                        <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-700" x-text="ref.conv + '%'"></span>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Reader Locations --}}
            <div class="bg-white border border-[#E8E5DE] rounded-2xl shadow-sm hover:shadow-md transition-shadow p-6" style="animation-delay:.48s">
                <h2 class="font-semibold mb-1">Lokasi Pembaca</h2>
                <p class="text-xs text-slate-600 mb-5">Negara & kota dengan pembaca terbanyak</p>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr>
                                <th class="text-left text-xs uppercase tracking-wider text-slate-600 font-semibold px-3 py-2">Lokasi</th>
                                <th class="text-right text-xs uppercase tracking-wider text-slate-600 font-semibold px-3 py-2">Pembaca</th>
                                <th class="text-right text-xs uppercase tracking-wider text-slate-600 font-semibold px-3 py-2">% Total</th>
                                <th class="text-right text-xs uppercase tracking-wider text-slate-600 font-semibold px-3 py-2">Δ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="loc in locations" :key="loc.city">
                                <tr class="border-t border-gray-100 hover:bg-gray-50 transition-colors">
                                    <td class="px-3 py-3">
                                        <div class="flex items-center gap-2">
                                            <span x-text="loc.flag" class="text-lg leading-none"></span>
                                            <div>
                                                <p class="font-medium text-sm" x-text="loc.city"></p>
                                                <p class="text-xs text-slate-600" x-text="loc.country"></p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-3 py-3 text-right font-mono font-semibold" x-text="loc.readers.toLocaleString('id-ID')"></td>
                                    <td class="px-3 py-3 text-right">
                                        <div class="flex items-center justify-end gap-1">
                                            <div class="w-12 h-2 rounded-full bg-gray-100 overflow-hidden">
                                                <div class="h-2 rounded-full transition-all duration-1000 bg-emerald-600" :style="'width:' + loc.pct + '%'"></div>
                                            </div>
                                            <span class="font-mono text-xs" x-text="loc.pct + '%'"></span>
                                        </div>
                                    </td>
                                    <td class="px-3 py-3 text-right">
                                        <span :class="loc.change >= 0 ? 'text-xs px-1.5 rounded-full bg-emerald-100 text-emerald-700 font-semibold' : 'text-xs px-1.5 rounded-full bg-rose-100 text-rose-700 font-semibold'" x-text="(loc.change >= 0 ? '+' : '') + loc.change + '%'" ></span>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>{{-- /max-w container --}}
</div>{{-- /x-data --}}

{{-- ══════════════════════════════════════════════════════════════════ --}}
{{-- Chart.js (Alpine is provided in the layout) --}}
{{-- ══════════════════════════════════════════════════════════════════ --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<script>
function articleAnalytics() {
    return {
        period: '30H',
        readingTimeTrend: 12.4,
        earningsTrend: 8.7,

        article: {
            title: '',
            status: 'Premium',
            category: 'Bisnis & Karier',
            publishedAt: '12 Mei 2025',
            readTime: 14,
            author: 'Anda',
        },

        kpis: [
            { label: 'Total Views',           value: '24.8K',   trend: 18.3 },
            { label: 'Avg. Reading Time',     value: '9m 42d',  trend: 6.1  },
            { label: 'Estimated Revenue',     value: 'Rp 1.2M', trend: -3.4 },
        ],

        trafficSources: [
            { name: 'Direct',         pct: 38, visits: 9424, change: 5.2,  color: '#C9A84C' },
            { name: 'Search Engine',  pct: 29, visits: 7192, change: 14.1, color: '#2D7A6B' },
            { name: 'Media Sosial',   pct: 21, visits: 5208, change: -2.8, color: '#6B85C9' },
            { name: 'Newsletter',     pct: 12, visits: 2976, change: 22.5, color: '#C0504A' },
        ],

        retentionOverall: 71,
        retentionSegments: [
            { label: '0–25% artikel',  pct: 94 },
            { label: '25–50% artikel', pct: 83 },
            { label: '50–75% artikel', pct: 76 },
            { label: '75–100% artikel',pct: 71 },
        ],

        insights: [
            {
                id: 1,
                icon: '📉',
                bgColor: '#FAE2E1',
                title: 'Bounce Rate Tinggi di Awal',
                desc: '18% pembaca meninggalkan artikel dalam 30 detik pertama. Coba tambahkan hook visual atau pertanyaan menarik di paragraf pembuka.',
                priority: 'Tinggi',
            },
            {
                id: 2,
                icon: '🔗',
                bgColor: '#D1EDE8',
                title: 'Optimalkan Internal Link',
                desc: 'Artikel ini jarang dihubungkan dari konten lain. Tambahkan tautan dari 3–5 artikel populer Anda untuk meningkatkan distribusi traffic.',
                priority: 'Sedang',
            },
            {
                id: 3,
                icon: '📱',
                bgColor: '#F0DFA3',
                title: 'Performa Mobile Rendah',
                desc: '62% pembaca menggunakan perangkat mobile, namun waktu baca mobile 40% lebih pendek. Periksa format dan keterbacaan di layar kecil.',
                priority: 'Tinggi',
            },
            {
                id: 4,
                icon: '📢',
                bgColor: '#E8E5DE',
                title: 'Tambahkan Call-to-Action',
                desc: 'Tingkat konversi ke artikel lain hanya 8%. Tambahkan CTA eksplisit di akhir untuk mendorong pembaca melanjutkan perjalanan membaca.',
                priority: 'Sedang',
            },
            {
                id: 5,
                icon: '⭐',
                bgColor: '#D1EDE8',
                title: 'Newsletter Performa Baik',
                desc: 'Pembaca dari Newsletter memiliki waktu baca 3x lebih panjang. Pertimbangkan mempromosikan artikel ini lebih dalam di newsletter mendatang.',
                priority: 'Rendah',
            },
            {
                id: 6,
                icon: '🔍',
                bgColor: '#F0DFA3',
                title: 'SEO Berpotensi Tinggi',
                desc: 'Traffic Search Engine naik 14% bulan ini. Perbarui meta description dan tambahkan FAQ section untuk meningkatkan peringkat organik.',
                priority: 'Sedang',
            },
        ],

        referrers: [
            { source: 'twitter.com',      visits: 3841, conv: 4.2 },
            { source: 'linkedin.com',     visits: 2910, conv: 6.8 },
            { source: 'google.com',       visits: 2210, conv: 3.1 },
            { source: 'facebook.com',     visits: 1540, conv: 2.9 },
            { source: 'medium.com',       visits: 980,  conv: 5.4 },
            { source: 'instagram.com',    visits: 710,  conv: 1.7 },
        ],

        locations: [
            { flag: '🇮🇩', city: 'Jakarta',    country: 'Indonesia',  readers: 9120, pct: 37, change: 12.3  },
            { flag: '🇮🇩', city: 'Surabaya',   country: 'Indonesia',  readers: 3840, pct: 15, change: 8.1   },
            { flag: '🇲🇾', city: 'Kuala Lumpur',country: 'Malaysia',  readers: 2410, pct: 10, change: 21.4  },
            { flag: '🇸🇬', city: 'Singapura',  country: 'Singapura',  readers: 1870, pct: 8,  change: -1.2  },
            { flag: '🇮🇩', city: 'Bandung',    country: 'Indonesia',  readers: 1540, pct: 6,  change: 4.8   },
            { flag: '🇦🇺', city: 'Sydney',     country: 'Australia',  readers: 980,  pct: 4,  change: 15.0  },
        ],

        readingChartInstance: null,
        earningsChartInstance: null,

        generateDays(n) {
            const labels = [];
            const now = new Date();
            for (let i = n - 1; i >= 0; i--) {
                const d = new Date(now);
                d.setDate(d.getDate() - i);
                labels.push(d.toLocaleDateString('id-ID', { day: '2-digit', month: 'short' }));
            }
            return labels;
        },

        randomWalk(n, base, variance) {
            const data = [];
            let v = base;
            for (let i = 0; i < n; i++) {
                v += (Math.random() - 0.48) * variance;
                v = Math.max(base * 0.4, Math.min(base * 1.8, v));
                data.push(Math.round(v * 10) / 10);
            }
            return data;
        },

        buildLineChart(id, tooltipId, labels, data, color, unit) {
            const ctx = document.getElementById(id).getContext('2d');
            const grad = ctx.createLinearGradient(0, 0, 0, 220);
            grad.addColorStop(0, color + '33');
            grad.addColorStop(1, color + '00');

            return new Chart(ctx, {
                type: 'line',
                data: {
                    labels,
                    datasets: [{
                        data,
                        borderColor: color,
                        borderWidth: 2.5,
                        backgroundColor: grad,
                        fill: true,
                        tension: 0.42,
                        pointRadius: 0,
                        pointHoverRadius: 5,
                        pointHoverBackgroundColor: color,
                        pointHoverBorderColor: '#fff',
                        pointHoverBorderWidth: 2,
                    }],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: { mode: 'index', intersect: false },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            enabled: false,
                            external: ({ chart, tooltip }) => {
                                const el = document.getElementById(tooltipId);
                                if (tooltip.opacity === 0) { el.style.opacity = 0; return; }
                                const pos = chart.canvas.getBoundingClientRect();
                                el.style.opacity = 1;
                                el.style.left = tooltip.caretX + 'px';
                                el.style.top  = tooltip.caretY + 'px';
                                const item = tooltip.dataPoints[0];
                                el.textContent = item.label + ': ' + item.raw + ' ' + unit;
                            },
                        },
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            border: { display: false },
                            ticks: {
                                color: '#6B6760',
                                font: { family: 'DM Mono', size: 10 },
                                maxTicksLimit: 6,
                                maxRotation: 0,
                            },
                        },
                        y: {
                            grid: { color: '#F0EDE6' },
                            border: { display: false, dash: [4, 4] },
                            ticks: {
                                color: '#6B6760',
                                font: { family: 'DM Mono', size: 10 },
                                maxTicksLimit: 5,
                            },
                        },
                    },
                },
            });
        },

        init() {
            this.$nextTick(() => {
                const n = this.period === '7H' ? 7 : this.period === '30H' ? 30 : 90;
                const labels = this.generateDays(n);

                const readData = this.randomWalk(n, 8.5, 3);
                const earnData = this.randomWalk(n, 40000, 15000);

                this.readingChartInstance = this.buildLineChart(
                    'readingChart', 'readingTooltip',
                    labels, readData, '#2D7A6B', 'mnt'
                );
                this.earningsChartInstance = this.buildLineChart(
                    'earningsChart', 'earningsTooltip',
                    labels, earnData, '#C9A84C', 'Rp'
                );
            });
        },

        refreshData() {
            const n = this.period === '7H' ? 7 : this.period === '30H' ? 30 : 90;
            const labels = this.generateDays(n);

            if (this.readingChartInstance) {
                this.readingChartInstance.data.labels = labels;
                this.readingChartInstance.data.datasets[0].data = this.randomWalk(n, 8.5, 3);
                this.readingChartInstance.update('active');
            }
            if (this.earningsChartInstance) {
                this.earningsChartInstance.data.labels = labels;
                this.earningsChartInstance.data.datasets[0].data = this.randomWalk(n, 40000, 15000);
                this.earningsChartInstance.update('active');
            }
        },
    };
}
</script>
@endsection