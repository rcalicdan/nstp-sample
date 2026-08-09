<div>
    <x-slot:header>
        <x-partials.header 
            title="Audit Logs" 
            subtitle="Security, user activity, and data alteration audit trails"
        />
    </x-slot:header>

    <div x-data="{ selectedLog: null }">
        <x-ui.filter-bar>
            <div class="relative flex-1 w-full">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-[#800033]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="11" cy="11" r="8" />
                    <path stroke-linecap="round" d="m21 21-4.35-4.35" />
                </svg>
                <input
                    wire:model.live.debounce.300ms="search"
                    type="text"
                    placeholder="Search by User, IP address, or Model type..."
                    class="w-full pl-10 pr-4 py-2.5 rounded border border-[#f9e6ec] text-sm bg-[#fdf2f5]/50 transition placeholder-gray-400 focus:border-[#800033] focus:outline-none"
                />
            </div>

            <select wire:model.live="eventFilter" class="border border-[#f9e6ec] rounded px-3 py-2.5 text-sm bg-[#fdf2f5]/50 min-w-[130px] transition focus:border-[#800033] focus:outline-none">
                <option value="">All Events</option>
                <option value="created">Created</option>
                <option value="updated">Updated</option>
                <option value="deleted">Deleted</option>
            </select>

            <input 
                type="date" 
                wire:model.live="dateFrom" 
                class="border border-[#f9e6ec] rounded px-3 py-2.5 text-sm bg-[#fdf2f5]/50 transition focus:border-[#800033] focus:outline-none"
                title="From Date"
            />

            <input 
                type="date" 
                wire:model.live="dateTo" 
                class="border border-[#f9e6ec] rounded px-3 py-2.5 text-sm bg-[#fdf2f5]/50 transition focus:border-[#800033] focus:outline-none"
                title="To Date"
            />

            <button wire:click="clearFilters" type="button" class="text-xs text-gray-400 hover:text-[#660028] transition whitespace-nowrap px-1 font-semibold">
                ✕ Clear
            </button>
        </x-ui.filter-bar>

        <x-ui.table-card title="System Activity Logs" :countText="'Showing ' . $this->auditLogs->count() . ' log entry(ies)'">
            <x-table.main>
                <x-table.thead>
                    <x-table.tr>
                        <x-table.th>Event</x-table.th>
                        <x-table.th>Performer</x-table.th>
                        <x-table.th>Model / Resource</x-table.th>
                        <x-table.th class="hidden sm:table-cell">Record ID</x-table.th>
                        <x-table.th class="hidden md:table-cell">IP Address</x-table.th>
                        <x-table.th>Timestamp</x-table.th>
                        <x-table.th align="center" class="w-24">Details</x-table.th>
                    </x-table.tr>
                </x-table.thead>

                <x-table.tbody>
                    @forelse($this->auditLogs as $log)
                        <x-table.tr wire:key="audit-{{ $log->id }}">
                            <x-table.td>
                                @if($log->event === 'created')
                                    <span class="inline-flex items-center text-[0.68rem] font-bold tracking-wider px-2 py-0.5 rounded bg-emerald-50 text-emerald-700 border border-emerald-200 uppercase">Created</span>
                                @elseif($log->event === 'updated')
                                    <span class="inline-flex items-center text-[0.68rem] font-bold tracking-wider px-2 py-0.5 rounded bg-amber-50 text-amber-700 border border-amber-200 uppercase">Updated</span>
                                @elseif($log->event === 'deleted')
                                    <span class="inline-flex items-center text-[0.68rem] font-bold tracking-wider px-2 py-0.5 rounded bg-red-50 text-red-700 border border-red-200 uppercase">Deleted</span>
                                @else
                                    <span class="inline-flex items-center text-[0.68rem] font-bold tracking-wider px-2 py-0.5 rounded bg-blue-50 text-blue-700 border border-blue-200 uppercase">{{ $log->event }}</span>
                                @endif
                            </x-table.td>
                            <x-table.td class="font-semibold text-[#2d0012] text-xs">
                                {{ $log->user?->name ?? 'System / Anonymous' }}
                            </x-table.td>
                            <x-table.td class="text-xs text-[#800033] font-mono font-bold">
                                {{ class_basename($log->auditable_type) }}
                            </x-table.td>
                            <x-table.td class="text-xs font-mono text-gray-500 hidden sm:table-cell">
                                #{{ $log->auditable_id }}
                            </x-table.td>
                            <x-table.td class="text-xs font-mono text-gray-400 hidden md:table-cell">
                                {{ $log->ip_address ?? '—' }}
                            </x-table.td>
                            <x-table.td class="text-xs text-gray-500">
                                {{ $log->created_at->format('M d, Y h:i A') }}
                            </x-table.td>
                            <x-table.td align="center">
                                <div class="flex items-center justify-center">
                                    <x-utils.view-button data-log="{{ json_encode($log) }}"
                                        @click="selectedLog = JSON.parse($el.dataset.log); $dispatch('open-modal', 'view-audit-modal')" />
                                </div>
                            </x-table.td>
                        </x-table.tr>
                    @empty
                        <x-table.empty colspan="7" title="No Audit Logs Found" description="System activity will appear here as actions occur." />
                    @endforelse
                </x-table.tbody>
            </x-table.main>

            <x-slot:footer>
                <div class="w-full">
                    {{ $this->auditLogs->links() }}
                </div>
            </x-slot:footer>
        </x-ui.table-card>

        @include('livewire.audit-logs.partials.view-modal')
    </div>
</div>