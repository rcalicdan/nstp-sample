@props([
    'error' => null,
])

@php
    $errorClass = $error ? 'border-red-400 bg-red-50/50' : 'border-[#f9e6ec] bg-[#fdf2f5]/30 focus:border-[#800033]';
@endphp

<div>
    <select
        {{ $attributes->merge(['class' => "w-full border rounded px-3 py-2 text-sm transition text-[#2d0012] focus:outline-none $errorClass"]) }}>
        {{ $slot }}
    </select>
    @if ($error)
        <span class="text-[10px] text-red-500 font-semibold mt-1 block">{{ $error }}</span>
    @endif
</div>
