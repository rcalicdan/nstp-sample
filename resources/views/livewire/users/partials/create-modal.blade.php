<x-utils.modal name="create-user-modal" maxWidth="lg">
    <div>
        <div class="bg-[#2d0012] header-pattern px-7 py-5 flex items-center justify-between">
            <div>
                <p class="text-[#f9c22e] text-xs font-display tracking-[0.2em] uppercase mb-1">EVSU NSTP System</p>
                <h2 class="text-white font-display text-xl tracking-wide">Add New User</h2>
            </div>
            <button @click="$dispatch('close-modal', 'create-user-modal')" type="button"
                class="text-white/40 hover:text-white text-2xl leading-none">&times;</button>
        </div>

        <form wire:submit="store">
            <div class="p-7 space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <x-form.label required>First Name</x-form.label>
                        <x-form.input wire:model="createForm.first_name" placeholder="e.g. Juan" :error="$errors->first('createForm.first_name')" />
                    </div>
                    <div>
                        <x-form.label required>Last Name</x-form.label>
                        <x-form.input wire:model="createForm.last_name" placeholder="e.g. Dela Cruz"
                            :error="$errors->first('createForm.last_name')" />
                    </div>
                </div>

                <div>
                    <x-form.label required>Email Address</x-form.label>
                    <x-form.input type="email" wire:model="createForm.email" placeholder="user@evsu.edu.ph"
                        :error="$errors->first('createForm.email')" />
                </div>

                <div>
                    <x-form.label required>Password</x-form.label>
                    <x-form.input type="password" wire:model="createForm.password" placeholder="••••••••"
                        :error="$errors->first('createForm.password')" />
                </div>

                <div>
                    <x-form.label required>System Role</x-form.label>
                    <x-form.select wire:model="createForm.role" :error="$errors->first('createForm.role')">
                        @foreach ($this->assignableRoleOptions as $roleOption)
                            <option value="{{ $roleOption->value }}">{{ $roleOption->label }}</option>
                        @endforeach
                    </x-form.select>
                </div>
            </div>

            <div class="border-t border-[#f9e6ec] px-7 py-4 flex justify-between items-center bg-[#fdf2f5]">
                <x-utils.button type="button" color="outline" size="sm"
                    @click="$dispatch('close-modal', 'create-user-modal')">Cancel</x-utils.button>
                <x-utils.button type="submit" color="primary" size="sm" loadingText="Creating...">Save
                    User</x-utils.button>
            </div>
        </form>
    </div>
</x-utils.modal>
