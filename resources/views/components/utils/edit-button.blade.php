@props([
    'type' => 'button',
    'title' => 'Edit Record',
])

<button type="{{ $type }}" title="{{ $title }}"
    {{ $attributes->merge(['class' => 'w-8 h-8 rounded flex items-center justify-center transition active:scale-95 disabled:opacity-50 bg-[#f9c22e] hover:bg-[#e5a800] text-[#2d0012]']) }}>
    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round"
            d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z" />
    </svg>
</button>
