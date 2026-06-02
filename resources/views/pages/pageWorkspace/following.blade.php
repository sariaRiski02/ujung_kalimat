@extends('layouts.workspace')

@section('content')
<main class="min-h-screen transition-all duration-300 p-8 bg-gray-50" :class="open ? 'ml-56' : 'ml-14'">
    <div class="max-w-4xl mx-auto">

        <!-- Header -->
        <div class="mb-10">
            <h1 class="text-4xl font-serif font-bold text-gray-900 tracking-tight">Following</h1>
            <p class="text-gray-500 mt-2">People and creators you're currently following.</p>
        </div>

        <!-- Following Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            
            <!-- User Card -->
            <div class="bg-white border border-gray-100 rounded-2xl p-5 flex items-center justify-between shadow-sm hover:border-gray-200 transition-all group">
                <div class="flex items-center gap-4">
                    <img src="https://i.pravatar.cc/150?u=1" alt="Avatar" class="w-12 h-12 rounded-full object-cover">
                    <div>
                        <h3 class="font-bold text-gray-900">Sarah Jenkins</h3>
                        <p class="text-xs text-gray-500">Tech Lead & Writer</p>
                    </div>
                </div>
                <button class="px-4 py-1.5 rounded-full border border-gray-200 text-xs font-semibold hover:bg-red-50 hover:text-red-600 hover:border-red-200 transition-colors">
                    Following
                </button>
            </div>

            <!-- User Card 2 -->
            <div class="bg-white border border-gray-100 rounded-2xl p-5 flex items-center justify-between shadow-sm hover:border-gray-200 transition-all group">
                <div class="flex items-center gap-4">
                    <img src="https://i.pravatar.cc/150?u=2" alt="Avatar" class="w-12 h-12 rounded-full object-cover">
                    <div>
                        <h3 class="font-bold text-gray-900">Markus Aurelius</h3>
                        <p class="text-xs text-gray-500">Philosophy Enthusiast</p>
                    </div>
                </div>
                <button class="px-4 py-1.5 rounded-full border border-gray-200 text-xs font-semibold hover:bg-red-50 hover:text-red-600 hover:border-red-200 transition-colors">
                    Following
                </button>
            </div>

            <!-- User Card 3 -->
            <div class="bg-white border border-gray-100 rounded-2xl p-5 flex items-center justify-between shadow-sm hover:border-gray-200 transition-all group">
                <div class="flex items-center gap-4">
                    <img src="https://i.pravatar.cc/150?u=3" alt="Avatar" class="w-12 h-12 rounded-full object-cover">
                    <div>
                        <h3 class="font-bold text-gray-900">Elena Rodriguez</h3>
                        <p class="text-xs text-gray-500">UX Researcher</p>
                    </div>
                </div>
                <button class="px-4 py-1.5 rounded-full border border-gray-200 text-xs font-semibold hover:bg-red-50 hover:text-red-600 hover:border-red-200 transition-colors">
                    Following
                </button>
            </div>

        </div>

    </div>
</main>
@endsection