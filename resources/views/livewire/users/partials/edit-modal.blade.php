<x-utils.modal name="edit-user-modal" maxWidth="lg">
    <div>
        <div class="bg-[#2d0012] header-pattern px-7 py-5 flex items-center justify-between">
            <div>
                <p class="text-[#f9c22e] text-xs font-display tracking-[0.2em] uppercase mb-1">EVSU NSTP System</p>
                <h2 class="text-white font-display text-xl tracking-wide">Edit User Account</h2>
            </div>
            <button @click="$dispatch('close-modal', 'edit-user-modal')" type="button"
                class="text-white/40 hover:text-white text-2xl leading-none">&times;</button>
        </div>

        <form wire:submit="update">
            <div class="p-7 space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <x-form.label required>First Name</x-form.label>
                        <x-form.input wire:model="updateForm.first_name" :error="$errors->first('updateForm.first_name')" />
                    </div>
                    <div>
                        <x-form.label required>Last Name</x-form.label>
                        <x-form.input wire:model="updateForm.last_name" :error="$errors->first('updateForm.last_name')" />
                    </div>
                </div>

                <div>
                    <x-form.label required>Email Address</x-form.label>
                    <x-form.input type="email" wire:model="updateForm.email" :error="$errors->first('updateForm.email')" />
                </div>

                <div>
                    <x-form.label>New Password (leave blank to keep current)</x-form.label>
                    <x-form.input type="password" wire:model="updateForm.password" placeholder="••••••••"
                        :error="$errors->first('updateForm.password')" />
                </div>

                <div>
                    <x-form.label required>System Role</x-form.label>
                    <x-form.select wire:model="updateForm.role" :error="$errors->first('updateForm.role')">
                        @foreach ($this->assignableRoleOptions as $roleOption)
                            <option value="{{ $roleOption->value }}">{{ $roleOption->label }}</option>
                        @endforeach
                    </x-form.select>
                </div>
            </div>

            <div class="border-t border-[#f9e6ec] px-7 py-4 flex justify-between items-center bg-[#fdf2f5]">
                <x-utils.button type="button" color="outline" size="sm"
                    @click="$dispatch('close-modal', 'edit-user-modal')">Cancel</x-utils.button>
                <x-utils.button type="submit" color="primary" size="sm" loadingText="Updating...">Update
                    Account</x-utils.button>
            </div>
        </form>
    </div>
</x-utils.modal>
