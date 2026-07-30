@props([
    'title',
    'value',
    'bg' => 'bg-[#2d0012]',
    'textColor' => 'text-[#f9c22e]'
])

<div {{ $attributes->merge(['class' => "$bg rounded-lg px-5 py-4 shadow text-white relative overflow-hidden after:content-[''] after:absolute after:-bottom-2.5 after:-right-2.5 after:w-15 after:h-15 after:rounded-full after:bg-white/[0.07]"]) }}>
    <p class="text-[#e8b4c4]/60 text-xs font-display tracking-widest uppercase">
        {{ $title }}
    </p>
    <p class="text-4xl font-display {{ $textColor }} mt-1">
        {{ $value }}
    </p>
</div>