@props([
    'name',
    'title' => 'Remove Data?',
    'message' => 'This record will be permanently deleted and cannot be recovered.',
    'confirmText' => 'Remove',
    'cancelText' => 'Cancel',
])

<x-utils.modal :name="$name" maxWidth="sm">
    <div class="p-7 text-center">
        <div
            class="w-14 h-14 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-red-100">
            <svg class="w-7 h-7 text-red-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
            </svg>
        </div>

        <h3 class="font-display text-[#2d0012] text-lg tracking-wide uppercase mb-1">
            {{ $title }}
        </h3>

        @if ($slot->isNotEmpty())
            {{ $slot }}
        @else
            <p class="text-gray-400 text-sm font-body mb-6">
                {{ $message }}
            </p>
        @endif

        <div class="flex gap-3">
            <x-utils.button type="button" color="outline" size="md" class="flex-1"
                @click="$dispatch('close-modal', '{{ $name }}')">
                {{ $cancelText }}
            </x-utils.button>

            <x-utils.button type="button" color="danger" size="md" class="flex-1" {{ $attributes }}>
                {{ $confirmText }}
            </x-utils.button>
        </div>
    </div>
</x-utils.modal>
