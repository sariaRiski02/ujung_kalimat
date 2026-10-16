@extends('layouts.workspace')

@section('content')
<main class="min-h-screen transition-all duration-300 p-8 bg-gray-50" :class="open ? 'ml-56' : 'ml-14'" x-data="{
        query: '',
        confirmUnfollow: null,
        selectedName: ''
    }">
    <div class="max-w-4xl mx-auto">

        <!-- Header -->
        <div class="mb-10">
            <h1 class="text-4xl font-serif font-bold text-gray-900 tracking-tight">Following</h1>
            <p class="text-gray-500 mt-2">People and creators you're currently following.</p>
        </div>

        <div class="mb-6">
            <label for="search-following" class="sr-only">Search following</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                    <i class="ti ti-search"></i>
                </span>
                <input
                    id="search-following"
                    type="search"
                    x-model="query"
                    placeholder="Cari following..."
                    class="w-full rounded-3xl border border-gray-200 bg-white py-3 pl-12 pr-4 text-sm text-gray-900 shadow-sm outline-none transition focus:border-black focus:ring-2 focus:ring-black/10"
                />
            </div>
        </div>

        @if ($followings->isEmpty())
            <div class="rounded-3xl border border-dashed border-gray-200 bg-white p-8 text-center text-gray-500">
                Kamu belum mengikuti siapa pun.
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach ($followings as $following)
                    <div
                        x-show="@js(Str::lower($following->name)).includes(query.toLowerCase())"
                        class="bg-white border border-gray-100 rounded-2xl p-5 flex items-center justify-between shadow-sm hover:border-gray-200 transition-all group"
                    >
                        <div class="flex items-center gap-4 min-w-0">
                            @if ($following->profile_photo_url)
                                <img src="{{ $following->profile_photo_url }}" alt="Avatar {{ $following->name }}" class="w-12 h-12 rounded-full object-cover">
                            @else
                                <div class="w-12 h-12 rounded-full bg-black text-white flex items-center justify-center text-sm font-medium">{{ $following->initials() }}</div>
                            @endif
                            <div class="min-w-0">
                                <h3 class="font-bold text-gray-900 truncate">{{ $following->name }}</h3>
                                @if ($following->bio)
                                    <p class="text-xs text-gray-500 truncate">{{ $following->bio }}</p>
                                @endif
                            </div>
                        </div>

                        <button
                            type="button"
                            @click="confirmUnfollow = {{ $following->id }}; selectedName = @js($following->name)"
                            class="px-4 py-1.5 rounded-full border border-gray-200 text-xs font-semibold text-gray-800 hover:bg-red-50 hover:text-red-600 hover:border-red-200 transition-colors"
                        >
                            Following
                        </button>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <div
        x-show="confirmUnfollow !== null"
        x-cloak
        x-transition.opacity
        @keydown.escape.window="confirmUnfollow = null"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 p-4"
    >
        <div class="w-full max-w-md rounded-3xl bg-white p-6 shadow-2xl" @click.away="confirmUnfollow = null">
            <h2 class="text-xl font-semibold text-gray-900">Berhenti mengikuti</h2>
            <p class="mt-3 text-sm text-gray-500">
                Anda yakin ingin berhenti mengikuti <span class="font-semibold text-gray-900" x-text="selectedName"></span>?
            </p>
            <form method="POST" :action="'{{ url('/users') }}/' + confirmUnfollow + '/follow'" class="mt-6 flex justify-end gap-3">
                @csrf
                @method('DELETE')
                <button
                    type="button"
                    @click="confirmUnfollow = null"
                    class="rounded-3xl border border-gray-200 bg-white px-4 py-2 text-sm text-gray-700 hover:bg-gray-50"
                >
                    Batal
                </button>
                <button
                    type="submit"
                    class="rounded-3xl bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700"
                >
                    Berhenti
                </button>
            </form>
        </div>
    </div>
</main>
@endsection
