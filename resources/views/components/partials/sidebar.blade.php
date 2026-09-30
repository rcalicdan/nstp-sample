<aside
    :class="{
        'w-64': !sidebarCollapsed,
        'w-20': sidebarCollapsed,
        'translate-x-0': sidebarOpen,
        '-translate-x-full': !sidebarOpen
    }"
    class="fixed inset-y-0 left-0 top-[5px] z-40 bg-[#2d0012] header-pattern text-white transition-all duration-300 ease-in-out md:translate-x-0 flex flex-col justify-between border-r border-[#4a001c] shadow-2xl overflow-hidden">
    <div>
        <div class="px-4 py-4 border-b border-[#4a001c] flex items-center justify-between">
            <div :class="sidebarCollapsed ? 'justify-center px-0' : 'justify-between px-4'"
                class="py-4 border-b border-[#4a001c] flex items-center transition-all duration-300">
                <a href="{{ route('home') }}" :class="sidebarCollapsed ? 'justify-center w-full' : 'gap-3'"
                    class="flex items-center overflow-hidden group transition" title="Go to Public Homepage">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo"
                        class="w-10 h-10 flex-shrink-0 drop-shadow group-hover:scale-105 transition-transform duration-200" />

                    <div x-show="!sidebarCollapsed" class="transition-opacity duration-200">
                        <h2
                            class="text-white font-display text-lg tracking-wide leading-tight whitespace-nowrap group-hover:text-[#f9c22e] transition-colors">
                            EVSU NSTP
                        </h2>
                        <p class="text-[#f9c22e] text-[10px] font-display tracking-widest uppercase whitespace-nowrap">
                            Management System
                        </p>
                    </div>
                </a>

                <button x-show="!sidebarCollapsed" @click="sidebarOpen = false" type="button"
                    class="md:hidden text-white/50 hover:text-white flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <button @click="sidebarOpen = false" type="button" class="md:hidden text-white/50 hover:text-white">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <nav class="p-3 space-y-1.5 font-display text-xs tracking-wider uppercase">
            <x-utils.nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" title="Dashboard">
                <x-slot:icon>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" />
                    </svg>
                </x-slot:icon>
            </x-utils.nav-link>

            @can('viewAny', App\Models\Post::class)
                <x-utils.nav-link :href="route('admin.posts.index')" :active="request()->routeIs('admin.posts.*')" title="News & Bulletins">
                    <x-slot:icon>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 0 1-2.25 2.25M16.5 7.5V18a2.25 2.25 0 0 0 2.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 0 0 2.25 2.25h13.5M6 7.5h3v3H6v-3Z" />
                        </svg>
                    </x-slot:icon>
                </x-utils.nav-link>
            @endcan

            <p x-show="!sidebarCollapsed"
                class="px-3 text-[10px] text-[#e8b4c4]/40 tracking-widest mb-2 whitespace-nowrap">Branches & Registry
            </p>

            <x-utils.nav-link :href="route('cwts-students.index')" :active="request()->routeIs('cwts-students.*')" title="CWTS Masterlist">
                <x-slot:icon>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" />
                    </svg>
                </x-slot:icon>
            </x-utils.nav-link>

            <x-utils.nav-link :href="route('rotc-students.index')" :active="request()->routeIs('rotc-students.*')" title="ROTC Registry">
                <x-slot:icon>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 21a9.004 9.004 0 0 0 8.716-6.747M12 21a9.004 9.004 0 0 1-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 0 1 7.843 4.582M12 3a8.997 8.997 0 0 0-7.843 4.582m15.686 0A11.953 11.953 0 0 1 12 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0 1 21 12c0 .778-.099 1.533-.284 2.253" />
                    </svg>
                </x-slot:icon>
            </x-utils.nav-link>

            <x-utils.nav-link :href="route('lts-students.index')" :active="request()->routeIs('lts-students.*')" title="LTS Registry">
                <x-slot:icon>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                    </svg>
                </x-slot:icon>
            </x-utils.nav-link>

            <p x-show="!sidebarCollapsed"
                class="px-3 text-[10px] text-[#e8b4c4]/40 tracking-widest pt-4 mb-2 whitespace-nowrap">Administration
            </p>

            @can('viewAny', App\Models\User::class)
                <x-utils.nav-link :href="route('users.index')" :active="request()->routeIs('users.*')" title="User Management">
                    <x-slot:icon>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.25 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                        </svg>
                    </x-slot:icon>
                </x-utils.nav-link>
            @endcan

            @can('viewAny', App\Models\AuditLog::class)
                <x-utils.nav-link :href="route('audit-logs.index')" :active="request()->routeIs('audit-logs.*')" title="Audit Logs">
                    <x-slot:icon>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3h7.5M6.75 21h10.5a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25Z" />
                        </svg>
                    </x-slot:icon>
                </x-utils.nav-link>
            @endcan
        </nav>
    </div>

    <div x-data="{
        userName: @js(auth()->user()->name),
        userRole: @js(auth()->user()->role->label()),
        initials: @js(auth()->user()->initials)
    }"
        @profile-updated.window="
            userName = $event.detail.name;
            const parts = userName.trim().split(' ');
            initials = parts.length >= 2 ? (parts[0][0] + parts[parts.length - 1][0]).toUpperCase() : userName.substring(0, 2).toUpperCase();
        "
        class="p-3 border-t border-[#4a001c] bg-[#150008]/40 flex items-center justify-between gap-2">
        <a href="{{ route('profile.index') }}" wire:navigate
            class="flex items-center gap-3 overflow-hidden flex-1 group">
            <div x-text="initials"
                class="w-9 h-9 rounded-full bg-[#f9c22e] text-[#2d0012] font-display font-bold flex items-center justify-center text-sm shadow flex-shrink-0 group-hover:scale-105 transition">
            </div>
            <div x-show="!sidebarCollapsed" class="overflow-hidden flex-1">
                <p x-text="userName"
                    class="text-xs font-display text-white truncate group-hover:text-[#f9c22e] transition"></p>
                <p x-text="userRole" class="text-[10px] text-[#f9c22e] font-display uppercase tracking-wider"></p>
            </div>
        </a>

        <button x-show="!sidebarCollapsed" @click.stop="$dispatch('open-modal', 'confirm-logout')" type="button"
            title="Logout Account"
            class="text-[#e8b4c4]/60 hover:text-white transition p-2 rounded-lg hover:bg-[#4a001c]/50 flex-shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
            </svg>
        </button>
    </div>
</aside>
