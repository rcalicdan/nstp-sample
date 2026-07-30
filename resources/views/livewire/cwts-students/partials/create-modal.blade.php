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
                    Personal Information
                </p>

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
                        <x-form.searchable-select wire:model="createForm.course" :options="$this->courseOptions" valueField="value"
                            labelField="label" placeholder="Search Course..." :error="$errors->first('createForm.course')" />
                    </div>
                    <div>
                        <x-form.label required>Gender</x-form.label>
                        <x-form.searchable-select wire:model="createForm.gender" :options="$this->genderOptions" valueField="value"
                            labelField="label" placeholder="Select Gender..." :searchable="false" :error="$errors->first('createForm.gender')" />
                    </div>
                    <div>
                        <x-form.label>Birthdate</x-form.label>
                        <x-form.input wire:model="createForm.birth_date" type="date" :error="$errors->first('createForm.birth_date')" />
                    </div>
                </div>

                <p
                    class="text-[10px] font-display tracking-widest uppercase text-[#800033] border-b border-[#f9e6ec] pb-1 pt-2">
                    Address & Contact
                </p>
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

            <div x-show="activeTab === 'csv'" class="p-7 space-y-5 max-h-[70vh] overflow-y-auto">
                <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 flex items-center justify-between">
                    <div>
                        <label class="block text-xs font-display tracking-wider uppercase text-amber-900 font-bold">
                            Duplicate Serial Strategy
                        </label>
                        <p class="text-[11px] text-amber-700">What should happen if a student's Serial No. already
                            exists?</p>
                    </div>
                    <div class="flex gap-4 text-xs font-display uppercase tracking-wider">
                        <label class="flex items-center gap-1.5 cursor-pointer">
                            <input type="radio" wire:model="duplicateAction" value="skip"
                                class="text-[#800033] focus:ring-[#800033]">
                            <span>Skip</span>
                        </label>
                        <label class="flex items-center gap-1.5 cursor-pointer">
                            <input type="radio" wire:model="duplicateAction" value="update"
                                class="text-[#800033] focus:ring-[#800033]">
                            <span>Update / Overwrite</span>
                        </label>
                    </div>
                </div>

                <div x-data="{ isDragging: false }" @dragover.prevent="isDragging = true"
                    @dragleave.prevent="isDragging = false"
                    @drop.prevent="isDragging = false; $refs.fileInput.files = $event.dataTransfer.files; $refs.fileInput.dispatchEvent(new Event('change'))"
                    :class="isDragging ? 'border-[#800033] bg-[#fdf2f5]' : 'border-[#e8b4c4] bg-[#fdf2f5]/30'"
                    class="border-2 border-dashed rounded-xl p-8 text-center transition cursor-pointer"
                    @click="$refs.fileInput.click()">

                    <input type="file" wire:model="csvFile" x-ref="fileInput" accept=".csv" class="hidden">

                    <div
                        class="w-12 h-12 bg-[#2d0012]/10 rounded-full flex items-center justify-center mx-auto mb-3 text-[#2d0012]">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                        </svg>
                    </div>

                    <p class="text-[#2d0012] font-display text-sm uppercase tracking-wide">
                        <span wire:loading.remove wire:target="csvFile">Click to upload or drag & drop CSV</span>
                        <span wire:loading wire:target="csvFile" class="text-[#800033] font-bold">Uploading
                            file...</span>
                    </p>
                    <p class="text-xs text-gray-400 mt-1">Accepts standard .CSV files up to 10MB</p>

                    @if ($csvFile)
                        <div
                            class="mt-3 inline-flex items-center gap-2 bg-[#800033] text-white text-xs px-3 py-1.5 rounded-full font-mono">
                            <span>📄 {{ $csvFile->getClientOriginalName() }}</span>
                        </div>
                    @endif
                </div>

                @error('csvFile')
                    <span class="text-xs text-red-500 font-semibold block">{{ $message }}</span>
                @enderror
            </div>


            <div class="border-t border-[#f9e6ec] px-7 py-4 flex justify-between items-center bg-[#fdf2f5]">
                <x-utils.button type="button" color="outline" size="sm"
                    @click="$dispatch('close-modal', 'create-modal')">
                    Cancel
                </x-utils.button>

                <div x-show="activeTab === 'manual'">
                    <x-utils.button type="submit" color="primary" size="sm" loadingText="Saving...">
                        Save Record
                    </x-utils.button>
                </div>

                <div x-show="activeTab === 'csv'">
                    <x-utils.button type="button" wire:click="importCsv" color="gold" size="sm"
                        loadingText="Importing CSV..." :disabled="!$csvFile">
                        Import CSV Records
                    </x-utils.button>
                </div>
            </div>
        </form>
    </div>
</x-utils.modal>
