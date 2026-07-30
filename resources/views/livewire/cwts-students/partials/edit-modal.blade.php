<x-utils.modal name="edit-modal" maxWidth="3xl">
    <div>
        <div class="bg-[#2d0012] header-pattern px-7 py-5 flex-shrink-0">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-[#f9c22e] text-xs font-display tracking-[0.2em] uppercase mb-1">EVSU NSTP – CWTS</p>
                    <h2 class="text-white font-display text-xl tracking-wide">Edit Data Record</h2>
                </div>
                <button @click="$dispatch('close-modal', 'edit-modal')" type="button"
                    class="text-white/40 hover:text-white text-2xl leading-none transition mt-1">&times;</button>
            </div>
        </div>

        <form wire:submit="update">
            <div class="p-7 space-y-4 max-h-[70vh] overflow-y-auto">
                <div class="bg-gradient-to-br from-[#fdf2f5] to-white border border-[#e8b4c4] rounded-lg p-4">
                    <x-form.label required>Serial No.</x-form.label>
                    <x-form.input wire:model="updateForm.serial_number" class="font-mono tracking-wider uppercase"
                        :error="$errors->first('updateForm.serial_number')" />
                </div>

                <p
                    class="text-[10px] font-display tracking-widest uppercase text-[#800033] border-b border-[#f9e6ec] pb-1">
                    Personal Information</p>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <x-form.label required>Last Name</x-form.label>
                        <x-form.input wire:model="updateForm.last_name" :error="$errors->first('updateForm.last_name')" />
                    </div>
                    <div>
                        <x-form.label required>First Name</x-form.label>
                        <x-form.input wire:model="updateForm.first_name" :error="$errors->first('updateForm.first_name')" />
                    </div>
                    <div>
                        <x-form.label>Middle Name</x-form.label>
                        <x-form.input wire:model="updateForm.middle_name" :error="$errors->first('updateForm.middle_name')" />
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <x-form.label required>Course</x-form.label>
                        <x-form.select wire:model="updateForm.course" :error="$errors->first('updateForm.course')">
                            <option value="">Select Course</option>
                            @foreach (\App\Enums\Course::cases() as $course)
                                <option value="{{ $course->value }}">{{ $course->value }} - {{ $course->label() }}
                                </option>
                            @endforeach
                        </x-form.select>
                    </div>
                    <div>
                        <x-form.label required>Gender</x-form.label>
                        <x-form.select wire:model="updateForm.gender" :error="$errors->first('updateForm.gender')">
                            <option value="">Select Gender</option>
                            @foreach (\App\Enums\Gender::cases() as $gender)
                                <option value="{{ $gender->value }}">{{ $gender->value }}</option>
                            @endforeach
                        </x-form.select>
                    </div>
                    <div>
                        <x-form.label>Birthdate</x-form.label>
                        <x-form.input type="date" wire:model="updateForm.birth_date" :error="$errors->first('updateForm.birth_date')" />
                    </div>
                </div>

                <p
                    class="text-[10px] font-display tracking-widest uppercase text-[#800033] border-b border-[#f9e6ec] pb-1 pt-2">
                    Address & Contact</p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <x-form.label>City Address</x-form.label>
                        <x-form.input wire:model="updateForm.city_address" :error="$errors->first('updateForm.city_address')" />
                    </div>
                    <div>
                        <x-form.label>Province Address</x-form.label>
                        <x-form.input wire:model="updateForm.province_address" :error="$errors->first('updateForm.province_address')" />
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <x-form.label>Contact No.</x-form.label>
                        <x-form.input wire:model="updateForm.contact_number" :error="$errors->first('updateForm.contact_number')" />
                    </div>
                    <div class="sm:col-span-2">
                        <x-form.label>Email Address</x-form.label>
                        <x-form.input type="email" wire:model="updateForm.email" :error="$errors->first('updateForm.email')" />
                    </div>
                </div>

                <div>
                    <x-form.label required>School Year</x-form.label>
                    <x-form.input wire:model="updateForm.school_year" placeholder="e.g. 2024-2025" :error="$errors->first('updateForm.school_year')" />
                </div>
            </div>

            <div class="border-t border-[#f9e6ec] px-7 py-4 flex justify-between items-center bg-[#fdf2f5]">
                <x-utils.button type="button" color="outline" size="sm"
                    @click="$dispatch('close-modal', 'edit-modal')">Cancel</x-utils.button>
                <x-utils.button type="submit" color="primary" size="sm" loadingText="Updating...">Update
                    Record</x-utils.button>
            </div>
        </form>
    </div>
</x-utils.modal>
