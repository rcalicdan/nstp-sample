@props(['name', 'maxWidth' => '2xl'])

@php
    $maxWidthClass = match ($maxWidth) {
        'sm' => 'max-w-sm',
        'md' => 'max-w-md',
        'lg' => 'max-w-lg',
        'xl' => 'max-w-xl',
        '2xl' => 'max-w-2xl',
        '3xl' => 'max-w-3xl',
        '4xl' => 'max-w-4xl',
        default => 'max-w-2xl',
    };
@endphp

<div x-data="{ open: false }"
    x-on:open-modal.window="
        let target = typeof $event.detail === 'object' && $event.detail !== null
            ? ($event.detail.name || $event.detail[0] || $event.detail)
            : $event.detail;

        if (target === '{{ $name }}') {
            open = true;
        }
    "
    x-on:close-modal.window="
        let target = typeof $event.detail === 'object' && $event.detail !== null
            ? ($event.detail.name || $event.detail[0] || $event.detail)
            : $event.detail;

        if (target === '{{ $name }}') {
            open = false;
        }
    "
    x-on:keydown.escape.window="open = false" style="display: none;" x-show="open"
    class="fixed inset-0 z-50 overflow-y-auto p-4 flex items-center justify-center">
    <div x-show="open" x-transition.opacity.duration.200ms class="fixed inset-0 bg-[#150008]/65" @click="open = false">
    </div>

    <div x-show="open" x-transition:enter="transition ease-out duration-250"
        x-transition:enter-start="opacity-0 translate-y-4 scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 scale-95"
        class="relative bg-white rounded-lg shadow-2xl w-full {{ $maxWidthClass }} overflow-hidden border border-[#f9e6ec] z-10 my-8">
        {{ $slot }}
    </div>
</div>
