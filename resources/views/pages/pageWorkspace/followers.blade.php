@extends('layouts.workspace')

@section('content')
<main class="min-h-screen transition-all duration-300 p-8 bg-gray-50" :class="open ? 'ml-56' : 'ml-14'">
    <div class="max-w-4xl mx-auto">

        <!-- Header -->
        <div class="mb-10">
            <h1 class="text-4xl font-serif font-bold text-gray-900 tracking-tight">Followers</h1>
            <p class="text-gray-500 mt-2">People who are following your work.</p>
        </div>

        <!-- Followers List -->
        <div class="grid grid-cols-1 gap-3">
            
            <!-- Follower Card -->
            <div class="bg-white border border-gray-100 rounded-2xl p-5 flex items-center justify-between shadow-sm hover:border-gray-200 transition-all">
                <div class="flex items-center gap-4">
                    <img src="https://i.pravatar.cc/150?u=4" alt="Avatar" class="w-12 h-12 rounded-full object-cover">
                    <div>
                        <h3 class="font-bold text-gray-900">Alex Rivera</h3>
                        <p class="text-xs text-gray-500">Followed you on June 1, 2026</p>
                    </div>
                </div>
                
                <!-- Action: Follow Back or Remove -->
                <div class="flex items-center gap-2">
                    <button class="px-4 py-1.5 rounded-full bg-black text-white text-xs font-semibold hover:bg-gray-800 transition">
                        Follow Back
                    </button>
                    <button class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100 text-gray-400">
                        <i class="ti ti-dots-vertical"></i>
                    </button>
                </div>
            </div>

            <!-- Follower Card 2 -->
            <div class="bg-white border border-gray-100 rounded-2xl p-5 flex items-center justify-between shadow-sm hover:border-gray-200 transition-all">
                <div class="flex items-center gap-4">
                    <img src="https://i.pravatar.cc/150?u=5" alt="Avatar" class="w-12 h-12 rounded-full object-cover">
                    <div>
                        <h3 class="font-bold text-gray-900">Jordan Smith</h3>
                        <p class="text-xs text-gray-500">Followed you on May 29, 2026</p>
                    </div>
                </div>
                
                <div class="flex items-center gap-2">
                    <button class="px-4 py-1.5 rounded-full border border-gray-200 text-xs font-semibold hover:bg-gray-50 transition">
                        Following
                    </button>
                    <button class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100 text-gray-400">
                        <i class="ti ti-dots-vertical"></i>
                    </button>
                </div>
            </div>

            <!-- Follower Card 3 -->
            <div class="bg-white border border-gray-100 rounded-2xl p-5 flex items-center justify-between shadow-sm hover:border-gray-200 transition-all">
                <div class="flex items-center gap-4">
                    <img src="https://i.pravatar.cc/150?u=6" alt="Avatar" class="w-12 h-12 rounded-full object-cover">
                    <div>
                        <h3 class="font-bold text-gray-900">Casey Lee</h3>
                        <p class="text-xs text-gray-500">Followed you on May 25, 2026</p>
                    </div>
                </div>
                
                <div class="flex items-center gap-2">
                    <button class="px-4 py-1.5 rounded-full bg-black text-white text-xs font-semibold hover:bg-gray-800 transition">
                        Follow Back
                    </button>
                    <button class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100 text-gray-400">
                        <i class="ti ti-dots-vertical"></i>
                    </button>
                </div>
            </div>

        </div>

    </div>
</main>
@endsection