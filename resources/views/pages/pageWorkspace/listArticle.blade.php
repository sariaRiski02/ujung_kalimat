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

        <livewire:workspace.article-list />

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
        
    </div>
</main>
@endsection
