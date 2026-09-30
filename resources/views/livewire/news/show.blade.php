@php
    $readingTime = max(1, (int) ceil(str_word_count(strip_tags($post->content)) / 200));
@endphp

<div>
    <nav class="flex items-center gap-2 text-xs font-display uppercase tracking-widest text-gray-400 mb-6">
        <a href="/" class="hover:text-[#800033] transition flex items-center gap-1">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/></svg>
            Home
        </a>
        <svg class="w-3 h-3 text-gray-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/></svg>
        <a href="/news" class="hover:text-[#800033] transition">Bulletins</a>
        <svg class="w-3 h-3 text-gray-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/></svg>
        <span class="text-[#800033] font-semibold">{{ $post->category->value }}</span>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        <article class="lg:col-span-2 bg-white rounded-2xl border border-[#f9e6ec] shadow-sm p-6 sm:p-12 overflow-hidden">
            @if(! $post->is_published || ($post->published_at && $post->published_at->isFuture()))
                <div class="bg-amber-50 border border-amber-200 text-amber-900 rounded-xl p-4 text-xs mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-3 shadow-xs">
                    <div class="flex items-center gap-2.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-amber-500 animate-pulse"></span>
                        <div>
                            <span class="font-bold uppercase tracking-wider">Internal Administrative Preview:</span>
                            <p class="text-amber-800 text-[11px] mt-0.5">
                                {{ ! $post->is_published ? 'This article is currently an unreleased Draft.' : 'Scheduled to go live on ' . $post->published_at->format('M d, Y h:i A') . '.' }}
                            </p>
                        </div>
                    </div>
                    <a href="{{ route('admin.posts.edit', $post) }}" class="inline-flex items-center gap-1 font-semibold text-xs text-[#800033] hover:underline whitespace-nowrap">
                        Edit in Studio →
                    </a>
                </div>
            @endif

            <header class="mb-8">
                <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center text-[0.7rem] font-bold tracking-wider px-3 py-1 rounded-full border uppercase {{ $post->category->badgeClasses() }}">
                            {{ $post->category->value }}
                        </span>
                        @if($post->is_pinned)
                            <span class="inline-flex items-center text-[0.7rem] font-bold tracking-wider px-2.5 py-1 rounded-full bg-amber-100 text-amber-800 border border-amber-200 uppercase">
                                Pinned Bulletin
                            </span>
                        @endif
                    </div>

                    <span class="text-xs text-gray-400 font-mono flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                        {{ $readingTime }} min read
                    </span>
                </div>

                <h1 class="text-2xl sm:text-4xl font-display font-bold text-[#2d0012] leading-tight mb-4 tracking-wide">
                    {{ $post->title }}
                </h1>

                <div class="flex flex-wrap items-center justify-between gap-4 py-3 border-y border-[#fdf2f5] text-xs text-gray-500 font-body">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-[#f9c22e] text-[#2d0012] font-display font-bold flex items-center justify-center text-xs">
                            {{ $post->user ? $post->user->initials : 'EV' }}
                        </div>
                        <div>
                            <p class="font-semibold text-[#2d0012] leading-none">{{ $post->user?->name ?? 'EVSU NSTP Administration' }}</p>
                            <span class="text-[11px] text-gray-400">Institutional Communications</span>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 text-[11px]">
                        <time datetime="{{ $post->published_at?->toISOString() }}" class="font-mono">
                            {{ $post->published_at ? $post->published_at->format('F d, Y') : $post->created_at->format('F d, Y') }}
                        </time>

                        <div x-data="{ copied: false }" class="relative inline-block">
                            <button 
                                type="button"
                                @click="
                                    navigator.clipboard.writeText(window.location.href);
                                    copied = true;
                                    setTimeout(() => copied = false, 2500);
                                "
                                class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md border border-[#f9e6ec] bg-[#fdf2f5]/40 hover:bg-[#fdf2f5] text-[#800033] font-semibold transition"
                                title="Copy link to clipboard"
                            >
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 0 1 1.242 7.244l-4.5 4.5a4.5 4.5 0 0 1-6.364-6.364l1.757-1.757m13.35-.622 1.757-1.757a4.5 4.5 0 0 0-6.364-6.364l-4.5 4.5a4.5 4.5 0 0 0 1.242 7.244"/></svg>
                                <span x-text="copied ? 'Copied!' : 'Share'"></span>
                            </button>
                        </div>
                    </div>
                </div>
            </header>

            @if ($post->featured_image)
                <div class="mb-8 rounded-2xl overflow-hidden border border-[#f9e6ec] shadow-sm">
                    <img src="{{ $post->featured_image_url }}" alt="{{ $post->title }}" class="w-full max-h-[460px] object-cover" />
                </div>
            @endif

            @if ($post->excerpt)
                <div class="bg-[#fdf2f5] border-l-4 border-[#800033] p-5 rounded-r-xl text-sm italic text-[#4a001c] font-body mb-8 leading-relaxed">
                    {{ $post->excerpt }}
                </div>
            @endif

            <div class="prose max-w-none text-[#2d0012] leading-relaxed text-sm sm:text-base font-body">
                {!! $post->content !!}
            </div>

            <div class="mt-12 pt-8 border-t border-[#f9e6ec] flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 bg-[#fdf2f5]/40 rounded-xl p-5">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-10 h-10 object-contain drop-shadow-xs" />
                    <div>
                        <p class="font-display uppercase tracking-wider text-xs font-bold text-[#2d0012]">EVSU National Service Training Program</p>
                        <p class="text-[11px] text-gray-500 font-body">Official institutional advisory released under RA 9163</p>
                    </div>
                </div>

                <a href="/news" class="text-xs font-display tracking-widest uppercase text-[#800033] hover:text-[#4a001c] font-bold transition">
                    ← All Bulletins
                </a>
            </div>
        </article>

        <aside class="space-y-6">
            <div class="bg-white rounded-2xl border border-[#f9e6ec] shadow-sm p-6">
                <div class="flex items-center justify-between border-b border-[#fdf2f5] pb-3 mb-4">
                    <h3 class="font-display uppercase tracking-wider text-xs text-[#800033] font-bold">
                        Recent Bulletins
                    </h3>
                    <a href="/news" class="text-[10px] uppercase font-display tracking-widest text-gray-400 hover:text-[#800033] font-semibold transition">
                        View All
                    </a>
                </div>

                <div class="space-y-4">
                    @forelse($this->recentBulletins as $recent)
                        <div class="group border-b border-gray-50 last:border-0 pb-3 last:pb-0">
                            <span class="inline-block text-[9px] font-bold uppercase tracking-wider px-2 py-0.5 rounded border {{ $recent->category->badgeClasses() }} mb-1">
                                {{ $recent->category->value }}
                            </span>
                            <a href="{{ route('news.show', $recent->slug) }}" class="block text-xs font-semibold text-[#2d0012] group-hover:text-[#800033] transition line-clamp-2 leading-snug">
                                {{ $recent->title }}
                            </a>
                            <span class="text-[10px] text-gray-400 mt-1 block font-mono">
                                {{ $recent->published_at ? $recent->published_at->format('M d, Y') : $recent->created_at->format('M d, Y') }}
                            </span>
                        </div>
                    @empty
                        <p class="text-xs text-gray-400">No other announcements available.</p>
                    @endforelse
                </div>
            </div>

            <div class="bg-[#2d0012] header-pattern text-white rounded-2xl p-6 shadow-md border border-[#4a001c]">
                <span class="text-[#f9c22e] text-[10px] font-display tracking-[0.2em] uppercase block mb-1">
                    Academic Programs
                </span>
                <h4 class="font-display text-lg tracking-wide leading-tight mb-2">
                    EVSU NSTP Components
                </h4>
                <p class="text-xs text-[#e8b4c4]/70 leading-relaxed font-body mb-4">
                    Committed to youth civic consciousness, disaster preparedness, literacy development, and national defense readiness.
                </p>

                <div class="space-y-2 text-xs font-display uppercase tracking-wider text-[#fde68a]">
                    <div class="flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#f9c22e]"></span>
                        <span>CWTS · Civic Welfare Training</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#f9c22e]"></span>
                        <span>ROTC · Reserve Officers' Training</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#f9c22e]"></span>
                        <span>LTS · Literacy Training Service</span>
                    </div>
                </div>
            </div>
        </aside>
    </div>
</div>