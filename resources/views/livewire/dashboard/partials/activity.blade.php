<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <x-ui.table-card title="Recent CSV Upload Batches" countText="Latest 5 files">
        <x-table.main>
            <x-table.thead>
                <x-table.tr>
                    <x-table.th>File Name</x-table.th>
                    <x-table.th>Branch</x-table.th>
                    <x-table.th>Imported</x-table.th>
                    <x-table.th>Date</x-table.th>
                </x-table.tr>
            </x-table.thead>
            <x-table.tbody>
                @forelse($this->recentUploads as $upload)
                    <x-table.tr>
                        <x-table.td class="font-mono text-xs text-[#800033] font-bold">{{ $upload->file_name }}</x-table.td>
                        <x-table.td class="text-xs">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold border border-[#f9e6ec] bg-[#fdf2f5] text-[#800033]">
                                {{ $upload->nstp_component->value }}
                            </span>
                        </x-table.td>
                        <x-table.td class="text-xs text-emerald-600 font-bold">+{{ $upload->imported_count }}</x-table.td>
                        <x-table.td class="text-xs text-gray-400">{{ $upload->created_at->diffForHumans() }}</x-table.td>
                    </x-table.tr>
                @empty
                    <x-table.empty colspan="4" title="No Uploads Yet" description="Imported CSV files will appear here." />
                @endforelse
            </x-table.tbody>
        </x-table.main>
    </x-ui.table-card>

    <x-ui.table-card title="System Security Audit Feed" countText="Recent actions">
        <x-table.main>
            <x-table.thead>
                <x-table.tr>
                    <x-table.th>Event</x-table.th>
                    <x-table.th>Performer</x-table.th>
                    <x-table.th>Resource</x-table.th>
                    <x-table.th>Time</x-table.th>
                </x-table.tr>
            </x-table.thead>
            <x-table.tbody>
                @forelse($this->recentAuditLogs as $log)
                    <x-table.tr>
                        <x-table.td>
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded uppercase
                                {{ $log->event === 'created' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : '' }}
                                {{ $log->event === 'updated' ? 'bg-amber-50 text-amber-700 border border-amber-200' : '' }}
                                {{ $log->event === 'deleted' ? 'bg-red-50 text-red-700 border border-red-200' : '' }}
                            ">
                                {{ $log->event }}
                            </span>
                        </x-table.td>
                        <x-table.td class="text-xs font-semibold text-[#2d0012]">{{ $log->user?->name ?? 'System' }}</x-table.td>
                        <x-table.td class="text-xs text-[#800033] font-mono">{{ class_basename($log->auditable_type) }}</x-table.td>
                        <x-table.td class="text-xs text-gray-400">{{ $log->created_at->diffForHumans() }}</x-table.td>
                    </x-table.tr>
                @empty
                    <x-table.empty colspan="4" title="No Logs" description="Audit trails will appear here." />
                @endforelse
            </x-table.tbody>
        </x-table.main>
    </x-ui.table-card>
</div>