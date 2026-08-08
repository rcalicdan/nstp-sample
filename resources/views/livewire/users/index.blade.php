<div>
    <x-slot:header>
        <x-partials.header 
            title="User Management" 
            subtitle="Manage administrative accounts, coordinators, and system roles"
        >
            <x-slot:actions>
                @can('create', App\Models\User::class)
                    <x-utils.button 
                        type="button" 
                        color="gold" 
                        size="md" 
                        @click="$dispatch('open-modal', 'create-user-modal')"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                        <span class="hidden sm:inline">Add New User</span>
                    </x-utils.button>
                @endcan
            </x-slot:actions>
        </x-partials.header>
    </x-slot:header>

    <div x-data="{ selectedUser: null }">
        <x-ui.filter-bar>
            <div class="relative flex-1 w-full">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-[#800033]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="11" cy="11" r="8" />
                    <path stroke-linecap="round" d="m21 21-4.35-4.35" />
                </svg>
                <input
                    wire:model.live.debounce.300ms="search"
                    type="text"
                    placeholder="Search by Name or Email address..."
                    class="w-full pl-10 pr-4 py-2.5 rounded border border-[#f9e6ec] text-sm bg-[#fdf2f5]/50 transition placeholder-gray-400 focus:border-[#800033] focus:outline-none"
                />
            </div>

            <select wire:model.live="selectedRole" class="border border-[#f9e6ec] rounded px-3 py-2.5 text-sm bg-[#fdf2f5]/50 min-w-[160px] transition focus:border-[#800033] focus:outline-none">
                <option value="">All Roles</option>
                @foreach(\App\Enums\Role::cases() as $roleOption)
                    <option value="{{ $roleOption->value }}">{{ $roleOption->label() }}</option>
                @endforeach
            </select>

            <button wire:click="clearFilters" type="button" class="text-xs text-gray-400 hover:text-[#660028] transition whitespace-nowrap px-1 font-semibold">
                ✕ Clear
            </button>
        </x-ui.filter-bar>

        <x-ui.table-card title="System Users Registry" :countText="'Showing ' . $this->users->count() . ' user(s)'">
            <x-table.main>
                <x-table.thead>
                    <x-table.tr>
                        <x-table.th>Name</x-table.th>
                        <x-table.th>Email Address</x-table.th>
                        <x-table.th>Role</x-table.th>
                        <x-table.th>Status</x-table.th>
                        <x-table.th class="hidden sm:table-cell">Created</x-table.th>
                        <x-table.th align="center" class="w-32">Actions</x-table.th>
                    </x-table.tr>
                </x-table.thead>

                <x-table.tbody>
                    @forelse($this->users as $user)
                        <x-table.tr wire:key="user-{{ $user->id }}">
                            <x-table.td class="font-semibold text-[#2d0012] uppercase text-xs tracking-wide">
                                {{ $user->name }}
                            </x-table.td>
                            <x-table.td class="text-xs text-gray-600 font-mono">{{ $user->email }}</x-table.td>
                            <x-table.td>
                                <span class="inline-flex items-center text-[0.68rem] font-bold tracking-wider px-2 py-0.5 rounded bg-[#2d0012]/10 text-[#4a001c] border border-[#f9e6ec] uppercase">
                                    {{ $user->role->label() }}
                                </span>
                            </x-table.td>
                            <x-table.td>
                                @if($user->is_active)
                                    <span class="inline-flex items-center text-[0.68rem] font-bold tracking-wider px-2 py-0.5 rounded bg-emerald-50 text-emerald-700 border border-emerald-200 uppercase">Active</span>
                                @else
                                    <span class="inline-flex items-center text-[0.68rem] font-bold tracking-wider px-2 py-0.5 rounded bg-red-50 text-red-700 border border-red-200 uppercase">Inactive</span>
                                @endif
                            </x-table.td>
                            <x-table.td class="text-gray-400 text-xs hidden sm:table-cell">{{ $user->created_at->format('M d, Y') }}</x-table.td>
                            <x-table.td align="center">
                                <div class="flex items-center justify-center gap-1.5">
                                    @can('update', $user)
                                        <x-utils.edit-button wire:click="editUser({{ $user->id }})" />
                                    @endcan

                                    @can('toggleActive', $user)
                                        <button 
                                            wire:click="toggleActive({{ $user->id }})" 
                                            type="button" 
                                            title="{{ $user->is_active ? 'Deactivate' : 'Activate' }}"
                                            class="w-8 h-8 rounded flex items-center justify-center transition active:scale-95 {{ $user->is_active ? 'bg-amber-50 hover:bg-amber-100 text-amber-600' : 'bg-emerald-50 hover:bg-emerald-100 text-emerald-600' }}"
                                        >
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5.636 5.636a9 9 0 1 0 12.728 0M12 3v9"/></svg>
                                        </button>
                                    @endcan

                                    @can('delete', $user)
                                        <x-utils.delete-button 
                                            :message="'Are you sure you want to permanently remove user ' . $user->name . '?'"
                                            wire:click="deleteUser({{ $user->id }})" 
                                        />
                                    @endcan
                                </div>
                            </x-table.td>
                        </x-table.tr>
                    @empty
                        <x-table.empty colspan="6" title="No Users Found" description="Adjust your search or filters." />
                    @endforelse
                </x-table.tbody>
            </x-table.main>

            <x-slot:footer>
                <div class="w-full">
                    {{ $this->users->links() }}
                </div>
            </x-slot:footer>
        </x-ui.table-card>

        @include('livewire.users.partials.create-modal')
        @include('livewire.users.partials.edit-modal')
    </div>
</div>