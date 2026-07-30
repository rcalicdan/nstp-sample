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
            <div class="flex items-center gap-3 overflow-hidden">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-10 h-10 flex-shrink-0 drop-shadow" />
                <div x-show="!sidebarCollapsed" class="transition-opacity duration-200">
                    <h2 class="text-white font-display text-lg tracking-wide leading-tight whitespace-nowrap">EVSU NSTP
                    </h2>
                    <p class="text-[#f9c22e] text-[10px] font-display tracking-widest uppercase whitespace-nowrap">
                        Management System</p>
                </div>
            </div>
            <button @click="sidebarOpen = false" type="button" class="md:hidden text-white/50 hover:text-white">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <nav class="p-3 space-y-1.5 font-display text-xs tracking-wider uppercase">
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

            <x-utils.nav-link href="#" :active="request()->routeIs('rotc.*')" title="ROTC Registry">
                <x-slot:icon>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 21a9.004 9.004 0 0 0 8.716-6.747M12 21a9.004 9.004 0 0 1-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 0 1 7.843 4.582M12 3a8.997 8.997 0 0 0-7.843 4.582m15.686 0A11.953 11.953 0 0 1 12 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0 1 21 12c0 .778-.099 1.533-.284 2.253" />
                    </svg>
                </x-slot:icon>
            </x-utils.nav-link>

            <p x-show="!sidebarCollapsed"
                class="px-3 text-[10px] text-[#e8b4c4]/40 tracking-widest pt-4 mb-2 whitespace-nowrap">Administration
            </p>

            @if (auth()->user()->isSuperAdmin() || auth()->user()->isAdmin())
                <x-utils.nav-link href="#" :active="request()->routeIs('users.*')" title="User Management">
                    <x-slot:icon>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.25 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                        </svg>
                    </x-slot:icon>
                </x-utils.nav-link>
            @endif

            @if (auth()->user()->isSuperAdmin())
                <x-utils.nav-link href="#" :active="request()->routeIs('audit-logs.*')" title="Audit Logs">
                    <x-slot:icon>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3h7.5M6.75 21h10.5a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25Z" />
                        </svg>
                    </x-slot:icon>
                </x-utils.nav-link>
            @endif
        </nav>
    </div>

    <div class="p-3 border-t border-[#4a001c] bg-[#150008]/40">
        <div class="flex items-center gap-3">
            <div
                class="w-9 h-9 rounded-full bg-[#f9c22e] text-[#2d0012] font-display font-bold flex items-center justify-center text-sm shadow flex-shrink-0">
                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
            </div>
            <div x-show="!sidebarCollapsed" class="overflow-hidden flex-1">
                <p class="text-xs font-display text-white truncate">{{ auth()->user()->name }}</p>
                <p class="text-[10px] text-[#f9c22e] font-display uppercase tracking-wider">
                    {{ auth()->user()->role->label() }}</p>
            </div>
            <button x-show="!sidebarCollapsed" @click="$dispatch('open-modal', 'confirm-logout')" type="button"
                title="Logout" class="text-[#e8b4c4]/60 hover:text-white transition p-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
                </svg>
            </button>
        </div>
    </div>
</aside>
