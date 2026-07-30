<x-utils.modal name="view-modal" maxWidth="md">
    <template x-if="selectedStudent">
        <div>
            <div class="bg-[#2d0012] header-pattern pt-7 pb-12 px-7 relative">
                <button @click="$dispatch('close-modal', 'view-modal')" type="button" class="absolute top-4 right-5 text-white/40 hover:text-white text-2xl leading-none">&times;</button>
                <div class="flex items-start gap-4">
                    <div x-text="initials" class="w-16 h-16 rounded flex-shrink-0 bg-[#f9c22e] text-[#2d0012] text-2xl font-display font-bold flex items-center justify-center shadow-lg border-2 border-[#e5a800]"></div>
                    <div class="pt-1">
                        <span class="inline-flex items-center text-[0.68rem] font-bold tracking-wider px-2 py-0.5 rounded bg-[#f9c22e]/20 text-[#fde68a] border border-[#f9c22e]/30 uppercase mb-2 block w-fit">
                            CWTS Student
                        </span>
                        <h2 x-text="`${selectedStudent?.first_name || ''} ${selectedStudent?.middle_name ? selectedStudent.middle_name + ' ' : ''}${selectedStudent?.last_name || ''}`" class="text-white font-display text-xl tracking-wide leading-snug"></h2>
                        <p x-text="selectedStudent?.course || '—'" class="text-[#e8b4c4]/60 text-xs font-body mt-0.5 uppercase tracking-widest"></p>
                        <p x-text="selectedStudent?.serial_number || '—'" class="text-[#f9c22e]/80 text-xl font-mono mt-1 tracking-wider font-semibold"></p>
                    </div>
                </div>
                <div class="flag-stripe w-full mt-5 rounded opacity-50"></div>
            </div>

            <div class="mt-6 mx-5 grid grid-cols-3 bg-white rounded border border-[#f9e6ec] shadow-md overflow-hidden mb-1">
                <div class="px-3 py-3 text-center">
                    <p class="text-[10px] font-display tracking-widest uppercase text-gray-400">Gender</p>
                    <p x-text="typeof selectedStudent?.gender === 'object' ? selectedStudent?.gender?.value : (selectedStudent?.gender || '—')" class="text-sm font-display text-[#4a001c] mt-0.5 tracking-wide"></p>
                </div>
                <div class="px-3 py-3 text-center border-x border-[#fdf2f5]">
                    <p class="text-[10px] font-display tracking-widest uppercase text-gray-400">Birthdate</p>
                    <p x-text="selectedStudent?.birth_date ? String(selectedStudent.birth_date).substring(0, 10) : '—'" class="text-sm font-display text-[#4a001c] mt-0.5 tracking-wide"></p>
                </div>
                <div class="px-3 py-3 text-center">
                    <p class="text-[10px] font-display tracking-widest uppercase text-gray-400">SY</p>
                    <p x-text="selectedStudent?.school_year ? `${selectedStudent.school_year.start_year}-${selectedStudent.school_year.end_year}` : '—'" class="text-sm font-display text-[#4a001c] mt-0.5 tracking-wide"></p>
                </div>
            </div>

            <div class="px-6 py-5 space-y-3.5">
                <div class="flex items-start gap-3">
                    <div class="w-7 h-7 rounded bg-[#fdf2f5] border border-[#f9e6ec] flex items-center justify-center flex-shrink-0 mt-0.5">
                        <svg class="w-3.5 h-3.5 text-[#800033]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/></svg>
                    </div>
                    <div>
                        <p class="text-[10px] font-display uppercase tracking-widest text-gray-400">Address</p>
                        <p x-text="`${selectedStudent?.city_address || ''}${selectedStudent?.city_address && selectedStudent?.province_address ? ', ' : ''}${selectedStudent?.province_address || ''}` || '—'" class="text-sm text-[#4a001c] font-body mt-0.5"></p>
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    <div class="w-7 h-7 rounded bg-[#fdf2f5] border border-[#f9e6ec] flex items-center justify-center flex-shrink-0 mt-0.5">
                        <svg class="w-3.5 h-3.5 text-[#800033]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z"/></svg>
                    </div>
                    <div>
                        <p class="text-[10px] font-display uppercase tracking-widest text-gray-400">Contact No.</p>
                        <p x-text="selectedStudent?.contact_number || '—'" class="text-sm text-[#4a001c] font-body mt-0.5"></p>
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    <div class="w-7 h-7 rounded bg-[#fdf2f5] border border-[#f9e6ec] flex items-center justify-center flex-shrink-0 mt-0.5">
                        <svg class="w-3.5 h-3.5 text-[#800033]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"/></svg>
                    </div>
                    <div>
                        <p class="text-[10px] font-display uppercase tracking-widest text-gray-400">Email Address</p>
                        <p x-text="selectedStudent?.email || '—'" class="text-sm text-[#4a001c] font-body mt-0.5 break-all"></p>
                    </div>
                </div>
            </div>

            <div class="border-t border-[#fdf2f5] px-6 py-4 flex justify-between items-center bg-[#fdf2f5]/60">
                <button type="button" @click="$dispatch('close-modal', 'view-modal')" class="text-xs text-gray-400 hover:text-gray-600 transition uppercase tracking-widest font-display">Close</button>
                @can('update', App\Models\Student::class)
                <button @click="$wire.editStudent(selectedStudent.id); $dispatch('close-modal', 'view-modal')" type="button" class="flex items-center gap-2 text-sm font-display tracking-wide uppercase text-[#660028] hover:text-[#2d0012] transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z"/></svg>
                    Edit Record
                </button>
                @endcan
            </div>
        </div>
    </template>
</x-utils.modal>