@extends('layouts.workspace')

@section('content')
<main class="min-h-screen transition-all duration-300 p-8 bg-gray-50" :class="open ? 'ml-56' : 'ml-14'" x-data="{
        query: '',
        confirmRemove: null,
        selectedName: ''
    }">
    <div class="max-w-4xl mx-auto">

        <!-- Header -->
        <div class="mb-10">
            <h1 class="text-4xl font-serif font-bold text-gray-900 tracking-tight">Followers</h1>
            <p class="text-gray-500 mt-2">People who are following your work.</p>
        </div>

        <div class="mb-6">
            <label for="search-followers" class="sr-only">Search followers</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                    <i class="ti ti-search"></i>
                </span>
                <input
                    id="search-followers"
                    type="search"
                    x-model="query"
                    placeholder="Cari follower..."
                    class="w-full rounded-3xl border border-gray-200 bg-white py-3 pl-12 pr-4 text-sm text-gray-900 shadow-sm outline-none transition focus:border-black focus:ring-2 focus:ring-black/10"
                />
            </div>
        </div>

        <!-- Followers List -->
        @if ($followers->isEmpty())
            <div class="rounded-3xl border border-dashed border-gray-200 bg-white p-8 text-center text-gray-500">
                Belum ada yang mengikuti kamu.
            </div>
        @else
            <div class="grid grid-cols-1 gap-3">
                @foreach ($followers as $follower)
                    @php($followsBack = $followingIds->contains($follower->id))
                    <div
                        x-show="@js(Str::lower($follower->name)).includes(query.toLowerCase())"
                        class="bg-white border border-gray-100 rounded-2xl p-5 flex items-center justify-between shadow-sm hover:border-gray-200 transition-all"
                    >
                        <div class="flex items-center gap-4 min-w-0">
                            @if ($follower->profile_photo_url)
                                <img src="{{ $follower->profile_photo_url }}" alt="Avatar {{ $follower->name }}" class="w-12 h-12 rounded-full object-cover">
                            @else
                                <div class="w-12 h-12 rounded-full bg-black text-white flex items-center justify-center text-sm font-medium">{{ $follower->initials() }}</div>
                            @endif
                            <div class="min-w-0">
                                <h3 class="font-bold text-gray-900 truncate">{{ $follower->name }}</h3>
                                <p class="text-xs text-gray-500">Followed you on {{ $follower->pivot->created_at->format('F j, Y') }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <form method="POST" action="{{ route($followsBack ? 'users.unfollow' : 'users.follow', $follower) }}">
                                @csrf
                                @if ($followsBack)
                                    @method('DELETE')
                                @endif
                                <button
                                    type="submit"
                                    class="{{ $followsBack ? 'px-4 py-1.5 rounded-full border border-gray-200 text-xs font-semibold hover:bg-gray-50 transition' : 'px-4 py-1.5 rounded-full bg-black text-white text-xs font-semibold hover:bg-gray-800 transition' }}"
                                >
                                    {{ $followsBack ? 'Following' : 'Follow Back' }}
                                </button>
                            </form>

                            <button
                                type="button"
                                @click="confirmRemove = {{ $follower->id }}; selectedName = @js($follower->name)"
                                class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100 text-gray-400 hover:text-red-600"
                                aria-label="Hapus follower {{ $follower->name }}"
                            >
                                <i class="ti ti-trash"></i>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <div
        x-show="confirmRemove !== null"
        x-cloak
        x-transition.opacity
        @keydown.escape.window="confirmRemove = null"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 p-4"
    >
        <div class="w-full max-w-md rounded-3xl bg-white p-6 shadow-2xl" @click.away="confirmRemove = null">
            <h2 class="text-xl font-semibold text-gray-900">Hapus follower</h2>
            <p class="mt-3 text-sm text-gray-500">
                Anda yakin ingin menghapus <span class="font-semibold text-gray-900" x-text="selectedName"></span> dari daftar followers?
            </p>
            <form method="POST" :action="'{{ url('/workspace/followers') }}/' + confirmRemove" class="mt-6 flex justify-end gap-3">
                @csrf
                @method('DELETE')
                <button
                    type="button"
                    @click="confirmRemove = null"
                    class="rounded-3xl border border-gray-200 bg-white px-4 py-2 text-sm text-gray-700 hover:bg-gray-50"
                >
                    Batal
                </button>
                <button
                    type="submit"
                    class="rounded-3xl bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700"
                >
                    Hapus
                </button>
            </form>
        </div>
    </div>
</main>
@endsection
