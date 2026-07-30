<x-utils.modal name="create-modal" maxWidth="3xl">
    <div x-data="{ activeTab: 'manual' }">
        <div class="bg-[#2d0012] header-pattern px-7 pt-5 pb-0 flex-shrink-0">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <p class="text-[#f9c22e] text-xs font-display tracking-[0.2em] uppercase mb-1">EVSU NSTP – CWTS</p>
                    <h2 class="text-white font-display text-xl tracking-wide">Add New Data</h2>
                    <p class="text-[#e8b4c4]/50 text-xs font-body mt-0.5">Fill in all required fields marked with *</p>
                </div>
                <button @click="$dispatch('close-modal', 'create-student')" type="button"
                    class="text-white/40 hover:text-white text-2xl leading-none transition mt-1">&times;</button>
            </div>

            <div class="flex gap-1 border-b border-white/10">
                <button @click="activeTab = 'manual'"
                    :class="activeTab === 'manual' ? 'bg-white/15 text-[#f9c22e] border-b-2 border-[#f9c22e]' :
                        'text-[#e8b4c4]/60 border-b-2 border-transparent hover:text-[#e8b4c4]/90'"
                    type="button"
                    class="px-5 py-2.5 text-xs font-display tracking-widest uppercase flex items-center gap-2 transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z" />
                    </svg>
                    Manual Entry
                </button>
                <button @click="activeTab = 'csv'"
                    :class="activeTab === 'csv' ? 'bg-white/15 text-[#f9c22e] border-b-2 border-[#f9c22e]' :
                        'text-[#e8b4c4]/60 border-b-2 border-transparent hover:text-[#e8b4c4]/90'"
                    type="button"
                    class="px-5 py-2.5 text-xs font-display tracking-widest uppercase flex items-center gap-2 transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                    </svg>
                    Upload CSV
                </button>
            </div>
        </div>

        <div x-show="activeTab === 'manual'" class="p-7 space-y-4 max-h-[70vh] overflow-y-auto">
            <div class="bg-gradient-to-br from-[#fdf2f5] to-white border border-[#e8b4c4] rounded-lg p-4">
                <x-form.label required>Serial No.</x-form.label>
                <x-form.input placeholder="C-08-000000-00" class="font-mono tracking-wider" />
            </div>

            <p class="text-[10px] font-display tracking-widest uppercase text-[#800033] border-b border-[#f9e6ec] pb-1">
                Personal Information</p>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <x-form.label required>Last Name</x-form.label>
                    <x-form.input placeholder="e.g. Santos" />
                </div>
                <div>
                    <x-form.label required>First Name</x-form.label>
                    <x-form.input placeholder="e.g. Maria" />
                </div>
                <div>
                    <x-form.label>Middle Name</x-form.label>
                    <x-form.input placeholder="e.g. Cruz" />
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <x-form.label required>Course</x-form.label>
                    <x-form.input placeholder="e.g. BSIT" />
                </div>
                <div>
                    <x-form.label required>Gender</x-form.label>
                    <x-form.select>
                        <option value="">Select Gender</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                    </x-form.select>
                </div>
                <div>
                    <x-form.label>Birthdate</x-form.label>
                    <x-form.input type="date" />
                </div>
            </div>

            <p
                class="text-[10px] font-display tracking-widest uppercase text-[#800033] border-b border-[#f9e6ec] pb-1 pt-2">
                Address</p>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <x-form.label>City Address</x-form.label>
                    <x-form.input placeholder="Tacloban City" />
                </div>
                <div>
                    <x-form.label>Province Address</x-form.label>
                    <x-form.input placeholder="Leyte" />
                </div>
            </div>

            <p
                class="text-[10px] font-display tracking-widest uppercase text-[#800033] border-b border-[#f9e6ec] pb-1 pt-2">
                Contact & Academic</p>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <x-form.label>Contact No.</x-form.label>
                    <x-form.input placeholder="09171234567" />
                </div>
                <div class="sm:col-span-2">
                    <x-form.label>Email Address</x-form.label>
                    <x-form.input type="email" placeholder="student@evsu.edu.ph" />
                </div>
            </div>

            <div>
                <x-form.label required>School Year</x-form.label>
                <x-form.input placeholder="e.g. 2024-2025" />
            </div>
        </div>

        <div x-show="activeTab === 'csv'" class="p-7 max-h-[70vh] overflow-y-auto space-y-4">
            <div class="flex items-center justify-between bg-[#fdf2f5] border border-[#f9e6ec] rounded-lg px-4 py-3">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 bg-[#2d0012] rounded flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-[#f9c22e]" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m.75 12 3 3m0 0 3-3m-3 3v-6m-1.5-9H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-display tracking-wider text-[#2d0012] uppercase">Download CSV Template
                        </p>
                        <p class="text-[10px] text-[#800033] font-body">Use the official template to ensure correct
                            headers</p>
                    </div>
                </div>
                <x-utils.button type="button" color="gold" size="sm">
                    Template
                </x-utils.button>
            </div>

            <div
                class="border-2 border-dashed border-[#e8b4c4] rounded-xl bg-gradient-to-br from-[#fdf2f5] to-[#fff8fa] p-12 text-center cursor-pointer hover:border-[#800033] transition">
                <div
                    class="w-16 h-16 bg-[#f9e6ec] rounded-full flex items-center justify-center mx-auto mb-4 border border-[#e8b4c4]">
                    <svg class="w-8 h-8 text-[#800033]" fill="none" stroke="currentColor" stroke-width="1.5"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m6.75 12-3-3m0 0-3 3m3-3v6m-1.5-15H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                    </svg>
                </div>
                <p class="text-[#2d0012] font-display text-base uppercase mb-1">Drag & Drop CSV File</p>
                <p class="text-gray-400 text-sm mb-3">or click to browse your files</p>
                <span
                    class="inline-flex items-center font-display text-xs tracking-wider px-3 py-1 rounded bg-[#f9e6ec] text-[#800033] border border-[#e8b4c4] uppercase">.CSV
                    files only</span>
            </div>
        </div>

        <div class="border-t border-[#f9e6ec] px-7 py-4 flex justify-between items-center bg-[#fdf2f5]">
            <p class="text-xs text-gray-400">EVSU NSTP-CWTS Form</p>
            <div class="flex gap-3">
                <x-utils.button type="button" color="outline" size="sm"
                    @click="$dispatch('close-modal', 'create-student')">
                    Cancel
                </x-utils.button>
                <x-utils.button type="button" color="primary" size="sm">
                    Save Record
                </x-utils.button>
            </div>
        </div>
    </div>
</x-utils.modal>
