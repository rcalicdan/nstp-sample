<div class="space-y-10">
    {{-- Header --}}
    <header class="text-center max-w-2xl mx-auto space-y-3">
        <span class="text-xs font-display tracking-[0.2em] uppercase text-[#800033] font-bold">
            Public Information Desk
        </span>
        <h1 class="text-3xl sm:text-4xl font-display font-bold text-[#2d0012] uppercase tracking-wide">
            Bulletins, Advisories & News
        </h1>
        <p class="text-xs sm:text-sm text-gray-500 font-body leading-relaxed">
            Official announcements, enrollment directives, training schedules, and university notices from the EVSU National Service Training Program.
        </p>
    </header>

    {{-- Filter & Search Bar --}}
    <div class="bg-white rounded-2xl border border-[#f9e6ec] p-4 sm:p-5 shadow-sm space-y-4">
        <div class="flex flex-col sm:flex-row gap-3 items-center justify-between">
            {{-- Search Bar --}}
            <div class="relative flex-1 w-full">
                <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-[#800033]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="11" cy="11" r="8" />
                    <path stroke-linecap="round" d="m21 21-4.35-4.35" />
                </svg>
                <input
                    wire:model.live.debounce.300ms="search"
                    type="text"
                    placeholder="Search bulletins by title, topic, or keyword..."
                    class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-[#f9e6ec] text-sm bg-[#fdf2f5]/40 transition placeholder-gray-400 focus:border-[#800033] focus:outline-none"
                />
            </div>

            @if($search || $category)
                <button wire:click="clearFilters" type="button" class="text-xs font-display uppercase tracking-wider text-gray-400 hover:text-[#800033] transition px-2 font-semibold whitespace-nowrap">
                    ✕ Clear Filters
                </button>
            @endif
        </div>

        {{-- Category Pills --}}
        <div class="flex flex-wrap items-center gap-2 pt-2 border-t border-[#fdf2f5] text-xs font-display uppercase tracking-wider">
            <button 
                type="button" 
                wire:click="setCategory('')"
                class="px-3.5 py-1.5 rounded-full border transition {{ $category === '' ? 'bg-[#4a001c] text-white border-[#4a001c] shadow-xs' : 'bg-[#fdf2f5]/60 text-gray-600 border-[#f9e6ec] hover:bg-[#fdf2f5]' }}"
            >
                All Bulletins
            </button>
            @foreach($categories as $cat)
                <button 
                    type="button" 
                    wire:click="setCategory('{{ $cat->value }}')"
                    class="px-3.5 py-1.5 rounded-full border transition {{ $category === $cat->value ? 'bg-[#4a001c] text-white border-[#4a001c] shadow-xs' : 'bg-[#fdf2f5]/60 text-gray-600 border-[#f9e6ec] hover:bg-[#fdf2f5]' }}"
                >
                    {{ $cat->label() }}
                </button>
            @endforeach
        </div>
    </div>

    {{-- Articles Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($this->posts as $post)
            @php
                $readingTime = max(1, (int) ceil(str_word_count(strip_tags($post->content)) / 200));
            @endphp
            <article wire:key="catalog-post-{{ $post->id }}" class="bg-white rounded-2xl border border-[#f9e6ec] shadow-sm hover:shadow-md transition overflow-hidden flex flex-col justify-between group">
                <div>
                    @if ($post->featured_image)
                        <div class="h-48 overflow-hidden bg-gray-100 border-b border-[#f9e6ec]">
                            <img src="{{ $post->featured_image_url }}" alt="{{ $post->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300" />
                        </div>
                    @else
                        <div class="h-32 bg-[#fdf2f5] border-b border-[#f9e6ec] flex items-center justify-center text-xs font-display tracking-widest uppercase text-[#800033]/60">
                            Institutional Bulletin
                        </div>
                    @endif

                    <div class="p-6 space-y-3">
                        <div class="flex items-center justify-between gap-2">
                            <span class="inline-flex items-center text-[0.68rem] font-bold tracking-wider px-2.5 py-0.5 rounded-full border uppercase {{ $post->category->badgeClasses() }}">
                                {{ $post->category->value }}
                            </span>
                            @if ($post->is_pinned)
                                <span class="text-[10px] uppercase font-display tracking-widest text-[#d4a017] font-bold">
                                    Pinned
                                </span>
                            @endif
                        </div>

                        <h2 class="font-display font-bold text-lg text-[#2d0012] group-hover:text-[#800033] transition line-clamp-2 leading-snug">
                            <a href="{{ route('news.show', $post->slug) }}">
                                {{ $post->title }}
                            </a>
                        </h2>

                        @if ($post->excerpt)
                            <p class="text-xs text-gray-500 font-body line-clamp-3 leading-relaxed">
                                {{ $post->excerpt }}
                            </p>
                        @endif
                    </div>
                </div>

                <div class="px-6 py-4 border-t border-[#fdf2f5] bg-[#fdf2f5]/30 flex items-center justify-between text-[11px] text-gray-400 font-body">
                    <div class="flex items-center gap-2">
                        <time datetime="{{ $post->published_at?->toISOString() }}">
                            {{ $post->published_at ? $post->published_at->format('M d, Y') : $post->created_at->format('M d, Y') }}
                        </time>
                        <span>•</span>
                        <span>{{ $readingTime }}m read</span>
                    </div>

                    <a href="{{ route('news.show', $post->slug) }}" class="text-[#800033] font-semibold hover:underline font-display tracking-wider uppercase text-[10px]">
                        Read →
                    </a>
                </div>
            </article>
        @empty
            <div class="col-span-full py-16 text-center bg-white rounded-2xl border border-[#f9e6ec] p-8">
                <p class="text-base font-display uppercase tracking-wider text-gray-400">No matching bulletins found</p>
                <p class="text-xs text-gray-400 font-body mt-1">Try adjusting your search terms or category filter.</p>
                @if($search || $category)
                    <button wire:click="clearFilters" type="button" class="mt-4 inline-block text-xs font-display uppercase tracking-wider text-[#800033] font-bold underline">
                        Reset Filters
                    </button>
                @endif
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="pt-4">
        {{ $this->posts->links() }}
    </div>
</div>