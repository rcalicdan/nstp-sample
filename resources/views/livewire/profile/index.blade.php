<div>
    <x-slot:header>
        <x-partials.header title="Account Profile" subtitle="Manage your personal information and security settings" />
    </x-slot:header>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg border border-[#f9e6ec] shadow overflow-hidden">
            <div class="bg-[#2d0012] px-6 py-3 border-b border-[#4a001c]">
                <h2 class="text-white font-display text-sm uppercase tracking-wider">Personal Information</h2>
            </div>

            <form wire:submit="updateProfile" class="p-6 space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <x-form.label required>First Name</x-form.label>
                        <x-form.input wire:model="profileForm.first_name" :error="$errors->first('profileForm.first_name')" />
                    </div>
                    <div>
                        <x-form.label required>Last Name</x-form.label>
                        <x-form.input wire:model="profileForm.last_name" :error="$errors->first('profileForm.last_name')" />
                    </div>
                </div>

                <div>
                    <x-form.label required>Email Address</x-form.label>
                    <x-form.input type="email" wire:model="profileForm.email" :error="$errors->first('profileForm.email')" />
                </div>

                <div class="pt-2 flex justify-end">
                    <x-utils.button type="submit" color="primary" size="md" loadingText="Saving...">
                        Save Profile
                    </x-utils.button>
                </div>
            </form>
        </div>

        <div class="bg-white rounded-lg border border-[#f9e6ec] shadow overflow-hidden">
            <div class="bg-[#2d0012] px-6 py-3 border-b border-[#4a001c]">
                <h2 class="text-white font-display text-sm uppercase tracking-wider">Change Password</h2>
            </div>

            <form wire:submit="updatePassword" class="p-6 space-y-4">
                <div>
                    <x-form.label required>Current Password</x-form.label>
                    <x-form.input type="password" wire:model="passwordForm.current_password" :error="$errors->first('passwordForm.current_password')" />
                </div>

                <div>
                    <x-form.label required>New Password</x-form.label>
                    <x-form.input type="password" wire:model="passwordForm.new_password" :error="$errors->first('passwordForm.new_password')" />
                </div>

                <div>
                    <x-form.label required>Confirm New Password</x-form.label>
                    <x-form.input type="password" wire:model="passwordForm.new_password_confirmation" />
                </div>

                <div class="pt-2 flex justify-end">
                    <x-utils.button type="submit" color="gold" size="md" loadingText="Updating...">
                        Update Password
                    </x-utils.button>
                </div>
            </form>
        </div>
    </div>
</div>
