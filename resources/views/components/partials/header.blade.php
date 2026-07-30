<header class="bg-[#2d0012] header-pattern shadow-lg border-b border-[#4a001c] w-full">
    <div class="px-6 py-4 flex items-center justify-between w-full">
        <div class="flex items-center gap-4">
            <button @click="toggleSidebar()" type="button"
                class="text-white hover:text-[#f9c22e] bg-[#4a001c] hover:bg-[#660028] p-2 rounded-lg transition shadow flex-shrink-0"
                title="Toggle Sidebar">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                </svg>
            </button>

            <div>
                <span class="text-[#f9c22e] text-xs font-display tracking-[0.2em] uppercase block">
                    Republic of the Philippines
                </span>
                <h1 class="text-white font-display text-2xl leading-tight tracking-wide">
                    EVSU NSTP – CWTS
                </h1>
                <p class="text-[#e8b4c4]/60 text-xs tracking-widest uppercase font-body mt-0.5 hidden sm:block">
                    National Service Training Program · Civic Welfare Training Service
                </p>
            </div>
        </div>

        @can('create', App\Models\Student::class)
            <div class="flex items-center gap-3">
                <x-utils.button type="button" color="gold" size="md"
                    @click="$dispatch('open-modal', 'create-student')">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    <span class="hidden sm:inline">Add New Data</span>
                </x-utils.button>
            </div>
        @endcan
    </div>

    <form id="logout-form" method="POST" action="{{ route('logout') }}" class="hidden">
        @csrf
    </form>

    <x-utils.confirm-modal name="confirm-logout" title="Logout Account?"
        message="Are you sure you want to end your session and log out?" confirmText="Logout" cancelText="Cancel"
        onclick="document.getElementById('logout-form').submit()" />
</header>
