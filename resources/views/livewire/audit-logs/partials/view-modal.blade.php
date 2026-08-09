<x-utils.modal name="view-audit-modal" maxWidth="2xl">
    <template x-if="selectedLog">
        <div>
            <div class="bg-[#2d0012] header-pattern px-7 py-5 flex items-center justify-between">
                <div>
                    <p class="text-[#f9c22e] text-xs font-display tracking-[0.2em] uppercase mb-1">Audit Log Inspection</p>
                    <h2 class="text-white font-display text-xl tracking-wide">
                        <span x-text="selectedLog.event.toUpperCase()"></span> on <span x-text="selectedLog.auditable_type.split('\\').pop()"></span> #<span x-text="selectedLog.auditable_id"></span>
                    </h2>
                </div>
                <button @click="$dispatch('close-modal', 'view-audit-modal')" type="button" class="text-white/40 hover:text-white text-2xl leading-none">&times;</button>
            </div>

            <div class="p-7 space-y-4 max-h-[70vh] overflow-y-auto">
                <div class="grid grid-cols-2 gap-4 bg-[#fdf2f5] border border-[#f9e6ec] rounded-lg p-4 text-xs">
                    <div>
                        <p class="font-display uppercase tracking-widest text-[#800033]">Performer</p>
                        <p x-text="selectedLog.user ? selectedLog.user.first_name + ' ' + selectedLog.user.last_name : 'System / Anonymous'" class="font-semibold text-[#2d0012] mt-0.5"></p>
                    </div>
                    <div>
                        <p class="font-display uppercase tracking-widest text-[#800033]">Timestamp</p>
                        <p x-text="selectedLog.created_at" class="font-mono text-[#2d0012] mt-0.5"></p>
                    </div>
                    <div>
                        <p class="font-display uppercase tracking-widest text-[#800033]">IP Address</p>
                        <p x-text="selectedLog.ip_address || '—'" class="font-mono text-[#2d0012] mt-0.5"></p>
                    </div>
                    <div>
                        <p class="font-display uppercase tracking-widest text-[#800033]">Target URL</p>
                        <p x-text="selectedLog.url || '—'" class="font-mono text-[#2d0012] mt-0.5 truncate"></p>
                    </div>
                </div>

                <div x-show="selectedLog.old_values || selectedLog.new_values" class="space-y-3">
                    <p class="text-[10px] font-display tracking-widest uppercase text-[#800033] border-b border-[#f9e6ec] pb-1">Field Changes Diff</p>
                    
                    <div class="border border-[#f9e6ec] rounded-lg overflow-hidden">
                        <table class="w-full text-xs">
                            <thead class="bg-[#2d0012] text-[#fde68a] font-display uppercase tracking-wider">
                                <tr>
                                    <th class="px-3 py-2 text-left">Attribute</th>
                                    <th class="px-3 py-2 text-left">Old Value</th>
                                    <th class="px-3 py-2 text-left">New Value</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[#fdf2f5]">
                                <template x-for="(newValue, key) in (selectedLog.new_values || selectedLog.old_values)" :key="key">
                                    <tr>
                                        <td x-text="key" class="px-3 py-2 font-mono font-bold text-[#800033]"></td>
                                        <td x-text="selectedLog.old_values ? selectedLog.old_values[key] ?? '—' : '—'" class="px-3 py-2 text-red-600 bg-red-50/30"></td>
                                        <td x-text="selectedLog.new_values ? selectedLog.new_values[key] ?? '—' : '—'" class="px-3 py-2 text-emerald-600 bg-emerald-50/30"></td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="border-t border-[#f9e6ec] px-7 py-4 flex justify-end bg-[#fdf2f5]">
                <x-utils.button type="button" color="outline" size="sm" @click="$dispatch('close-modal', 'view-audit-modal')">Close</x-utils.button>
            </div>
        </div>
    </template>
</x-utils.modal>