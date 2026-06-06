@extends('layouts.app')

@section('content')


@if (!auth()->check())

    <!-- Hero Section -->
    <div class=" py-16 border-b border-black pt-10">
        <h1 class="text-6xl md:text-8xl font-serif italic mb-8">Menemukan titik, di ujung kalimat.</h1>
        <p class="max-w-xl text-lg md:text-xl font-sans text-gray-600">
            Sebuah ruang untuk esai, renungan acak, dan observasi tentang kehidupan yang tidak selalu memiliki jawaban pasti.
        </p>
    </div>
    @include('partials.articles')
    
@else
    
    <div class="border-b border-gray-200 mb-6 sticky bg-white top-14 z-10 py-4">
        <div class="flex gap-6">
            <button class="text-sm font-medium text-gray-900 pb-3 border-b-2 border-gray-900">
                For You
            </button>
            <button class="text-sm text-gray-500 pb-3 hover:text-gray-900 transition-colors">
                Featured
            </button>
        </div>
    </div>

    @include('partials.articles')

@endif


@endsection