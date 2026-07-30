@props([
    'href' => '#',
    'active' => false,
    'wireNavigate' => true,
    'title' => '',
])

@php
    $activeClasses = $active
        ? 'bg-[#4a001c] text-[#f9c22e] shadow-md border border-[#f9c22e]/30'
        : 'text-[#e8b4c4]/70 hover:bg-[#4a001c]/50 hover:text-white';
@endphp

<a href="{{ $href }}" @if ($wireNavigate) wire:navigate @endif title="{{ $title }}"
    {{ $attributes->merge(['class' => "flex items-center gap-3 px-3 py-3 rounded-lg transition $activeClasses"]) }}>
    @if (isset($icon))
        <span class="w-5 h-5 flex-shrink-0 flex items-center justify-center">
            {{ $icon }}
        </span>
    @endif

    <span x-show="!sidebarCollapsed" class="whitespace-nowrap">
        {{ $title ?: $slot }}
    </span>
</a>
