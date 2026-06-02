@extends('layouts.workspace')

@section('content')
{{-- Main --}}
    <main
        class="min-h-screen transition-all duration-300 p-8"
        :class="open ? 'ml-56' : 'ml-14'"
    >

        {{-- Page header --}}
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900 font-serif">Dashboard</h1>
            <p class="text-sm text-gray-500 mt-1">Welcome Back, Lamda</p>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-10">

            <!-- Total Articles -->
            <div class="bg-blue-50 border border-blue-100 rounded-xl p-5 hover:shadow-sm transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-blue-600 font-medium">Total Articles</p>
                        <p class="text-3xl font-bold text-gray-900 mt-1">12</p>
                    </div>
                    <i class="ti ti-article text-3xl text-blue-500"></i>
                </div>
            </div>

            <!-- Readers -->
            <div class="bg-green-50 border border-green-100 rounded-xl p-5 hover:shadow-sm transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-green-600 font-medium">Total Readers</p>
                        <p class="text-3xl font-bold text-gray-900 mt-1">4.3K</p>
                    </div>
                    <i class="ti ti-eye text-3xl text-green-500"></i>
                </div>
            </div>

            <!-- Comments -->
            <div class="bg-purple-50 border border-purple-100 rounded-xl p-5 hover:shadow-sm transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-purple-600 font-medium">Comments</p>
                        <p class="text-3xl font-bold text-gray-900 mt-1">218</p>
                    </div>
                    <i class="ti ti-message-circle text-3xl text-purple-500"></i>
                </div>
            </div>

            <!-- Followers -->
            <div class="bg-orange-50 border border-orange-100 rounded-xl p-5 hover:shadow-sm transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-orange-600 font-medium">Followers</p>
                        <p class="text-3xl font-bold text-gray-900 mt-1">218</p>
                    </div>
                    <i class="ti ti-users text-3xl text-orange-500"></i>
                </div>
            </div>

            <!-- Following -->
            <div class="bg-pink-50 border border-pink-100 rounded-xl p-5 hover:shadow-sm transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-pink-600 font-medium">Following</p>
                        <p class="text-3xl font-bold text-gray-900 mt-1">93</p>
                    </div>
                    <i class="ti ti-user-plus text-3xl text-pink-500"></i>
                </div>
            </div>

            <!-- Monetization -->
            <div class="bg-amber-50 border border-amber-100 rounded-xl p-5 hover:shadow-sm transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-amber-600 font-medium">Monetization</p>
                        <p class="text-3xl font-bold text-gray-900 mt-1">$128</p>
                    </div>
                    <i class="ti ti-coins text-3xl text-amber-500"></i>
                </div>
            </div>

        </div>

        {{-- Recent articles --}}
        <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <p class="text-sm font-medium text-gray-900">Recent Articles</p>
                <a href="#" class="text-xs text-gray-400 hover:text-gray-900 transition-colors">View All</a>
            </div>
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-xs text-gray-400 uppercase tracking-wide">
                    <tr>
                        <th class="text-left px-5 py-3 font-medium">Title</th>
                        <th class="text-left px-5 py-3 font-medium">Status</th>
                        <th class="text-left px-5 py-3 font-medium">Date</th>
                        <th class="px-5 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-5 py-3.5 text-gray-900 font-medium">Menulis di Tengah Kebisingan</td>
                        <td class="px-5 py-3.5">
                            <span class="text-xs bg-green-50 text-green-700 px-2 py-0.5 rounded-full font-medium">Publikasi</span>
                        </td>
                        <td class="px-5 py-3.5 text-gray-400">25 Mei 2026</td>
                        <td class="px-5 py-3.5 text-right">
                            <button class="text-gray-400 hover:text-gray-900 transition-colors">
                                <i class="ti ti-dots text-base"></i>
                            </button>
                        </td>
                    </tr>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-5 py-3.5 text-gray-900 font-medium">Menemukan Titik, di Ujung Kalimat</td>
                        <td class="px-5 py-3.5">
                            <span class="text-xs bg-yellow-50 text-yellow-700 px-2 py-0.5 rounded-full font-medium">Draft</span>
                        </td>
                        <td class="px-5 py-3.5 text-gray-400">2 Jun 2026</td>
                        <td class="px-5 py-3.5 text-right">
                            <button class="text-gray-400 hover:text-gray-900 transition-colors">
                                <i class="ti ti-dots text-base"></i>
                            </button>
                        </td>
                    </tr>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-5 py-3.5 text-gray-900 font-medium">Tentang Keheningan yang Dipilih</td>
                        <td class="px-5 py-3.5">
                            <span class="text-xs bg-green-50 text-green-700 px-2 py-0.5 rounded-full font-medium">Publikasi</span>
                        </td>
                        <td class="px-5 py-3.5 text-gray-400">18 Apr 2026</td>
                        <td class="px-5 py-3.5 text-right">
                            <button class="text-gray-400 hover:text-gray-900 transition-colors">
                                <i class="ti ti-dots text-base"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

    </main>
    
@endsection