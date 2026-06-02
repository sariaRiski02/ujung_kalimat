@extends('layouts.workspace')

@section('content')
<main class="min-h-screen transition-all duration-300 p-8 bg-gray-50" :class="open ? 'ml-56' : 'ml-14'">
    <div class="max-w-5xl mx-auto">

        <!-- Header -->
        <header class="mb-10">
            <h1 class="text-4xl font-serif font-bold text-gray-900 tracking-tight">Monetization</h1>
            <p class="text-gray-500 mt-2">Manage your earnings, payouts, and monetization settings.</p>
        </header>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Left Column: Status & Earnings -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white border border-gray-100 rounded-3xl p-8 shadow-sm">
                    <div class="flex items-start justify-between">
                        <div>
                            <span class="text-[10px] font-bold uppercase tracking-widest text-emerald-600 bg-emerald-50 px-2 py-1 rounded-md">Creator Program</span>
                            <h2 class="text-2xl font-bold mt-3 text-gray-900">Active Creator</h2>
                            <p class="text-gray-500 mt-2 text-sm leading-relaxed max-w-sm">
                                Your articles are eligible to earn from Ujung Kalimat's subscription revenue sharing program.
                            </p>
                        </div>
                        <div class="bg-black text-white px-4 py-1.5 rounded-full text-xs font-semibold shadow-lg">Active</div>
                    </div>
                </div>

                <div class="bg-white border border-gray-100 rounded-3xl p-8 shadow-sm" x-data="{ period: 'June 2026' }">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="font-semibold text-gray-900">Earnings Overview</h3>
                        
                        <select 
                            x-model="period"
                            class="bg-gray-50 border-none text-xs font-semibold text-gray-600 rounded-lg px-3 py-1.5 focus:ring-0 cursor-pointer hover:bg-gray-100 transition"
                        >
                            <option>June 2026</option>
                            <option>May 2026</option>
                            <option>April 2026</option>
                            <option>March 2026</option>
                        </select>
                    </div>
                    
                    <div class="flex items-end justify-center gap-4 h-32 mb-8 px-4">
                        <div class="flex flex-col items-center gap-2"><div class="w-12 bg-gray-200 rounded-t-lg h-16"></div><span class="text-[10px] text-gray-400">W1</span></div>
                        <div class="flex flex-col items-center gap-2"><div class="w-12 bg-gray-200 rounded-t-lg h-20"></div><span class="text-[10px] text-gray-400">W2</span></div>
                        <div class="flex flex-col items-center gap-2"><div class="w-12 bg-gray-200 rounded-t-lg h-12"></div><span class="text-[10px] text-gray-400">W3</span></div>
                        <div class="flex flex-col items-center gap-2"><div class="w-12 bg-gray-200 rounded-t-lg h-24"></div><span class="text-[10px] text-gray-400">W4</span></div>
                        <div class="flex flex-col items-center gap-2"><div class="w-12 bg-black rounded-t-lg h-28"></div><span class="text-[10px] font-bold text-gray-900">Now</span></div>
                    </div>

                    <div class="flex justify-center gap-6 mb-6">
                        <div class="flex items-center gap-2 text-[10px] text-gray-400 uppercase font-bold">
                            <span class="w-3 h-3 bg-gray-200 rounded-full"></span> Past Weeks
                        </div>
                        <div class="flex items-center gap-2 text-[10px] text-gray-400 uppercase font-bold">
                            <span class="w-3 h-3 bg-black rounded-full"></span> Current Week
                        </div>
                    </div>

                    <div class="space-y-4 border-t border-gray-50 pt-4">
                        <div class="flex justify-between items-center p-3 hover:bg-gray-50 rounded-xl transition">
                            <span class="text-sm text-gray-600">Subscription Revenue</span>
                            <span class="font-bold text-gray-900" x-text="period === 'June 2026' ? '$842' : '$0'"></span>
                        </div>
                        <div class="flex justify-between items-center p-3 hover:bg-gray-50 rounded-xl transition">
                            <span class="text-sm text-gray-600">Reader Donations</span>
                            <span class="font-bold text-gray-900" x-text="period === 'June 2026' ? '$281' : '$0'"></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Settings & Recent Activity -->
            <div class="space-y-6">
                <!-- Payout Method -->
                <div class="bg-white border border-gray-100 rounded-3xl p-6 shadow-sm">
                    <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-4">Payout Method</p>
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center">
                            <i class="ti ti-building-bank"></i>
                        </div>
                        <div>
                            <p class="text-sm font-semibold">BCA Transfer</p>
                            <p class="text-xs text-gray-400">•••• 4567</p>
                        </div>
                    </div>
                    <button class="w-full py-2 text-xs font-semibold border border-gray-200 rounded-xl hover:bg-gray-50 transition">Change Method</button>
                </div>

                <!-- Recent Activity -->
                <div class="bg-white border border-gray-100 rounded-3xl p-6 shadow-sm">
                    <h3 class="font-semibold text-gray-900 mb-4">Recent Activity</h3>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <div><p class="text-xs font-medium">Subscription</p><p class="text-[10px] text-gray-400">June 2</p></div>
                            <span class="text-xs font-bold text-emerald-600">+$18.40</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <div><p class="text-xs font-medium">Donation</p><p class="text-[10px] text-gray-400">June 1</p></div>
                            <span class="text-xs font-bold text-emerald-600">+$5.00</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection