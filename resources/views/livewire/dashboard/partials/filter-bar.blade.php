<x-ui.filter-bar class="justify-between">
    <div class="flex items-center gap-3 w-full sm:w-auto">
        <span class="text-xs font-display tracking-widest uppercase text-[#800033] font-bold whitespace-nowrap">
            Academic Year Filter:
        </span>
        <select wire:model.live="schoolYear"
            class="border border-[#f9e6ec] rounded px-3 py-2 text-sm bg-[#fdf2f5]/50 min-w-[180px] transition focus:border-[#800033] focus:outline-none">
            <option value="">All Academic Years</option>
            @foreach ($this->availableSchoolYears as $year)
                <option value="{{ $year->label }}">{{ $year->label }}</option>
            @endforeach
        </select>
        @if($schoolYear)
            <button wire:click="clearFilters" type="button"
                class="text-xs text-gray-400 hover:text-[#660028] transition whitespace-nowrap font-semibold">
                ✕ Reset
            </button>
        @endif
    </div>
</x-ui.filter-bar>