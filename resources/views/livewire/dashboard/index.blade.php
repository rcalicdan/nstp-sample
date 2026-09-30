<div>
    <x-slot:header>
        <x-partials.header 
            title="EVSU NSTP Analytics Dashboard" 
            subtitle="Executive summary, component metrics, and institutional enrollment statistics"
        >
            <x-slot:actions>
                <button 
                    wire:click.async="refreshStatsCache" 
                    type="button"
                    class="text-xs font-display tracking-widest uppercase border border-[#f9c22e]/40 hover:bg-[#f9c22e]/10 text-[#f9c22e] px-3 py-2 rounded-lg transition"
                    title="Warm / Refresh Analytics Cache"
                >
                    ⟳ Refresh Cache
                </button>
            </x-slot:actions>
        </x-partials.header>
    </x-slot:header>

    @include('livewire.dashboard.partials.filter-bar')

    @island(name: 'kpis', defer: true)
        @placeholder
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-7 animate-pulse">
                @for ($i = 0; $i < 4; $i++)
                    <div class="bg-gray-200/70 rounded-lg h-24 p-5 flex flex-col justify-between border border-gray-200">
                        <div class="h-3 bg-gray-300 rounded w-1/2"></div>
                        <div class="h-8 bg-gray-300 rounded w-1/3"></div>
                    </div>
                @endfor
            </div>
        @endplaceholder

        @include('livewire.dashboard.partials.kpis')
    @endisland

    @island(name: 'charts', defer: true)
        @placeholder
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-7 animate-pulse">
                @for ($i = 0; $i < 3; $i++)
                    <div class="bg-white rounded-lg border border-gray-200 p-5 h-80 flex flex-col justify-between">
                        <div class="h-4 bg-gray-200 rounded w-1/3 mb-4"></div>
                        <div class="flex-1 bg-gray-100 rounded"></div>
                    </div>
                @endfor
            </div>
        @endplaceholder

        @include('livewire.dashboard.partials.charts')
    @endisland

    @island(name: 'activity', defer: true)
        @placeholder
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 animate-pulse">
                <div class="bg-white rounded-lg h-60 border border-gray-200"></div>
                <div class="bg-white rounded-lg h-60 border border-gray-200"></div>
            </div>
        @endplaceholder

        @include('livewire.dashboard.partials.activity')
    @endisland
</div>