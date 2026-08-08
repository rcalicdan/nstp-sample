@props([
    'type' => 'button',
    'size' => 'md',
    'color' => 'primary',
    'loadingText' => 'Processing...',
    'showSpinner' => true,
])

@php
    $baseClasses = 'inline-flex items-center justify-center font-display tracking-widest uppercase rounded-lg transition-all duration-150 disabled:opacity-70 disabled:cursor-not-allowed group active:scale-[.98]';
    
    $sizeClasses = match($size) {
        'sm' => 'px-3 py-2 text-xs',
        'md' => 'px-5 py-2.5 text-sm',
        'lg' => 'px-6 py-3.5 text-base',
        default => 'px-5 py-2.5 text-sm',
    };

    $colorClasses = match($color) {
        'primary'       => 'bg-[#4a001c] hover:bg-[#660028] text-[#f9c22e] shadow-lg',
        'gold'          => 'bg-[#f9c22e] hover:bg-[#e5a800] text-[#2d0012] shadow-md',
        'danger'        => 'bg-red-500 hover:bg-red-600 text-white shadow-md',
        'outline'       => 'border border-[#e8b4c4] text-[#660028] hover:bg-[#f9e6ec]',
        'outline-light' => 'border border-white/30 text-white hover:bg-white/10 shadow-sm',
        'outline-gold'  => 'border border-[#f9c22e]/60 text-[#f9c22e] hover:bg-[#f9c22e]/10 shadow-sm',
        'ghost'         => 'text-gray-500 hover:text-[#800033] hover:bg-[#fdf2f5]',
        default         => 'bg-[#4a001c] hover:bg-[#660028] text-[#f9c22e] shadow-lg',
    };
@endphp

<button 
    type="{{ $type }}" 
    {{ $attributes->merge(['class' => "$baseClasses $sizeClasses $colorClasses"]) }}
    wire:loading.attr="disabled"
>
    <span class="flex items-center gap-2" 
          wire:loading.class="hidden" 
          @if($attributes->has('wire:target')) wire:target="{{ $attributes->get('wire:target') }}" @endif>
        {{ $slot }}
    </span>

    @if($showSpinner)
    <span class="items-center gap-2 hidden" 
          wire:loading.class.remove="hidden" 
          wire:loading.class="flex" 
          @if($attributes->has('wire:target')) wire:target="{{ $attributes->get('wire:target') }}" @endif>
        <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        {{ $loadingText }}
    </span>
    @endif
</button>