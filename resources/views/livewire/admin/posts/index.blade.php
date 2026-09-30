<div>
    <x-slot:header>
        <x-partials.header 
            title="News & Announcements" 
            subtitle="Publish bulletins, advisories, news articles, and institutional updates"
        >
            <x-slot:actions>
                @can('create', App\Models\Post::class)
                    <a href="{{ route('admin.posts.create') }}" wire:navigate>
                        <x-utils.button type="button" color="gold" size="md">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                            </svg>
                            <span class="hidden sm:inline">Write Article</span>
                        </x-utils.button>
                    </a>
                @endcan
            </x-slot:actions>
        </x-partials.header>
    </x-slot:header>

    <div>
        {{-- Filter Bar --}}
        <x-ui.filter-bar>
            <div class="relative flex-1 w-full">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-[#800033]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="11" cy="11" r="8" />
                    <path stroke-linecap="round" d="m21 21-4.35-4.35" />
                </svg>
                <input
                    wire:model.live.debounce.300ms="search"
                    type="text"
                    placeholder="Search by article headline, content, or author..."
                    class="w-full pl-10 pr-4 py-2.5 rounded border border-[#f9e6ec] text-sm bg-[#fdf2f5]/50 transition placeholder-gray-400 focus:border-[#800033] focus:outline-none"
                />
            </div>

            <select wire:model.live="category" class="border border-[#f9e6ec] rounded px-3 py-2.5 text-sm bg-[#fdf2f5]/50 min-w-[150px] transition focus:border-[#800033] focus:outline-none">
                <option value="">All Categories</option>
                @foreach(\App\Enums\PostCategory::cases() as $cat)
                    <option value="{{ $cat->value }}">{{ $cat->label() }}</option>
                @endforeach
            </select>

            <select wire:model.live="status" class="border border-[#f9e6ec] rounded px-3 py-2.5 text-sm bg-[#fdf2f5]/50 min-w-[130px] transition focus:border-[#800033] focus:outline-none">
                <option value="">All Statuses</option>
                <option value="published">Published</option>
                <option value="draft">Draft</option>
            </select>

            <button wire:click="clearFilters" type="button" class="text-xs text-gray-400 hover:text-[#660028] transition whitespace-nowrap px-1 font-semibold">
                ✕ Clear
            </button>
        </x-ui.filter-bar>

        {{-- Table Card --}}
        <x-ui.table-card title="Official Bulletins Registry" :countText="'Showing ' . $this->posts->count() . ' article(s)'">
            <x-table.main>
                <x-table.thead>
                    <x-table.tr>
                        <x-table.th class="w-12 text-center">Pin</x-table.th>
                        <x-table.th>Article Headline</x-table.th>
                        <x-table.th>Category</x-table.th>
                        <x-table.th>Author</x-table.th>
                        <x-table.th>Status</x-table.th>
                        <x-table.th class="hidden sm:table-cell">Publish Date</x-table.th>
                        <x-table.th align="center" class="w-32">Actions</x-table.th>
                    </x-table.tr>
                </x-table.thead>

                <x-table.tbody>
                    @forelse($this->posts as $post)
                        <x-table.tr wire:key="post-{{ $post->id }}">
                            {{-- Pin Toggle --}}
                            <x-table.td align="center">
                                @can('update', $post)
                                    <button 
                                        wire:click="togglePin({{ $post->id }})" 
                                        type="button" 
                                        title="{{ $post->is_pinned ? 'Unpin article' : 'Pin article to top' }}"
                                        class="p-1 rounded transition {{ $post->is_pinned ? 'text-[#f9c22e] hover:text-[#d4a017]' : 'text-gray-300 hover:text-gray-500' }}"
                                    >
                                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                            <path d="M16 12V4h1V2H7v2h1v8l-2 2v2h5.2v6h1.6v-6H18v-2l-2-2z"/>
                                        </svg>
                                    </button>
                                @else
                                    @if($post->is_pinned)
                                        <span class="text-[#f9c22e]">📌</span>
                                    @endif
                                @endcan
                            </x-table.td>

                            {{-- Title & Slug --}}
                            <x-table.td>
                                <div class="flex items-center gap-3">
                                    @if($post->featured_image)
                                        <img src="{{ $post->featured_image_url }}" alt="Cover" class="w-10 h-10 rounded object-cover border border-[#f9e6ec] flex-shrink-0" />
                                    @else
                                        <div class="w-10 h-10 rounded bg-[#fdf2f5] border border-[#f9e6ec] flex items-center justify-center text-[#800033] flex-shrink-0">
                                            📰
                                        </div>
                                    @endif
                                    <div class="min-w-0">
                                        <a href="{{ route('admin.posts.edit', $post) }}" wire:navigate class="font-semibold text-[#2d0012] hover:text-[#800033] transition line-clamp-1 block text-sm">
                                            {{ $post->title }}
                                        </a>
                                        <span class="text-[11px] font-mono text-gray-400 truncate block">/news/{{ $post->slug }}</span>
                                    </div>
                                </div>
                            </x-table.td>

                            {{-- Category --}}
                            <x-table.td>
                                <span class="inline-flex items-center text-[0.68rem] font-bold tracking-wider px-2 py-0.5 rounded border uppercase
                                    {{ $post->category === \App\Enums\PostCategory::NEWS ? 'bg-blue-50 text-blue-700 border-blue-200' : '' }}
                                    {{ $post->category === \App\Enums\PostCategory::ANNOUNCEMENT ? 'bg-amber-50 text-amber-700 border-amber-200' : '' }}
                                    {{ $post->category === \App\Enums\PostCategory::EVENT ? 'bg-purple-50 text-purple-700 border-purple-200' : '' }}
                                    {{ $post->category === \App\Enums\PostCategory::ADVISORY ? 'bg-red-50 text-red-700 border-red-200' : '' }}
                                ">
                                    {{ $post->category->value }}
                                </span>
                            </x-table.td>

                            {{-- Author --}}
                            <x-table.td class="text-xs text-gray-600 font-semibold">
                                {{ $post->user?->name ?? 'System' }}
                            </x-table.td>

                            {{-- Status Toggle --}}
                            <x-table.td>
                                @can('publish', $post)
                                    <button 
                                        wire:click="togglePublish({{ $post->id }})" 
                                        type="button" 
                                        class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold tracking-wide uppercase transition border
                                            {{ $post->is_published 
                                                ? 'bg-emerald-50 text-emerald-700 border-emerald-200 hover:bg-emerald-100' 
                                                : 'bg-gray-100 text-gray-500 border-gray-200 hover:bg-gray-200' }}"
                                    >
                                        <span class="w-1.5 h-1.5 rounded-full {{ $post->is_published ? 'bg-emerald-500' : 'bg-gray-400' }}"></span>
                                        {{ $post->is_published ? 'Live' : 'Draft' }}
                                    </button>
                                @else
                                    <span class="text-xs {{ $post->is_published ? 'text-emerald-600 font-bold' : 'text-gray-400' }}">
                                        {{ $post->is_published ? 'Live' : 'Draft' }}
                                    </span>
                                @endcan
                            </x-table.td>

                            {{-- Published Date --}}
                            <x-table.td class="text-xs text-gray-400 hidden sm:table-cell">
                                {{ $post->published_at ? $post->published_at->format('M d, Y') : '—' }}
                            </x-table.td>

                            {{-- Actions --}}
                            <x-table.td align="center">
                                <div class="flex items-center justify-center gap-1.5">
                                    @can('update', $post)
                                        <a href="{{ route('admin.posts.edit', $post) }}" wire:navigate>
                                            <x-utils.edit-button title="Edit Article" />
                                        </a>
                                    @endcan

                                    @can('delete', $post)
                                        <x-utils.delete-button 
                                            :message="'Are you sure you want to permanently delete \'' . $post->title . '\' and its uploaded images?'"
                                            wire:click="deletePost({{ $post->id }})" 
                                        />
                                    @endcan
                                </div>
                            </x-table.td>
                        </x-table.tr>
                    @empty
                        <x-table.empty colspan="7" title="No Articles Published Yet" description="Click 'Write Article' to create your first bulletin." />
                    @endforelse
                </x-table.tbody>
            </x-table.main>

            <x-slot:footer>
                <div class="w-full">
                    {{ $this->posts->links() }}
                </div>
            </x-slot:footer>
        </x-ui.table-card>
    </div>
</div>