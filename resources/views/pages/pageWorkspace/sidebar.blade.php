<aside
        class="fixed top-0 left-0 h-full bg-white border-r border-gray-200 z-40 flex flex-col transition-all duration-400"
        :class="open ? 'w-56' : 'w-14'"
    >
        {{-- Logo + Hamburger --}}
        <div class="flex items-center h-14 px-3 border-b border-gray-100" :class="open ? 'justify-between' : 'justify-center'">
            <span x-show="open"
                    class="text-sm font-semibold text-gray-900 font-serif tracking-tight"
                    x-transition:enter="transition ease-out duration-200 delay-200"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                        >Ujung Kalimat
            </span>
            <button
                @click="open = !open"
                class="w-8 h-8 flex items-center justify-center rounded hover:bg-gray-100 text-gray-500 hover:text-gray-900 transition-colors"
            >
                <i class="ti ti-menu-2 text-lg"></i>
            </button>
        </div>

        {{-- Nav --}}
        <nav class="flex-1 py-4 flex flex-col gap-1 px-2">

            <a href="{{ route('workspace.dashboard') }}"
               class="flex items-center gap-3 px-2 py-2 rounded-lg text-sm {{ request()->routeIs('workspace.dashboard') ? 'text-gray-900 bg-gray-100' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-900' }} transition-colors"
               :class="open ? '' : 'justify-center'"
            >
                <i class="ti ti-layout-dashboard text-lg shrink-0"></i>
                <span x-show="open"
                        x-show="open"
                        x-transition:enter="transition ease-out duration-200 delay-200"
                        x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100">
                        Dashboard
                </span>

            </a>

            <a href="{{ route('workspace.write') }}"
               class="flex items-center gap-3 px-2 py-2 rounded-lg text-sm {{ request()->routeIs('workspace.write') ? 'text-gray-900 bg-gray-100' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-900' }} transition-colors"
               :class="open ? '' : 'justify-center'"
            >
                <i class="ti ti-pencil text-lg shrink-0"></i>
                <span x-show="open"
                        x-transition:enter="transition ease-out duration-200 delay-200"
                        x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100"
                        >Write Article</span>
            </a>

            <a href="{{ route('workspace.articles') }}"
                    
               class="flex items-center gap-3 px-2 py-2 rounded-lg text-sm {{ request()->routeIs('workspace.articles') ? 'text-gray-900 bg-gray-100' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-900' }} transition-colors"
               :class="open ? '' : 'justify-center'"
            >
                <i class="ti ti-files text-lg shrink-0"></i>
                <span x-show="open"
                
                    x-transition:enter="transition ease-out duration-200 delay-200"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100">Article List</span>
            </a>

            {{-- Monetization --}}
            <a href="{{ route('workspace.monetization') }}"
            class="flex items-center gap-3 px-2 py-2 rounded-lg text-sm text-gray-500 hover:bg-gray-100 hover:text-gray-900 transition-colors {{ request()->routeIs('workspace.monetization') ? 'text-gray-900 bg-gray-100' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-900' }}"
            :class="open ? '' : 'justify-center'"
            >
                <i class="ti ti-coins text-lg shrink-0"></i>

                <span
                    x-show="open"
                    x-transition:enter="transition ease-out duration-200 delay-200"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                >
                    Monetization
                </span>
            </a>

            <!-- Divider -->
            <div class="my-2 border-t border-gray-100"></div>

            {{-- Following --}}
            <a href="{{ route('workspace.following') }}"
            class="flex items-center gap-3 px-2 py-2 rounded-lg text-sm {{ request()->routeIs('workspace.following') ? 'text-gray-900 bg-gray-100' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-900' }} transition-colors"
            :class="open ? '' : 'justify-center'"
            >
                <i class="ti ti-user-plus text-lg shrink-0"></i>

                <span
                    x-show="open"
                    x-transition:enter="transition ease-out duration-200 delay-200"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                >
                    Following
                </span>
            </a>

            {{-- Followers --}}
            <a href="{{ route('workspace.followers') }}"
            class="flex items-center gap-3 px-2 py-2 rounded-lg text-sm {{ request()->routeIs('workspace.followers') ? 'text-gray-900 bg-gray-100' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-900' }} transition-colors"
            :class="open ? '' : 'justify-center'"
            >
                <i class="ti ti-users text-lg shrink-0"></i>

                <span
                    x-show="open"
                    x-transition:enter="transition ease-out duration-200 delay-200"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                >
                    Followers
                </span>
            </a>

        </nav>

        {{-- Profile --}}
        <div class="border-t border-gray-100 p-3">
            <a href="{{ route('workspace.profile') }}"
               class="flex items-center gap-3 px-2 py-2 rounded-lg text-sm {{ request()->routeIs('workspace.profile') ? 'text-gray-900 bg-gray-100' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-900' }} transition-colors"
               :class="open ? '' : 'justify-center'"
            >
                <img src="https://i.pravatar.cc/80?img=12" class="w-6 h-6 rounded-full shrink-0 object-cover" alt="">
                <span x-show="open" 
                    x-transition:enter="transition ease-out duration-200 delay-200"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100">Profile</span>
            </a>

            
             <a href="#"
               class="flex items-center gap-3 px-2 py-2 rounded-lg text-sm text-gray-500 hover:bg-gray-100 hover:text-gray-900 transition-colors mt-1"
               :class="open ? '' : 'justify-center'"
            >
                <i class="ti ti-logout text-lg shrink-0"></i>
                <span x-show="open" 
                    x-transition:enter="transition ease-out duration-200 delay-200"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100">Logout</span>
            </a>
        </div>

    </aside>