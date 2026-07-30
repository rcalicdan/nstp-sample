@props([
    'options' => [],
    'valueField' => 'value',
    'labelField' => 'label',
    'placeholder' => 'Select an option...',
    'error' => null,
    'searchable' => true, 
])

@php
    $errorClass = $error ? 'border-red-400 bg-red-50/50' : 'border-[#f9e6ec] bg-[#fdf2f5]/30 hover:border-[#800033]/50';
@endphp

<div x-data="{
    open: false,
    search: '',
    value: @entangle($attributes->wire('model')),
    options: {{ json_encode($options) }},

    get filteredOptions() {
        if (this.search === '') return this.options;
        const term = this.search.toLowerCase();
        return this.options.filter(opt =>
            String(opt['{{ $labelField }}']).toLowerCase().includes(term) ||
            String(opt['{{ $valueField }}']).toLowerCase().includes(term)
        );
    },

    get selectedLabel() {
        const selected = this.options.find(opt => opt['{{ $valueField }}'] == this.value);
        return selected ? selected['{{ $labelField }}'] : '{{ $placeholder }}';
    }
}" @click.outside="open = false; search = ''" class="relative w-full">
    <button type="button" @click="open = !open"
        class="w-full text-left flex justify-between items-center rounded px-3 py-2 text-sm transition {{ $errorClass }}"
        :class="{ 'text-gray-400': !value, 'text-[#2d0012]': value, 'ring-2 ring-[#800033]/20 border-[#800033] bg-white': open }">
        <span x-text="selectedLabel" class="truncate block"></span>
        <svg class="w-4 h-4 text-gray-400 transition-transform flex-shrink-0" :class="open ? 'rotate-180' : ''"
            fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    <div x-show="open" x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="opacity-0 scale-95 -translate-y-2"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0" x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-95 -translate-y-2"
        class="absolute z-[100] mt-1 w-full bg-white border border-[#f9e6ec] rounded-lg shadow-xl overflow-hidden"
        style="display: none;">
        @if ($searchable)
            <div class="p-2 border-b border-[#fdf2f5] bg-gray-50">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none">
                        <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" x-model="search" x-ref="searchInput"
                        x-effect="if(open) $nextTick(() => $refs.searchInput.focus())"
                        class="w-full pl-8 pr-3 py-1.5 text-sm border border-gray-200 rounded-md focus:outline-none focus:border-[#800033] focus:ring-1 focus:ring-[#800033]/20 placeholder-gray-400"
                        placeholder="Search options...">
                </div>
            </div>
        @endif

        <ul class="max-h-60 overflow-y-auto py-1 overscroll-contain">
            <li @click="value = ''; open = false; search = ''" x-show="value !== ''"
                class="px-3 py-2 text-sm cursor-pointer transition-colors text-gray-400 hover:bg-gray-50 border-b border-gray-50">
                {{ $placeholder }}
            </li>

            <template x-for="(option, index) in filteredOptions" :key="index">
                <li @click="value = option['{{ $valueField }}']; open = false; search = ''"
                    class="px-3 py-2 text-sm cursor-pointer transition-colors flex justify-between items-center"
                    :class="value == option['{{ $valueField }}'] ? 'bg-[#fdf2f5] text-[#800033] font-medium' :
                        'text-[#2d0012] hover:bg-[#f9e6ec]/50'">
                    <span x-text="option['{{ $labelField }}']"></span>
                    <svg x-show="value == option['{{ $valueField }}']" class="w-4 h-4 text-[#800033] flex-shrink-0"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </li>
            </template>

            <li x-show="filteredOptions.length === 0" class="px-3 py-4 text-center text-sm text-gray-400">
                No results found
            </li>
        </ul>
    </div>

    @if ($error)
        <span class="text-[10px] text-red-500 font-semibold mt-1 block">{{ $error }}</span>
    @endif
</div>
