<x-utils.modal name="edit-modal" maxWidth="3xl">
    <div>
        <div class="bg-[#2d0012] header-pattern px-7 py-5 flex-shrink-0">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-[#f9c22e] text-xs font-display tracking-[0.2em] uppercase mb-1">EVSU NSTP – CWTS</p>
                    <h2 class="text-white font-display text-xl tracking-wide">Edit Data Record</h2>
                    <p class="text-[#e8b4c4]/50 text-xs font-body mt-0.5">Update student details below</p>
                </div>
                <button @click="$dispatch('close-modal', 'edit-modal')" type="button"
                    class="text-white/40 hover:text-white text-2xl leading-none transition mt-1">&times;</button>
            </div>
        </div>

        <div class="p-7 space-y-4 max-h-[70vh] overflow-y-auto">
            <div class="bg-gradient-to-br from-[#fdf2f5] to-white border border-[#e8b4c4] rounded-lg p-4">
                <x-form.label required>Serial No.</x-form.label>
                <x-form.input x-bind:value="selectedStudent?.serial_number" class="font-mono tracking-wider" />
            </div>

            <p class="text-[10px] font-display tracking-widest uppercase text-[#800033] border-b border-[#f9e6ec] pb-1">
                Personal Information</p>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <x-form.label required>Last Name</x-form.label>
                    <x-form.input x-bind:value="selectedStudent?.last_name" />
                </div>
                <div>
                    <x-form.label required>First Name</x-form.label>
                    <x-form.input x-bind:value="selectedStudent?.first_name" />
                </div>
                <div>
                    <x-form.label>Middle Name</x-form.label>
                    <x-form.input x-bind:value="selectedStudent?.middle_name" />
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <x-form.label required>Course</x-form.label>
                    <x-form.input x-bind:value="selectedStudent?.course" />
                </div>
                <div>
                    <x-form.label required>Gender</x-form.label>
                    <x-form.select x-bind:value="selectedStudent?.gender">
                        <option value="">Select Gender</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                    </x-form.select>
                </div>
                <div>
                    <x-form.label>Birthdate</x-form.label>
                    <x-form.input type="date" x-bind:value="selectedStudent?.birth_date" />
                </div>
            </div>

            <p
                class="text-[10px] font-display tracking-widest uppercase text-[#800033] border-b border-[#f9e6ec] pb-1 pt-2">
                Address</p>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <x-form.label>City Address</x-form.label>
                    <x-form.input x-bind:value="selectedStudent?.city_address" />
                </div>
                <div>
                    <x-form.label>Province Address</x-form.label>
                    <x-form.input x-bind:value="selectedStudent?.province_address" />
                </div>
            </div>

            <p
                class="text-[10px] font-display tracking-widest uppercase text-[#800033] border-b border-[#f9e6ec] pb-1 pt-2">
                Contact & Academic</p>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <x-form.label>Contact No.</x-form.label>
                    <x-form.input x-bind:value="selectedStudent?.contact_number" />
                </div>
                <div class="sm:col-span-2">
                    <x-form.label>Email Address</x-form.label>
                    <x-form.input type="email" x-bind:value="selectedStudent?.email" />
                </div>
            </div>

            <div>
                <x-form.label required>School Year</x-form.label>
                <x-form.input x-bind:value="selectedStudent?.school_year" />
            </div>
        </div>

        <div class="border-t border-[#f9e6ec] px-7 py-4 flex justify-between items-center bg-[#fdf2f5]">
            <p class="text-xs text-gray-400">EVSU NSTP-CWTS Form</p>
            <div class="flex gap-3">
                <x-utils.button type="button" color="outline" size="sm"
                    @click="$dispatch('close-modal', 'edit-modal')">
                    Cancel
                </x-utils.button>
                <x-utils.button type="button" color="primary" size="sm">
                    Update Record
                </x-utils.button>
            </div>
        </div>
    </div>
</x-utils.modal>
