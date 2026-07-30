<x-utils.modal name="create-modal" maxWidth="3xl">
    <div x-data="{ activeTab: 'manual' }" @close-modal.window="if($event.detail === 'create-modal') activeTab = 'manual'">
        <div class="bg-[#2d0012] header-pattern px-7 pt-5 pb-0 flex-shrink-0">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <p class="text-[#f9c22e] text-xs font-display tracking-[0.2em] uppercase mb-1">EVSU NSTP – CWTS</p>
                    <h2 class="text-white font-display text-xl tracking-wide">Add New Data</h2>
                </div>
                <button @click="$dispatch('close-modal', 'create-modal')" type="button"
                    class="text-white/40 hover:text-white text-2xl leading-none transition mt-1">&times;</button>
            </div>

            <div class="flex gap-1 border-b border-white/10">
                <button @click="activeTab = 'manual'"
                    :class="activeTab === 'manual' ? 'bg-white/15 text-[#f9c22e] border-b-2 border-[#f9c22e]' :
                        'text-[#e8b4c4]/60 border-b-2 border-transparent hover:text-[#e8b4c4]/90'"
                    type="button"
                    class="px-5 py-2.5 text-xs font-display tracking-widest uppercase flex items-center gap-2 transition">
                    Manual Entry
                </button>
                <button @click="activeTab = 'csv'"
                    :class="activeTab === 'csv' ? 'bg-white/15 text-[#f9c22e] border-b-2 border-[#f9c22e]' :
                        'text-[#e8b4c4]/60 border-b-2 border-transparent hover:text-[#e8b4c4]/90'"
                    type="button"
                    class="px-5 py-2.5 text-xs font-display tracking-widest uppercase flex items-center gap-2 transition">
                    Upload CSV
                </button>
            </div>
        </div>

        <form wire:submit="store">
            <div x-show="activeTab === 'manual'" class="p-7 space-y-4 max-h-[70vh] overflow-y-auto">
                <div class="bg-gradient-to-br from-[#fdf2f5] to-white border border-[#e8b4c4] rounded-lg p-4">
                    <x-form.label required>Serial No.</x-form.label>
                    <x-form.input wire:model="createForm.serial_number" placeholder="C-08-000000-00"
                        class="font-mono tracking-wider uppercase" :error="$errors->first('createForm.serial_number')" />
                </div>

                <p
                    class="text-[10px] font-display tracking-widest uppercase text-[#800033] border-b border-[#f9e6ec] pb-1">
                    Personal Information</p>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <x-form.label required>Last Name</x-form.label>
                        <x-form.input wire:model="createForm.last_name" placeholder="e.g. Santos" :error="$errors->first('createForm.last_name')" />
                    </div>
                    <div>
                        <x-form.label required>First Name</x-form.label>
                        <x-form.input wire:model="createForm.first_name" placeholder="e.g. Maria" :error="$errors->first('createForm.first_name')" />
                    </div>
                    <div>
                        <x-form.label>Middle Name</x-form.label>
                        <x-form.input wire:model="createForm.middle_name" placeholder="e.g. Cruz" :error="$errors->first('createForm.middle_name')" />
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <x-form.label required>Course</x-form.label>
                        <x-form.select wire:model="createForm.course" :error="$errors->first('createForm.course')">
                            <option value="">Select Course</option>
                            @foreach (\App\Enums\Course::cases() as $course)
                                <option value="{{ $course->value }}">{{ $course->value }} -
                                    {{ \Illuminate\Support\Str::limit($course->label(), 42) }}</option>
                            @endforeach
                        </x-form.select>
                    </div>
                    <div>
                        <x-form.label required>Gender</x-form.label>
                        <x-form.select wire:model="createForm.gender" :error="$errors->first('createForm.gender')">
                            <option value="">Select Gender</option>
                            @foreach (\App\Enums\Gender::cases() as $gender)
                                <option value="{{ $gender->value }}">{{ $gender->value }}</option>
                            @endforeach
                        </x-form.select>
                    </div>
                    <div>
                        <x-form.label>Birthdate</x-form.label>
                        <x-form.input wire:model="createForm.birth_date" type="date" :error="$errors->first('createForm.birth_date')" />
                    </div>
                </div>

                <p
                    class="text-[10px] font-display tracking-widest uppercase text-[#800033] border-b border-[#f9e6ec] pb-1 pt-2">
                    Address & Contact</p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <x-form.label>City Address</x-form.label>
                        <x-form.input wire:model="createForm.city_address" placeholder="Tacloban City"
                            :error="$errors->first('createForm.city_address')" />
                    </div>
                    <div>
                        <x-form.label>Province Address</x-form.label>
                        <x-form.input wire:model="createForm.province_address" placeholder="Leyte" :error="$errors->first('createForm.province_address')" />
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <x-form.label>Contact No.</x-form.label>
                        <x-form.input wire:model="createForm.contact_number" placeholder="09171234567"
                            :error="$errors->first('createForm.contact_number')" />
                    </div>
                    <div class="sm:col-span-2">
                        <x-form.label>Email Address</x-form.label>
                        <x-form.input wire:model="createForm.email" type="email" placeholder="student@evsu.edu.ph"
                            :error="$errors->first('createForm.email')" />
                    </div>
                </div>

                <div>
                    <x-form.label required>School Year</x-form.label>
                    <x-form.input wire:model="createForm.school_year" placeholder="e.g. 2024-2025" :error="$errors->first('createForm.school_year')" />
                </div>
            </div>

            <div x-show="activeTab === 'csv'" class="p-7 max-h-[70vh] overflow-y-auto">
                <div
                    class="border-2 border-dashed border-[#e8b4c4] rounded-xl bg-gradient-to-br from-[#fdf2f5] to-[#fff8fa] p-12 text-center">
                    <p class="text-[#2d0012] font-display text-base uppercase mb-1">CSV Uploader Disabled</p>
                    <p class="text-gray-400 text-sm">We are building this functionality in the next phase.</p>
                </div>
            </div>

            <div class="border-t border-[#f9e6ec] px-7 py-4 flex justify-between items-center bg-[#fdf2f5]">
                <x-utils.button type="button" color="outline" size="sm"
                    @click="$dispatch('close-modal', 'create-modal')">Cancel</x-utils.button>
                <div x-show="activeTab === 'manual'">
                    <x-utils.button type="submit" color="primary" size="sm" loadingText="Saving...">Save
                        Record</x-utils.button>
                </div>
            </div>
        </form>
    </div>
</x-utils.modal>
