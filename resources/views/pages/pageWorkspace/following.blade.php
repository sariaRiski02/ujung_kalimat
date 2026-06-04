@extends('layouts.workspace')

@section('content')
<main class="min-h-screen transition-all duration-300 p-8 bg-gray-50" :class="open ? 'ml-56' : 'ml-14'" x-data="{
        query: '',
        activeMenu: null,
        confirmDelete: null,
        selectedFollowingName: '',
        followings: [
            { id: 1, name: 'Sarah Jenkins', avatar: 'https://i.pravatar.cc/150?u=1', title: 'Tech Lead & Writer' },
            { id: 2, name: 'Markus Aurelius', avatar: 'https://i.pravatar.cc/150?u=2', title: 'Philosophy Enthusiast' },
            { id: 3, name: 'Elena Rodriguez', avatar: 'https://i.pravatar.cc/150?u=3', title: 'UX Researcher' }
        ],
        get filteredFollowings() {
            return this.followings.filter((following) => following.name.toLowerCase().includes(this.query.toLowerCase()));
        },
        openDeleteModal(following) {
            this.selectedFollowingName = following.name;
            this.confirmDelete = following.id;
            this.activeMenu = null;
        },
        deleteFollowing() {
            this.followings = this.followings.filter((following) => following.id !== this.confirmDelete);
            this.confirmDelete = null;
            this.selectedFollowingName = '';
        }
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

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <template x-if="filteredFollowings.length === 0">
                <div class="col-span-full rounded-3xl border border-dashed border-gray-200 bg-white p-8 text-center text-gray-500">
                    Tidak ada following yang cocok dengan pencarian.
                </div>
            </template>

            <template x-for="following in filteredFollowings" :key="following.id">
                <div class="bg-white border border-gray-100 rounded-2xl p-5 flex items-center justify-between shadow-sm hover:border-gray-200 transition-all group">
                    <div class="flex items-center gap-4">
                        <img :src="following.avatar" alt="Avatar" class="w-12 h-12 rounded-full object-cover">
                        <div>
                            <h3 class="font-bold text-gray-900" x-text="following.name"></h3>
                            <p class="text-xs text-gray-500" x-text="following.title"></p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <button class="px-4 py-1.5 rounded-full border border-gray-200 text-xs font-semibold text-gray-800 hover:bg-red-50 hover:text-red-600 hover:border-red-200 transition-colors">
                            Following
                        </button>

                        <div class="relative" @keydown.escape.window="activeMenu = null">
                            <button
                                type="button"
                                @click="activeMenu = activeMenu === following.id ? null : following.id"
                                class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100 text-gray-400"
                                aria-label="More actions"
                            >
                                <i class="ti ti-dots-vertical"></i>
                            </button>

                            <div
                                x-show="activeMenu === following.id"
                                x-transition
                                @click.away="activeMenu = null"
                                class="absolute right-0 z-10 mt-2 w-36 rounded-3xl border border-gray-200 bg-white p-2 shadow-lg"
                            >
                                <button
                                    type="button"
                                    @click="openDeleteModal(following)"
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
            <h2 class="text-xl font-semibold text-gray-900">Hapus following</h2>
            <p class="mt-3 text-sm text-gray-500">
                Anda yakin ingin berhenti mengikuti <span class="font-semibold text-gray-900" x-text="selectedFollowingName"></span>?
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
                    @click="deleteFollowing()"
                    class="rounded-3xl bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700"
                >
                    Hapus
                </button>
            </div>
        </div>
    </div>
</main>
@endsection