@props([
    'align' => 'left',
])

@php
    $alignClass = match($align) {
        'center' => 'text-center',
        'right' => 'text-right',
        default => 'text-left',
    };
@endphp

<th {{ $attributes->merge(['class' => "px-4 py-3 font-display tracking-wider text-xs uppercase $alignClass"]) }}>
    {{ $slot }}
</th>