<section id="bulletins" class="space-y-6 pt-4">
    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 border-b border-[#f9e6ec] pb-4">
        <div>
            <span class="text-xs font-display tracking-[0.2em] uppercase text-[#800033] font-bold">University Updates</span>
            <h2 class="text-2xl sm:text-3xl font-display font-bold text-[#2d0012] uppercase tracking-wide">
                Official Bulletins & Advisories
            </h2>
        </div>

        {{-- Category Filters --}}
        <div class="flex flex-wrap items-center gap-1.5 text-xs font-display uppercase tracking-wider">
            <button 
                wire:click="setCategory('')" 
                type="button"
                class="px-3 py-1.5 rounded-lg border transition {{ $selectedCategory === '' ? 'bg-[#4a001c] text-white border-[#4a001c]' : 'bg-white text-gray-600 border-[#f9e6ec] hover:bg-[#fdf2f5]' }}"
            >
                All
            </button>
            @foreach ($categories as $cat)
                <button 
                    wire:click="setCategory('{{ $cat->value }}')" 
                    type="button"
                    class="px-3 py-1.5 rounded-lg border transition {{ $selectedCategory === $cat->value ? 'bg-[#4a001c] text-white border-[#4a001c]' : 'bg-white text-gray-600 border-[#f9e6ec] hover:bg-[#fdf2f5]' }}"
                >
                    {{ $cat->value }}
                </button>
            @endforeach
        </div>
    </div>

    {{-- Livewire 4 Island --}}
    @island(name: 'home-bulletins', defer: true)
        @placeholder
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 animate-pulse">
                @for ($i = 0; $i < 3; $i++)
                    <div class="bg-white rounded-2xl border border-gray-200 p-6 h-72 flex flex-col justify-between">
                        <div class="space-y-3">
                            <div class="h-4 bg-gray-200 rounded w-1/4"></div>
                            <div class="h-6 bg-gray-300 rounded w-3/4"></div>
                            <div class="h-4 bg-gray-200 rounded w-full"></div>
                        </div>
                        <div class="h-3 bg-gray-200 rounded w-1/3"></div>
                    </div>
                @endfor
            </div>
        @endplaceholder

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($this->bulletins as $post)
                <article wire:key="home-post-{{ $post->id }}" class="bg-white rounded-2xl border border-[#f9e6ec] shadow-sm hover:shadow-md transition overflow-hidden flex flex-col justify-between group">
                    <div>
                        @if ($post->featured_image)
                            <div class="h-44 overflow-hidden bg-gray-100 border-b border-[#f9e6ec]">
                                <img src="{{ $post->featured_image_url }}" alt="{{ $post->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300" />
                            </div>
                        @endif

                        <div class="p-6 space-y-3">
                            <div class="flex items-center justify-between gap-2">
                                <span class="inline-flex items-center text-[0.68rem] font-bold tracking-wider px-2.5 py-0.5 rounded-full border uppercase {{ $post->category->badgeClasses() }}">
                                    {{ $post->category->value }}
                                </span>
                                @if ($post->is_pinned)
                                    <span class="text-[10px] uppercase font-display tracking-widest text-[#d4a017] font-bold">Pinned</span>
                                @endif
                            </div>

                            <h3 class="font-display font-bold text-lg text-[#2d0012] group-hover:text-[#800033] transition line-clamp-2 leading-snug">
                                <a href="{{ route('news.show', $post->slug) }}">
                                    {{ $post->title }}
                                </a>
                            </h3>

                            @if ($post->excerpt)
                                <p class="text-xs text-gray-500 font-body line-clamp-2 leading-relaxed">
                                    {{ $post->excerpt }}
                                </p>
                            @endif
                        </div>
                    </div>

                    <div class="px-6 py-4 border-t border-[#fdf2f5] bg-[#fdf2f5]/30 flex items-center justify-between text-[11px] text-gray-400 font-body">
                        <time datetime="{{ $post->published_at?->toISOString() }}">
                            {{ $post->published_at ? $post->published_at->format('M d, Y') : $post->created_at->format('M d, Y') }}
                        </time>
                        <a href="{{ route('news.show', $post->slug) }}" class="text-[#800033] font-semibold hover:underline font-display tracking-wider uppercase text-[10px]">
                            Read Bulletin →
                        </a>
                    </div>
                </article>
            @empty
                <div class="col-span-full py-16 text-center bg-white rounded-2xl border border-[#f9e6ec]">
                    <p class="text-sm font-display uppercase tracking-wider text-gray-400">No public bulletins found</p>
                    <p class="text-xs text-gray-400 font-body mt-1">Check back later for university advisories and updates.</p>
                </div>
            @endforelse
        </div>
    @endisland
</section>