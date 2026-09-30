<x-utils.modal name="csv-history-modal" maxWidth="3xl">
    <div>
        <div class="bg-[#2d0012] header-pattern px-7 py-5 flex items-center justify-between">
            <div>
                <p class="text-[#f9c22e] text-xs font-display tracking-[0.2em] uppercase mb-1">Audit & Management</p>
                <h2 class="text-white font-display text-xl tracking-wide">CSV Import History & Rollback</h2>
            </div>
            <button @click="$dispatch('close-modal', 'csv-history-modal')" type="button" class="text-white/40 hover:text-white text-2xl leading-none">&times;</button>
        </div>

        <div class="p-7 overflow-x-auto">
            <x-table.main>
                <x-table.thead>
                    <x-table.tr>
                        <x-table.th>File Name</x-table.th>
                        <x-table.th>Uploaded By</x-table.th>
                        <x-table.th>Imported</x-table.th>
                        <x-table.th>Updated</x-table.th>
                        <x-table.th>Upload Date</x-table.th>
                        <x-table.th align="center">Action</x-table.th>
                    </x-table.tr>
                </x-table.thead>
                <x-table.tbody>
                    @forelse($this->recentUploads as $upload)
                        <x-table.tr>
                            <x-table.td class="font-mono text-xs text-[#800033] font-bold">{{ $upload->file_name }}</x-table.td>
                            <x-table.td class="text-xs">{{ $upload->user->name }}</x-table.td>
                            <x-table.td class="text-xs text-emerald-600 font-bold">+{{ $upload->imported_count }}</x-table.td>
                            <x-table.td class="text-xs text-amber-600 font-bold">{{ $upload->updated_count }}</x-table.td>
                            <x-table.td class="text-xs text-gray-400">{{ $upload->created_at->format('M d, Y h:i A') }}</x-table.td>
                            <x-table.td align="center">
                                <x-utils.delete-button 
                                    confirmTitle="Rollback CSV Import?"
                                    :message="'Are you sure you want to rollback ' . $upload->file_name . '? This will PERMANENTLY delete all ' . $upload->imported_count . ' imported student records!'"
                                    confirmText="Rollback Import"
                                    wire:click="rollbackCsv({{ $upload->id }})"
                                />
                            </x-table.td>
                        </x-table.tr>
                    @empty
                        <x-table.empty colspan="6" title="No Upload History" description="No CSV files have been imported yet." />
                    @endforelse
                </x-table.tbody>
            </x-table.main>
        </div>
    </div>
</x-utils.modal>