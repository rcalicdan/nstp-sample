@props([
    'colspan' => 10,
    'title' => 'No Records Found',
    'description' => 'Try adjusting your filters.'
])

<tr>
    <td colspan="{{ $colspan }}" {{ $attributes->merge(['class' => 'py-12 text-center']) }}>
        <p class="text-[#2d0012] font-display text-lg tracking-wide">{{ $title }}</p>
        <p class="text-gray-400 text-sm mt-1">{{ $description }}</p>
    </td>
</tr>