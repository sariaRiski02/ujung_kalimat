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
            <p class="text-sm text-gray-500 mt-1">Welcome Back, {{ auth()->user()->name }} </p>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-10">

            <!-- Total Articles -->
            <a href="{{ route('workspace.articles') }}" class="block bg-blue-50 border border-blue-100 rounded-xl p-5 hover:shadow-sm transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-blue-600 font-medium">Total Articles</p>
                        <p class="text-3xl font-bold text-gray-900 mt-1">{{ Number::format($articlesCount) }}</p>
                    </div>
                    <i class="ti ti-article text-3xl text-blue-500"></i>
                </div>
            </a>

            <!-- Readers -->
            <div class="bg-green-50 border border-green-100 rounded-xl p-5 hover:shadow-sm transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-green-600 font-medium">Total Readers</p>
                        <p class="text-3xl font-bold text-gray-900 mt-1">{{ Number::abbreviate($readersCount, maxPrecision: 1) }}</p>
                    </div>
                    <i class="ti ti-eye text-3xl text-green-500"></i>
                </div>
            </div>

            <!-- Comments -->
            <div class="bg-purple-50 border border-purple-100 rounded-xl p-5 hover:shadow-sm transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-purple-600 font-medium">Comments</p>
                        <p class="text-3xl font-bold text-gray-900 mt-1">{{ Number::abbreviate($commentsCount, maxPrecision: 1) }}</p>
                    </div>
                    <i class="ti ti-message-circle text-3xl text-purple-500"></i>
                </div>
            </div>

            <!-- Followers -->
            <a href="{{ route('workspace.followers') }}" class="block bg-orange-50 border border-orange-100 rounded-xl p-5 hover:shadow-sm transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-orange-600 font-medium">Followers</p>
                        <p class="text-3xl font-bold text-gray-900 mt-1">{{ Number::abbreviate($followersCount, maxPrecision: 1) }}</p>
                    </div>
                    <i class="ti ti-users text-3xl text-orange-500"></i>
                </div>
            </a>

            <!-- Following -->
            <a href="{{ route('workspace.following') }}" class="block bg-pink-50 border border-pink-100 rounded-xl p-5 hover:shadow-sm transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-pink-600 font-medium">Following</p>
                        <p class="text-3xl font-bold text-gray-900 mt-1">{{ Number::abbreviate($followingCount, maxPrecision: 1) }}</p>
                    </div>
                    <i class="ti ti-user-plus text-3xl text-pink-500"></i>
                </div>
            </a>

            <!-- Monetization -->
            <a href="{{ route('workspace.monetization') }}" class="block bg-amber-50 border border-amber-100 rounded-xl p-5 hover:shadow-sm transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-amber-600 font-medium">Available Balance</p>
                        <p class="text-3xl font-bold text-gray-900 mt-1">Rp {{ number_format($availableBalance, 0, ',', '.') }}</p>
                        <p class="text-xs text-gray-400 mt-1">Total earnings Rp {{ number_format($totalEarnings, 0, ',', '.') }}</p>
                    </div>
                    <i class="ti ti-coins text-3xl text-amber-500"></i>
                </div>
            </a>

        </div>

        {{-- Recent articles --}}
        <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <p class="text-sm font-medium text-gray-900">Recent Articles</p>
                <a href="{{ route('workspace.articles') }}" class="text-xs text-gray-400 hover:text-gray-900 transition-colors">View All</a>
            </div>
            @if ($recentArticles->isEmpty())
                <div class="px-5 py-10 text-center text-sm text-gray-400">
                    Belum ada artikel.
                    <a href="{{ route('workspace.write') }}" class="text-gray-900 underline underline-offset-2">Mulai menulis</a>
                </div>
            @else
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
                        @foreach ($recentArticles as $article)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-5 py-3.5 text-gray-900 font-medium">{{ $article->clean_title }}</td>
                                <td class="px-5 py-3.5">
                                    @if ($article->status === 'published')
                                        <span class="text-xs bg-green-50 text-green-700 px-2 py-0.5 rounded-full font-medium">Publikasi</span>
                                    @else
                                        <span class="text-xs bg-yellow-50 text-yellow-700 px-2 py-0.5 rounded-full font-medium">Draft</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 text-gray-400">{{ $article->created_at->translatedFormat('j M Y') }}</td>
                                <td class="px-5 py-3.5 text-right whitespace-nowrap">
                                    @if ($article->status === 'published')
                                        <a href="{{ route('article.show', $article->slug) }}" class="text-gray-400 hover:text-gray-900 transition-colors mr-3" aria-label="Lihat artikel">
                                            <i class="ti ti-eye text-base"></i>
                                        </a>
                                    @endif
                                    <a href="{{ route('workspace.article.update', $article->slug) }}" class="text-gray-400 hover:text-gray-900 transition-colors" aria-label="Edit artikel">
                                        <i class="ti ti-edit text-base"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

    </main>
    
@endsection