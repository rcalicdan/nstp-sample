<div x-data="toastManager()" @notify.window="add($event.detail)"
    class="fixed top-5 right-5 z-[100] flex flex-col gap-3 max-w-sm w-full pointer-events-none">
    <template x-for="toast in toasts" :key="toast.id">
        <div x-show="toast.visible" x-transition:enter="transform ease-out duration-300 transition"
            x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-4"
            x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="pointer-events-auto w-full max-w-sm overflow-hidden rounded-xl shadow-[0_8px_30px_rgb(0,0,0,0.12)] border border-gray-100/50 bg-white/95 backdrop-blur-md flex items-start p-4 relative">
            <div class="flex-shrink-0 mt-0.5">
                <template x-if="toast.type === 'success'">
                    <div
                        class="w-8 h-8 rounded-full bg-emerald-50 border border-emerald-100 flex items-center justify-center">
                        <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                        </svg>
                    </div>
                </template>
                <template x-if="toast.type === 'error'">
                    <div class="w-8 h-8 rounded-full bg-red-50 border border-red-100 flex items-center justify-center">
                        <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </div>
                </template>
                <template x-if="toast.type === 'warning'">
                    <div
                        class="w-8 h-8 rounded-full bg-amber-50 border border-amber-100 flex items-center justify-center">
                        <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                </template>
                <template x-if="toast.type === 'info'">
                    <div
                        class="w-8 h-8 rounded-full bg-blue-50 border border-blue-100 flex items-center justify-center">
                        <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                        </svg>
                    </div>
                </template>
            </div>

            <div class="ml-3 w-0 flex-1 pt-1">
                <p x-text="toast.title" class="text-xs font-display tracking-widest uppercase text-gray-900"></p>
                <p x-text="toast.message" class="mt-1 text-sm font-body text-gray-500"></p>
            </div>

            <div class="ml-4 flex flex-shrink-0">
                <button @click="remove(toast.id)" type="button"
                    class="inline-flex rounded-md bg-transparent text-gray-400 hover:text-gray-500 focus:outline-none">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </template>
</div>

@push('scripts')
    <script>
        function toastManager() {
            return {
                toasts: [],
                init() {
                    @if (session()->has('success'))
                        this.add({
                            type: 'success',
                            message: @js(session('success'))
                        });
                    @endif

                    @if (session()->has('error'))
                        this.add({
                            type: 'error',
                            message: @js(session('error'))
                        });
                    @endif

                    @if (session()->has('warning'))
                        this.add({
                            type: 'warning',
                            message: @js(session('warning'))
                        });
                    @endif

                    @if (session()->has('info'))
                        this.add({
                            type: 'info',
                            message: @js(session('info'))
                        });
                    @endif
                },
                add(toast) {
                    const id = Date.now() + Math.random().toString(36);

                    const titles = {
                        'success': 'Success',
                        'error': 'Error',
                        'warning': 'Warning',
                        'info': 'Information'
                    };

                    this.toasts.push({
                        id: id,
                        type: toast.type || 'info',
                        title: toast.title || titles[toast.type] || 'Notification',
                        message: toast.message,
                        visible: true
                    });

                    setTimeout(() => this.remove(id), 4000);
                },
                remove(id) {
                    const toast = this.toasts.find(t => t.id === id);
                    if (toast) {
                        toast.visible = false;
                        setTimeout(() => {
                            this.toasts = this.toasts.filter(t => t.id !== id);
                        }, 300);
                    }
                }
            };
        }
    </script>
@endpush
