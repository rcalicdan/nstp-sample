@props([
    'title' => 'Masterlist',
    'countText' => null,
])

<div {{ $attributes->merge(['class' => 'bg-white rounded-lg border border-[#f9e6ec] shadow overflow-hidden']) }}>
    <div class="bg-[#2d0012] px-5 py-2.5 flex items-center justify-between">
        <div class="flex items-center gap-2">
            @if (isset($icon))
                <span class="text-[#f9c22e]">
                    {{ $icon }}
                </span>
            @else
                <svg class="w-4 h-4 text-[#f9c22e]" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" />
                </svg>
            @endif
            <span class="text-white font-display tracking-wider text-sm uppercase">{{ $title }}</span>
        </div>
        @if ($countText)
            <span class="text-[#e8b4c4]/40 text-xs font-body">{{ $countText }}</span>
        @endif
    </div>

    <div class="overflow-x-auto">
        {{ $slot }}
    </div>

    @if (isset($footer))
        <div class="border-t border-[#f9e6ec] px-5 py-3 flex justify-center bg-white">
            {{ $footer }}
        </div>
    @endif
</div>
