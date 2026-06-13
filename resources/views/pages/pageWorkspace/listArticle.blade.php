@extends('layouts.workspace')

@section('content')
<main class="min-h-screen transition-all duration-300 p-4 sm:p-8 bg-gray-50" :class="open ? 'ml-56' : 'ml-14'">
    <div class="max-w-6xl mx-auto">

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8 sm:mb-10">
            <div>
                <h1 class="text-3xl sm:text-4xl font-serif font-bold text-gray-900 tracking-tight">Articles</h1>
                <p class="text-sm text-gray-500 mt-1 sm:mt-2">Manage your published content and drafts.</p>
            </div>
            <a href="{{ route('workspace.write') }}"
                class="bg-black text-white px-5 py-2.5 rounded-full text-sm font-semibold hover:bg-gray-800 transition flex items-center justify-center gap-2 w-full sm:w-auto shadow-sm">
                <i class="ti ti-plus"></i> New Article
            </a>
        </div>

        {{-- Filter + Search --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
            <div class="flex items-center gap-1.5 sm:gap-2 text-xs sm:text-sm overflow-x-auto pb-1 md:pb-0 scrollbar-none">
                
                {{-- Tombol All --}}
                <a href="{{ route('workspace.articles') }}" 
                class="px-3 sm:px-4 py-1.5 rounded-full transition font-medium whitespace-nowrap {{ !request('filter') ? 'bg-black text-white shadow-sm' : 'text-gray-500 hover:bg-white hover:shadow-sm' }}">
                    All ({{ $totalArticles ?? $articles->total() }})
                </a>

                {{-- Tombol Draft --}}
                <a href="{{ route('workspace.articles', ['filter' => 'draft']) }}" 
                class="px-3 sm:px-4 py-1.5 rounded-full transition font-medium whitespace-nowrap {{ request('filter') === 'draft' ? 'bg-black text-white shadow-sm' : 'text-gray-500 hover:bg-white hover:shadow-sm' }}">
                    Draft ({{ $draftCount ?? '' }})
                </a>

                {{-- Tombol Published --}}
                <a href="{{ route('workspace.articles', ['filter' => 'published']) }}" 
                class="px-3 sm:px-4 py-1.5 rounded-full transition font-medium whitespace-nowrap {{ request('filter') === 'published' ? 'bg-black text-white shadow-sm' : 'text-gray-500 hover:bg-white hover:shadow-sm' }}">
                    Published ({{ $publishedCount ?? '' }})
                </a>

            </div>
            
            <div class="flex items-center gap-2">
                <a href="{{ route('workspace.articles')}}" 
                    class="p-2 rounded-full bg-white border border-gray-200 text-gray-400 hover:text-red-500 hover:border-red-100 hover:bg-red-50 transition flex items-center justify-center shadow-sm"
                    title="Clear Search">
                        <i class="ti ti-x text-base font-bold"></i>
                    </a>
                <div class="relative w-full md:w-auto">
                    <i class="ti ti-search absolute left-3 top-2.5 text-gray-400 text-sm"></i>
                    <input 
                        type="text" 
                        value="{{ request('search') }}"
                        placeholder="Cari artikel..."
                        class="pl-9 pr-4 py-2 bg-white border border-gray-200 rounded-xl text-sm outline-none focus:ring-2 focus:ring-black/5 shadow-sm w-full md:w-56"
                        @keyup.enter="if($el.value.trim()) { window.location.href = '{{ route('workspace.articles') }}?search=' + encodeURIComponent($el.value) + '{{ request('filter') ? '&filter='.request('filter') : '' }}' } else { window.location.href = '{{ route('workspace.articles') }}{{ request('filter') ? '?filter='.request('filter') : '' }}' }"
                    >
                </div>
            </div>
        </div>

        {{-- Responsive Table Wrapper --}}
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
            <div class="w-full overflow-x-auto min-w-full inline-block align-middle">
                <table class="w-full text-sm min-w-[800px] table-fixed">
                    <thead>
                        <tr class="border-b border-gray-100 bg-gray-50/50">
                            <th class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wider px-5 py-3.5 w-[35%]">Artikel</th>
                            <th class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wider px-5 py-3.5 w-[15%]">Status</th>
                            <th class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wider px-5 py-3.5 w-[15%]">Tipe</th>
                            <th class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wider px-5 py-3.5 w-[20%]">Pengaturan</th>
                            <th class="text-right text-xs font-semibold text-gray-400 uppercase tracking-wider px-5 py-3.5 w-[15%]">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($articles as $article)
                        <tr class="hover:bg-gray-50/80 transition group">

                            {{-- Judul + Excerpt --}}
                            <td class="px-5 py-4">
                                <div class="max-w-xs sm:max-w-md">
                                    <p class="font-semibold text-gray-900 truncate" title="{{ $article->title }}">{{ $article->title }}</p>
                                    <p class="text-xs text-gray-400 mt-1 truncate">
                                        <span class="font-medium text-gray-500">{{ $article->created_at->format('d M Y') }}</span> ·
                                        {{ str($article->clean_content)->limit(50) }}
                                    </p>
                                </div>
                            </td>
                            
                            {{-- Status Badge --}}
                            <td class="px-5 py-4 whitespace-nowrap">
                                @if ($article->status === 'published')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700">
                                        <i class="ti ti-circle-check text-[10px]"></i> Published
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-amber-50 text-amber-700">
                                        <i class="ti ti-file-text text-[10px]"></i> Draft
                                    </span>
                                @endif
                            </td>

                            {{-- Premium Badge --}}
                            <td class="px-5 py-4 whitespace-nowrap">
                                @if ($article->is_premium)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-amber-50 text-amber-700 border border-amber-100">
                                        <i class="ti ti-crown text-[10px]"></i> Premium
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-gray-50 text-gray-600">
                                        Free
                                    </span>
                                @endif
                            </td>

                            {{-- Toggles --}}
                            <td class="px-5 py-4">
                                <div class="flex flex-col gap-2">

                                    {{-- Toggle Publish --}}
                                    <form method="POST" action="{{route('workspace.article.status', $article->slug)}}" x-data class="inline-block">
                                        @csrf @method('PUT')
                                        <label class="inline-flex items-center gap-2 cursor-pointer select-none">
                                            <div class="relative inline-flex items-center">
                                                <input type="checkbox" class="sr-only" 
                                                    {{ $article->status === 'published' ? 'checked' : '' }}
                                                    @change="$el.form.submit()">
                                                <div class="w-9 h-5 rounded-full transition-colors duration-200 relative {{ $article->status === 'published' ? 'bg-emerald-500' : 'bg-gray-200' }}">
                                                    <div class="w-3.5 h-3.5 bg-white rounded-full shadow absolute top-1/2 -translate-y-1/2 transition-all duration-200 {{ $article->status === 'published' ? 'left-[19px]' : 'left-[3px]' }}"></div>
                                                </div>
                                            </div>
                                            <span class="text-xs text-gray-500 font-medium leading-none">Publish</span>
                                        </label>
                                    </form>

                                    {{-- Toggle Premium --}}
                                    <form method="POST" action="{{route('workspace.article.type', $article->slug)}}" x-data class="inline-block">
                                        @csrf @method('PUT')
                                        <label class="inline-flex items-center gap-2 cursor-pointer select-none">
                                            <div class="relative inline-flex items-center">
                                                <input type="checkbox" class="sr-only" 
                                                    {{ $article->is_premium ? 'checked' : '' }}
                                                    @change="$el.form.submit()">
                                                <div class="w-9 h-5 rounded-full transition-colors duration-200 relative {{ $article->is_premium ? 'bg-amber-500' : 'bg-gray-200' }}">
                                                    <div class="w-3.5 h-3.5 bg-white rounded-full shadow absolute top-1/2 -translate-y-1/2 transition-all duration-200 {{ $article->is_premium ? 'left-[19px]' : 'left-[3px]' }}"></div>
                                                </div>
                                            </div>
                                            <span class="text-xs text-gray-500 font-medium leading-none">Premium</span>
                                        </label>
                                    </form>

                                </div>
                            </td>

                            {{-- Aksi --}}
                            <td class="px-5 py-4 text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{route('workspace.article.update', $article->slug)}}"
                                        class="p-2 rounded-lg hover:bg-gray-100 text-gray-400 hover:text-gray-700 transition"
                                        title="Edit">
                                        <i class="ti ti-pencil"></i>
                                    </a>
                                    <a href=""
                                        class="p-2 rounded-lg hover:bg-gray-100 text-gray-400 hover:text-gray-700 transition"
                                        title="Statistik">
                                        <i class="ti ti-chart-bar"></i>
                                    </a>
                                    <form method="POST" action=""
                                        onsubmit="return confirm('Hapus artikel ini?')" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            class="p-2 rounded-lg hover:bg-red-50 text-gray-400 hover:text-red-500 transition"
                                            title="Hapus">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>

                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-16 text-gray-400 text-sm">
                                <i class="ti ti-file-off text-2xl mb-2 block"></i>
                                Belum ada artikel.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Notif Success --}}
        @session('success')
            <div x-data="{ show: true }" 
                x-show="show"
                x-init="setTimeout(() => show = false, 5000)"
                x-transition:leave="transition ease-in duration-300"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="fixed top-5 left-1/2 -translate-x-1/2 z-50 w-full max-w-sm sm:max-w-md px-4">
                
                <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-medium flex items-center justify-between shadow-lg backdrop-blur-sm bg-white/90">
                    <div class="flex items-center gap-2">
                        <i class="ti ti-circle-check text-base text-emerald-600"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button @click="show = false" class="text-emerald-500 hover:text-emerald-700 transition p-1 rounded-lg hover:bg-emerald-100/50">
                        <i class="ti ti-x text-xs"></i>
                    </button>
                </div>
            </div>
        @endsession
        
        {{-- Custom Pagination --}}
        @if ($articles->hasPages())
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 border-t border-gray-100 bg-white px-6 py-4 rounded-b-2xl border-x border-b border-gray-200 -mt-px shadow-sm">
                
                {{-- Informasi Data --}}
                <div class="text-xs text-gray-500 order-2 sm:order-1 text-center sm:text-left">
                    Show <span class="font-semibold text-gray-700">{{ $articles->firstItem() }}</span> 
                    to <span class="font-semibold text-gray-700">{{ $articles->lastItem() }}</span> 
                    from <span class="font-semibold text-gray-700">{{ $articles->total() }}</span> articles
                </div>

                {{-- Tombol Navigasi --}}
                <div class="flex items-center gap-1.5 text-sm order-1 sm:order-2 flex-wrap justify-center">
                    {{-- Tombol Previous --}}
                    @if ($articles->onFirstPage())
                        <span class="p-2 text-gray-300 cursor-not-allowed">
                            <i class="ti ti-chevron-left text-base"></i>
                        </span>
                    @else
                        <a href="{{ $articles->previousPageUrl() }}" 
                        class="p-2 rounded-xl text-gray-400 hover:text-gray-700 hover:bg-gray-50 transition">
                            <i class="ti ti-chevron-left text-base"></i>
                        </a>
                    @endif

                    {{-- Loop Nomor Halaman --}}
                    @for ($i = 1; $i <= $articles->lastPage(); $i++)
                        @if ($i == $articles->currentPage())
                            <span class="px-3 py-1.5 rounded-full bg-black text-white font-medium text-xs min-w-[32px] text-center shadow-sm">
                                {{ $i }}
                            </span>
                        @else
                            <a href="{{ $articles->url($i) }}" 
                            class="px-3 py-1.5 rounded-full text-gray-500 hover:bg-gray-100 hover:text-gray-900 font-medium text-xs min-w-[32px] text-center transition">
                                {{ $i }}
                            </a>
                        @endif
                    @endfor

                    {{-- Tombol Next --}}
                    @if ($articles->hasMorePages())
                        <a href="{{ $articles->nextPageUrl() }}" 
                        class="p-2 rounded-xl text-gray-400 hover:text-gray-700 hover:bg-gray-50 transition">
                            <i class="ti ti-chevron-right text-base"></i>
                        </a>
                    @else
                        <span class="p-2 text-gray-300 cursor-not-allowed">
                            <i class="ti ti-chevron-right text-base"></i>
                        </span>
                    @endif
                </div>
            </div>
        @endif
    </div>
</main>
@endsection