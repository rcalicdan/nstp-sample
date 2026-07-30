<div>
    <x-slot:header>
        <x-partials.header title="EVSU NSTP – CWTS"
            subtitle="National Service Training Program · Civic Welfare Training Service">
            <x-slot:actions>
                @can('create', App\Models\Student::class)
                    <x-utils.button type="button" color="gold" size="md"
                        @click="$dispatch('open-modal', 'create-modal')">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                        <span class="hidden sm:inline">Add New Data</span>
                    </x-utils.button>
                @endcan
            </x-slot:actions>
        </x-partials.header>
    </x-slot:header>

    <div x-data="{
        students: @js($this->students->items()),
        selectedStudent: null,
        activeTab: 'manual',
        get initials() {
            if (!this.selectedStudent) return '';
            return ((this.selectedStudent.first_name?.[0] || '') + (this.selectedStudent.last_name?.[0] || '')).toUpperCase();
        },
        get schoolYearLabel() {
            if (!this.selectedStudent || !this.selectedStudent.school_year) return '—';
            return this.selectedStudent.school_year.start_year + '-' + this.selectedStudent.school_year.end_year;
        }
    }">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-7">
            <x-ui.stat-card title="Total Student/s" :value="$totalStudents" bg="bg-[#2d0012]" textColor="text-[#f9c22e]" />
            <x-ui.stat-card title="Male" :value="$totalMale" bg="bg-[#4a001c]" textColor="text-white" />
            <x-ui.stat-card title="Female" :value="$totalFemale" bg="bg-[#4a001c]" textColor="text-white" />
            <x-ui.stat-card title="Courses" :value="$totalCourses" bg="bg-[#660028]" textColor="text-[#fde68a]" />
        </div>

        <x-ui.filter-bar>
            <div class="relative flex-1 w-full">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-[#800033]" fill="none"
                    stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="11" cy="11" r="8" />
                    <path stroke-linecap="round" d="m21 21-4.35-4.35" />
                </svg>
                <input wire:model.live.debounce.300ms="search" type="text"
                    placeholder="Search by Last Name, First Name or Serial No..."
                    class="w-full pl-10 pr-4 py-2.5 rounded border border-[#f9e6ec] text-sm bg-[#fdf2f5]/50 transition placeholder-gray-400 focus:border-[#800033] focus:outline-none" />
            </div>

            <select wire:model.live="gender"
                class="border border-[#f9e6ec] rounded px-3 py-2.5 text-sm bg-[#fdf2f5]/50 min-w-[130px] transition focus:border-[#800033] focus:outline-none">
                <option value="">All Genders</option>
                @foreach (\App\Enums\Gender::cases() as $genderOption)
                    <option value="{{ $genderOption->value }}">{{ $genderOption->value }}</option>
                @endforeach
            </select>

            <select wire:model.live="schoolYear"
                class="border border-[#f9e6ec] rounded px-3 py-2.5 text-sm bg-[#fdf2f5]/50 min-w-[160px] transition focus:border-[#800033] focus:outline-none">
                <option value="">All School Years</option>
                @foreach ($this->availableSchoolYears as $year)
                    <option value="{{ $year->label }}">{{ $year->label }}</option>
                @endforeach
            </select>

            <button wire:click="clearFilters" type="button"
                class="text-xs text-gray-400 hover:text-[#660028] transition whitespace-nowrap px-1 font-semibold">
                ✕ Clear
            </button>
        </x-ui.filter-bar>

        <x-ui.table-card title="EVSU NSTP-CWTS Masterlist" :countText="'Showing ' . $this->students->count() . ' record(s) on this page'">
            <x-table.main>
                <x-table.thead>
                    <x-table.tr>
                        <x-table.th class="w-32">Serial No.</x-table.th>
                        <x-table.th>Last Name</x-table.th>
                        <x-table.th>First Name</x-table.th>
                        <x-table.th class="hidden sm:table-cell">Middle Name</x-table.th>
                        <x-table.th class="hidden md:table-cell">Course</x-table.th>
                        <x-table.th class="hidden md:table-cell">Gender</x-table.th>
                        <x-table.th class="hidden lg:table-cell">School Year</x-table.th>
                        <x-table.th class="hidden xl:table-cell">Contact No.</x-table.th>
                        <x-table.th align="center" class="w-32">Actions</x-table.th>
                    </x-table.tr>
                </x-table.thead>

                <x-table.tbody>
                    @forelse($this->students as $index => $student)
                        <x-table.tr wire:key="student-{{ $student->id }}">
                            <x-table.td
                                class="text-xs text-[#800033] font-mono font-bold tracking-wide">{{ $student->serial_number }}</x-table.td>
                            <x-table.td
                                class="font-semibold text-[#2d0012] uppercase text-xs tracking-wide">{{ $student->last_name }}</x-table.td>
                            <x-table.td class="text-[#4a001c] text-sm">{{ $student->first_name }}</x-table.td>
                            <x-table.td
                                class="text-gray-400 text-xs hidden sm:table-cell">{{ $student->middle_name ?? '—' }}</x-table.td>
                            <x-table.td class="hidden md:table-cell">
                                <span
                                    class="inline-flex items-center text-[0.68rem] font-bold tracking-wider px-2 py-0.5 rounded bg-[#2d0012]/10 text-[#4a001c] border border-[#f9e6ec] uppercase"
                                    title="{{ $student->course_label }}">
                                    {{ $student->course }}
                                </span>
                            </x-table.td>
                            <x-table.td class="hidden md:table-cell">
                                @if ($student->gender === \App\Enums\Gender::MALE)
                                    <span
                                        class="inline-flex items-center text-[0.68rem] font-bold tracking-wider px-2 py-0.5 rounded bg-blue-50 text-blue-700 border border-blue-200 uppercase">Male</span>
                                @elseif($student->gender === \App\Enums\Gender::FEMALE)
                                    <span
                                        class="inline-flex items-center text-[0.68rem] font-bold tracking-wider px-2 py-0.5 rounded bg-pink-50 text-pink-700 border border-pink-200 uppercase">Female</span>
                                @else
                                    <span
                                        class="inline-flex items-center text-[0.68rem] font-bold tracking-wider px-2 py-0.5 rounded bg-gray-100 text-gray-500 border border-gray-200 uppercase">{{ $student->gender->value }}</span>
                                @endif
                            </x-table.td>
                            <x-table.td
                                class="text-gray-500 text-xs hidden lg:table-cell">{{ $student->schoolYear?->label ?? '—' }}</x-table.td>
                            <x-table.td
                                class="text-gray-400 text-xs hidden xl:table-cell">{{ $student->contact_number ?? '—' }}</x-table.td>
                            <x-table.td align="center">
                                <div class="flex items-center justify-center gap-1.5">
                                    <!-- Instant Client-Side View -->
                                    <x-utils.view-button
                                        @click="selectedStudent = students[{{ $index }}]; $dispatch('open-modal', 'view-modal')" />

                                    <!-- Livewire Protected Edit -->
                                    @can('update', clone $student)
                                        <x-utils.edit-button wire:click="editStudent({{ $student->id }})" />
                                    @endcan

                                    <!-- Livewire Protected Delete -->
                                    @can('delete', clone $student)
                                        <x-utils.delete-button :message="'Are you sure you want to permanently remove ' .
                                            $student->first_name .
                                            ' ' .
                                            $student->last_name .
                                            '?'"
                                            wire:click="deleteStudent({{ $student->id }})" />
                                    @endcan
                                </div>
                            </x-table.td>
                        </x-table.tr>
                    @empty
                        <x-table.empty colspan="9" title="No Trainees Found"
                            description="Adjust your search or filters." />
                    @endforelse
                </x-table.tbody>
            </x-table.main>

            <x-slot:footer>
                <div class="w-full">
                    {{ $this->students->links() }}
                </div>
            </x-slot:footer>
        </x-ui.table-card>

        @include('livewire.cwts-students.partials.view-modal')
        @include('livewire.cwts-students.partials.create-modal')
        @include('livewire.cwts-students.partials.edit-modal')
    </div>
</div>
