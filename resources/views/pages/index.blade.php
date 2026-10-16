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
            @foreach (['for-you' => 'For You', 'featured' => 'Featured'] as $key => $label)
                <a
                    href="{{ route('welcome', $key === 'for-you' ? [] : ['tab' => $key]) }}"
                    @class([
                        'text-sm pb-3 transition-colors',
                        'font-medium text-gray-900 border-b-2 border-gray-900' => $tab === $key,
                        'text-gray-500 hover:text-gray-900' => $tab !== $key,
                    ])
                    @if ($tab === $key) aria-current="page" @endif
                >
                    {{ $label }}
                </a>
            @endforeach
        </div>
    </div>

    @include('partials.articles')

@endif

<div class="my-8">{{ $articles->links() }}</div>


@endsection