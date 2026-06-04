@extends('layouts.workspace')

@section('content')
<main class="min-h-screen transition-all duration-300 p-8 bg-gray-50" :class="open ? 'ml-56' : 'ml-14'" x-data="{
        query: '',
        activeMenu: null,
        confirmDelete: null,
        selectedFollowerName: '',
        followers: [
            { id: 1, name: 'Alex Rivera', avatar: 'https://i.pravatar.cc/150?u=4', followedAt: 'June 1, 2026', action: 'follow-back' },
            { id: 2, name: 'Jordan Smith', avatar: 'https://i.pravatar.cc/150?u=5', followedAt: 'May 29, 2026', action: 'following' },
            { id: 3, name: 'Casey Lee', avatar: 'https://i.pravatar.cc/150?u=6', followedAt: 'May 25, 2026', action: 'follow-back' }
        ],
        get filteredFollowers() {
            return this.followers.filter((follower) => follower.name.toLowerCase().includes(this.query.toLowerCase()));
        },
        openDeleteModal(follower) {
            this.selectedFollowerName = follower.name;
            this.confirmDelete = follower.id;
            this.activeMenu = null;
        },
        deleteFollower() {
            this.followers = this.followers.filter((follower) => follower.id !== this.confirmDelete);
            this.confirmDelete = null;
            this.selectedFollowerName = '';
        }
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
        <div class="grid grid-cols-1 gap-3">
            <template x-if="filteredFollowers.length === 0">
                <div class="rounded-3xl border border-dashed border-gray-200 bg-white p-8 text-center text-gray-500">
                    Tidak ada follower yang cocok dengan pencarian.
                </div>
            </template>

            <template x-for="follower in filteredFollowers" :key="follower.id">
                <div class="bg-white border border-gray-100 rounded-2xl p-5 flex items-center justify-between shadow-sm hover:border-gray-200 transition-all">
                    <div class="flex items-center gap-4">
                        <img :src="follower.avatar" alt="Avatar" class="w-12 h-12 rounded-full object-cover">
                        <div>
                            <h3 class="font-bold text-gray-900" x-text="follower.name"></h3>
                            <p class="text-xs text-gray-500">Followed you on <span x-text="follower.followedAt"></span></p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <button
                            x-bind:class="follower.action === 'following' ? 'px-4 py-1.5 rounded-full border border-gray-200 text-xs font-semibold hover:bg-gray-50 transition' : 'px-4 py-1.5 rounded-full bg-black text-white text-xs font-semibold hover:bg-gray-800 transition'"
                        >
                            <span x-text="follower.action === 'following' ? 'Following' : 'Follow Back'"></span>
                        </button>

                        <div class="relative" @keydown.escape.window="activeMenu = null">
                            <button
                                type="button"
                                @click="activeMenu = activeMenu === follower.id ? null : follower.id"
                                class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100 text-gray-400"
                                aria-label="More actions"
                            >
                                <i class="ti ti-dots-vertical"></i>
                            </button>

                            <div
                                x-show="activeMenu === follower.id"
                                x-transition
                                @click.away="activeMenu = null"
                                class="absolute right-0 z-10 mt-2 w-36 rounded-3xl border border-gray-200 bg-white p-2 shadow-lg"
                            >
                                <button
                                    type="button"
                                    @click="openDeleteModal(follower)"
                                    class="w-full rounded-2xl px-4 py-2 text-left text-sm text-red-600 hover:bg-gray-50"
                                >
                                    Hapus
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>

    <div
        x-show="confirmDelete !== null"
        x-transition.opacity
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 p-4"
    >
        <div class="w-full max-w-md rounded-3xl bg-white p-6 shadow-2xl" @click.away="confirmDelete = null">
            <h2 class="text-xl font-semibold text-gray-900">Hapus follower</h2>
            <p class="mt-3 text-sm text-gray-500">
                Anda yakin ingin menghapus <span class="font-semibold text-gray-900" x-text="selectedFollowerName"></span> dari daftar followers?
            </p>
            <div class="mt-6 flex justify-end gap-3">
                <button
                    type="button"
                    @click="confirmDelete = null"
                    class="rounded-3xl border border-gray-200 bg-white px-4 py-2 text-sm text-gray-700 hover:bg-gray-50"
                >
                    Batal
                </button>
                <button
                    type="button"
                    @click="deleteFollower()"
                    class="rounded-3xl bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700"
                >
                    Hapus
                </button>
            </div>
        </div>
    </div>
</main>
@endsection