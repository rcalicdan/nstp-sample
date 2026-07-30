<div
    class="w-full max-w-md bg-white rounded-2xl shadow-[0_20px_60px_rgba(45,0,18,0.15)] overflow-hidden border border-[#f9e6ec]">
    <div class="bg-[#2d0012] header-pattern px-8 py-10 text-center relative">
        <div class="flex justify-center mb-4">
            <img src="{{ asset('images/logo.png') }}" alt="NSTP Logo" class="w-20 h-20 drop-shadow-xl" />
        </div>

        <p class="text-[#f9c22e] text-xs font-display tracking-[0.2em] uppercase mb-1">
            Republic of the Philippines
        </p>
        <h1 class="text-white font-display text-2xl tracking-wide leading-tight">
            EVSU NSTP – CWTS
        </h1>
        <p class="text-[#e8b4c4]/60 text-[10px] tracking-widest uppercase font-body mt-2">
            Management System
        </p>
    </div>

    <div class="px-8 py-8">
        <div class="mb-6 border-b border-[#fdf2f5] pb-4">
            <h2 class="text-lg font-display tracking-wide uppercase text-[#4a001c]">System Login</h2>
            <p class="text-xs text-gray-500 mt-1">Enter your credentials to securely access the registry.</p>
        </div>

        <form wire:submit="login" class="space-y-5">
            <div>
                <label class="block text-xs font-display tracking-widest uppercase text-[#660028] mb-1.5">Email
                    Address</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                        </svg>
                    </div>
                    <input wire:model="email" type="email" placeholder="admin@evsu.edu.ph" required
                        class="w-full border border-[#f9e6ec] rounded-lg pl-10 pr-3 py-3 text-sm bg-[#fdf2f5]/40 transition placeholder-gray-400 text-[#2d0012] focus:border-[#800033] focus:ring focus:ring-[#800033]/20" />
                </div>
                @error('email')
                    <span class="text-[10px] text-red-500 font-semibold mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div x-data="{ showPassword: false }">
                <label
                    class="block text-xs font-display tracking-widest uppercase text-[#660028] mb-1.5">Password</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                        </svg>
                    </div>

                    <input wire:model="password" x-bind:type="showPassword ? 'text' : 'password'" placeholder="••••••••"
                        required
                        class="w-full border border-[#f9e6ec] rounded-lg pl-10 pr-10 py-3 text-sm bg-[#fdf2f5]/40 transition placeholder-gray-400 text-[#2d0012] focus:border-[#800033] focus:ring focus:ring-[#800033]/20" />

                    <button type="button" @click="showPassword = !showPassword"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-[#800033] transition p-1">
                        <svg x-show="showPassword" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24" style="display: none;">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        </svg>
                        <svg x-show="!showPassword" class="w-4 h-4" fill="none" stroke="currentColor"
                            stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                        </svg>
                    </button>
                </div>
                @error('password')
                    <span class="text-[10px] text-red-500 font-semibold mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div class="pt-2">
                <x-utils.button type="submit" size="lg" color="primary" class="w-full"
                    loading-text="Authenticating...">
                    <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none"
                        stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
                    </svg>
                    Sign In Securely
                </x-utils.button>
            </div>
        </form>
    </div>
</div>
