<div {{ $attributes->merge(['class' => 'bg-white border border-[#f9e6ec] rounded-lg shadow-sm px-5 py-4 mb-5']) }}>
    <div class="flex flex-col sm:flex-row gap-3 items-center">
        {{ $slot }}
    </div>
</div>