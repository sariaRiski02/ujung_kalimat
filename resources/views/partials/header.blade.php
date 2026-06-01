<nav class="sticky top-0 z-50 bg-white border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-6 h-14 flex items-center justify-between">
        <!-- Kiri -->
        <div class="flex items-center md:gap-3 lg:gap-6 gap-2 w-full">

            <a href="/" class="flex items-center gap-3">
                <img
                    src="{{ asset('images/logo.svg') }}"
                    alt="Ujung Kalimat"
                    class="w-10 h-10 object-contain shrink-0"
                >

                <span class="font-serif text-2xl font-semibold tracking-tight sm:inline hidden">
                    Ujung Kalimat
                </span>
            </a>
 
            {{-- Search --}}
            <div class="relative lg:w-auto w-full" x-data="searchDropdown()" @keydown.window.escape="close()" @click.away="close()">
                <div class="flex items-center justify-between bg-gray-100 rounded-full px-4 py-2 w-auto transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <circle cx="11" cy="11" r="8"/>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                    </svg>
                    <input
                        type="text"
                        placeholder="Search"
                        class="ml-3 bg-transparent outline-none text-sm w-full placeholder:text-gray-500"
                        @focus="open()"
                        @input="query = $event.target.value"
                        x-model="query"
                    >
                    <button 
                        type="button"
                        x-show="query.length > 0"
                        @click="query = ''; $el.focus()"
                        class="text-gray-400 hover:text-black transition p-0.5"
                        x-transition
                    >
                        <i class="ti ti-x text-sm"></i>
                    </button>
                    
                </div>
                <div
                    x-show="isOpen"
                    x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    class="absolute top-full mt-2 left-0 w-80 bg-white border border-gray-200 rounded-2xl shadow-xl z-[60] overflow-hidden"
                >
                    {{-- Kondisi Belum Mengetik --}}
                    <template x-if="!query">
                        <div>
                            <p class="text-[10px] uppercase tracking-widest text-gray-400 px-4 pt-4 pb-2">Jelajahi topik</p>
                            <a href="#" class="flex items-center gap-3 px-4 py-2.5 hover:bg-gray-50 transition">
                                <i class="ti ti-compass text-gray-500"></i>
                                <span class="text-sm text-gray-700">Jelajahi semua topik</span>
                            </a>

                            <div class="border-t border-gray-100 mt-2">
                                <p class="text-[10px] uppercase tracking-widest text-gray-400 px-4 pt-3 pb-1">Pencarian terbaru</p>
                                @foreach(session('recent_searches', ['filsafat', 'sastra']) as $term)
                                <div class="flex items-center justify-between px-4 py-2.5 hover:bg-gray-50 transition group cursor-pointer">
                                    <div class="flex items-center gap-3">
                                        <i class="ti ti-clock text-gray-400 text-sm"></i>
                                        <span class="text-sm text-gray-600">{{ $term }}</span>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </template>

                    {{-- Kondisi Sedang Mengetik --}}
                    <template x-if="query">
                        <div class="py-2">
                            <template x-for="item in filtered" :key="item">
                                <a href="#" class="block px-4 py-2 hover:bg-gray-50 text-sm text-gray-700" x-html="highlight(item)"></a>
                            </template>
                            <template x-if="filtered.length === 0">
                                <p class="text-sm text-gray-400 px-4 py-3">Tidak ada hasil untuk "<span x-text="query"></span>"</p>
                            </template>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <!-- Kanan -->
        <div class="flex items-center gap-3" x-show="!isOpen">

            {{-- Write button --}}
            <a href="#" class="text-gray-500 hover:text-black hover:bg-gray-100 transition hidden md:flex lg:flex items-center gap-1.5 px-1 py-1.5 rounded-md ">
                <i class="ti ti-edit text-[18px]" aria-hidden="true"></i>
                <span class="text-sm md:inline hidden">Write</span>
            </a>

            {{-- Notification button --}}
            <a href="#" class="relative hidden md:flex lg:flex items-center justify-center w-9 h-9 rounded-full text-gray-500 hover:text-black hover:bg-gray-100 transition" aria-label="Notifications">
                <i class="ti ti-bell text-[20px]" aria-hidden="true"></i>
                <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full border-[1.5px] border-white"></span>
            </a>

            <!-- Profil Icon / User Menu -->
            <a href="#" class="hidden md:flex lg:flex items-center justify-center w-9 h-9 rounded-full text-gray-500 hover:text-black hover:bg-gray-100 transition" aria-label="Profile">
                <i class="ti ti-user-circle text-[24px]" aria-hidden="true"></i>
            </a>

            {{-- login --}}
            {{-- <a href="#"
               class="bg-black text-white px-4 border py-2 rounded-full text-sm hover:bg-gray-800 transition">
                Login
            </a> --}}
            
            {{-- navigation on phone --}}
            <button class="md:hidden text-gray-500 hover:text-black p-2 cursor-pointer">
                <i class="ti ti-layout-grid text-[24px]" aria-hidden="true"></i>
            </button>

        </div>

    </div>
</nav>