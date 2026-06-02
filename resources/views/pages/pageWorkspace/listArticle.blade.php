@extends('layouts.workspace')

@section('content')
<main class="min-h-screen transition-all duration-300 p-8 bg-gray-50" :class="open ? 'ml-56' : 'ml-14'">
    <div class="max-w-5xl mx-auto">

        <!-- Header -->
        <div class="flex items-center justify-between mb-10">
            <div>
                <h1 class="text-4xl font-serif font-bold text-gray-900 tracking-tight">Articles</h1>
                <p class="text-gray-500 mt-2">Manage your published content and drafts.</p>
            </div>
            <a href="{{ route('workspace.write') }}" 
               class="bg-black text-white px-6 py-2.5 rounded-full text-sm font-semibold hover:bg-gray-800 transition flex items-center gap-2">
                <i class="ti ti-plus"></i> New Article
            </a>
        </div>

        <!-- Quick Stats / Filter Bar -->
        <div class="flex items-center gap-2 mb-6 text-sm">
            <button class="px-4 py-1.5 rounded-full bg-white border border-gray-200 font-medium shadow-sm">All (3)</button>
            <button class="px-4 py-1.5 rounded-full text-gray-500 hover:bg-white hover:shadow-sm transition">Published (2)</button>
            <button class="px-4 py-1.5 rounded-full text-gray-500 hover:bg-white hover:shadow-sm transition">Drafts (1)</button>
        </div>

        <!-- Search Bar -->
        <div class="mb-8 relative">
            <i class="ti ti-search absolute left-4 top-3.5 text-gray-400"></i>
            <input type="text" placeholder="Search your articles..." 
                   class="w-full pl-12 pr-4 py-3 bg-white border border-gray-200 rounded-2xl outline-none focus:ring-2 focus:ring-black/5 transition shadow-sm">
        </div>

        <!-- Article List -->
        <div class="space-y-4">
            
            <!-- Article Card Example -->
            <div class="bg-white border border-gray-200 rounded-2xl p-6 hover:border-gray-300 transition-all duration-300 group">
                <div class="flex justify-between items-start gap-6">
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-3">
                            <span class="px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider bg-emerald-50 text-emerald-600">Published</span>
                            <span class="text-xs text-gray-400">• May 31, 2026</span>
                        </div>
                        
                        <h2 class="text-lg font-bold text-gray-900 group-hover:text-emerald-700 transition">Understanding Design Patterns in Laravel</h2>
                        <p class="text-gray-500 mt-1 text-sm line-clamp-2">Learn Repository Pattern, Service Layer, and Dependency Injection in modern Laravel applications.</p>
                        
                        <div class="flex items-center gap-4 mt-4 text-xs text-gray-400">
                            <span class="flex items-center gap-1"><i class="ti ti-clock"></i> 8 min read</span>
                            <span class="flex items-center gap-1"><i class="ti ti-eye"></i> 1.2k views</span>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                        <button class="p-2 rounded-lg hover:bg-gray-100 text-gray-500"><i class="ti ti-pencil"></i></button>
                        <button class="p-2 rounded-lg hover:bg-gray-100 text-gray-500"><i class="ti ti-chart-bar"></i></button>
                        <button class="p-2 rounded-lg hover:bg-gray-100 text-red-500"><i class="ti ti-trash"></i></button>
                    </div>
                </div>
            </div>

            <!-- Repeat for other articles... -->
        </div>

    </div>
</main>
@endsection