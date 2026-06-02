@extends('layouts.app')

@section('content')


<!-- Hero Section -->
<div class=" py-16 border-b border-black pt-10">
    <h1 class="text-6xl md:text-8xl font-serif italic mb-8">Menemukan titik, di ujung kalimat.</h1>
    <p class="max-w-xl text-lg md:text-xl font-sans text-gray-600">
        Sebuah ruang untuk esai, renungan acak, dan observasi tentang kehidupan yang tidak selalu memiliki jawaban pasti.
    </p>
</div>

@include('partials.articles')

@endsection