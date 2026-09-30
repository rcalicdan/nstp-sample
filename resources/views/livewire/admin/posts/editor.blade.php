<div x-data="{ activeView: 'edit' }">
    <x-slot:header>
        <x-partials.header :title="$post ? 'Edit Article' : 'Write New Article'" subtitle="Compose institutional bulletins, announcements, and news updates">
            <x-slot:actions>
                @if ($post && $post->exists)
                    <a href="{{ route('news.show', $post->slug) }}" target="_blank">
                        <x-utils.button type="button" color="outline-gold" size="md">
                            ↗ View Dedicated Page
                        </x-utils.button>
                    </a>
                @endif

                <a href="{{ route('admin.posts.index') }}" wire:navigate>
                    <x-utils.button type="button" color="outline-gold" size="md">
                        ← Back to Bulletins
                    </x-utils.button>
                </a>
            </x-slot:actions>
        </x-partials.header>
    </x-slot:header>

    <form wire:submit="save">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                <div class="flex items-center gap-2 border-b border-[#f9e6ec] pb-2">
                    <button type="button" @click="activeView = 'edit'"
                        :class="activeView === 'edit' ? 'bg-[#4a001c] text-[#f9c22e] font-semibold shadow-sm' :
                            'text-gray-500 hover:text-[#4a001c] hover:bg-[#fdf2f5]'"
                        class="px-4 py-2 rounded-lg text-xs font-display tracking-widest uppercase transition flex items-center gap-2">
                        <span>Write Article</span>
                    </button>

                    <button type="button" @click="activeView = 'preview'"
                        :class="activeView === 'preview' ? 'bg-[#4a001c] text-[#f9c22e] font-semibold shadow-sm' :
                            'text-gray-500 hover:text-[#4a001c] hover:bg-[#fdf2f5]'"
                        class="px-4 py-2 rounded-lg text-xs font-display tracking-widest uppercase transition flex items-center gap-2">
                        <span>Live Public Preview</span>
                    </button>
                </div>

                <div x-show="activeView === 'edit'" class="space-y-6">
                    <div class="bg-white rounded-lg border border-[#f9e6ec] shadow p-6">
                        <x-form.label required>Article Headline / Title</x-form.label>
                        <input wire:model.live.debounce.300ms="form.title" type="text"
                            placeholder="e.g. NSTP Orientation and Opening Ceremonies for AY 2026-2027"
                            class="w-full border border-[#f9e6ec] rounded px-4 py-3 text-lg font-display tracking-wide text-[#2d0012] bg-[#fdf2f5]/30 focus:border-[#800033] focus:outline-none" />
                        @error('form.title')
                            <span class="text-[10px] text-red-500 font-semibold mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="bg-white rounded-lg border border-[#f9e6ec] shadow p-6">
                        <x-form.label required class="mb-2">Article Body Content</x-form.label>
                        <x-form.tiptap wire:model="form.content" :tempToken="$form->tempToken" :postId="$post?->id"
                            :error="$errors->first('form.content')" />
                    </div>
                </div>

                <div x-show="activeView === 'preview'" style="display: none;"
                    class="bg-white rounded-2xl border border-[#f9e6ec] shadow p-6 sm:p-10 space-y-6">
                    <div
                        class="bg-blue-50 border border-blue-200 text-blue-900 rounded-lg p-3 text-xs flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></span>
                            <span><strong>Live Interactive Preview:</strong> Real-time simulation of public typography,
                                image sizing, and captions.</span>
                        </div>
                        <button type="button" @click="activeView = 'edit'"
                            class="underline font-semibold hover:text-blue-950">Back to Editor</button>
                    </div>

                    <header class="border-b border-[#fdf2f5] pb-5">
                        <span
                            class="inline-block text-[10px] font-bold uppercase tracking-wider px-2.5 py-0.5 rounded border mb-3 bg-[#fdf2f5] text-[#800033] border-[#f9e6ec]"
                            x-text="$wire.form.category">
                        </span>

                        <h1 class="text-2xl sm:text-3xl font-display font-bold text-[#2d0012] leading-tight mb-2"
                            x-text="$wire.form.title || 'Untitled Article Headline'">
                        </h1>

                        <p class="text-xs text-gray-400 font-body">
                            Published by {{ auth()->user()->name }} • Today (Live Simulation)
                        </p>
                    </header>

                    @if ($form->featuredImage)
                        <div class="rounded-xl overflow-hidden border border-[#f9e6ec] shadow-sm">
                            <img src="{{ $form->featuredImage->temporaryUrl() }}" alt="Cover"
                                class="w-full max-h-[350px] object-cover" />
                        </div>
                    @elseif ($form->existingFeaturedImage)
                        <div class="rounded-xl overflow-hidden border border-[#f9e6ec] shadow-sm">
                            <img src="{{ $form->existingFeaturedImage }}" alt="Current Cover"
                                class="w-full max-h-[350px] object-cover" />
                        </div>
                    @endif

                    <div x-show="$wire.form.excerpt"
                        class="bg-[#fdf2f5] border-l-4 border-[#800033] p-4 rounded-r-lg text-sm italic text-[#4a001c] font-body"
                        x-text="$wire.form.excerpt">
                    </div>

                    <div class="prose max-w-none text-[#2d0012] text-sm sm:text-base font-body leading-relaxed"
                        x-html="$wire.form.content || '<p class=\'text-gray-400 italic\'>No content written yet...</p>'">
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="bg-white rounded-lg border border-[#f9e6ec] shadow overflow-hidden">
                    <div class="bg-[#2d0012] px-5 py-3 border-b border-[#4a001c]">
                        <h3 class="text-white font-display text-xs uppercase tracking-wider">Publishing Controls</h3>
                    </div>
                    <div class="p-5 space-y-4 text-xs">
                        <div>
                            <x-form.label required>Category</x-form.label>
                            <x-form.select wire:model="form.category" :error="$errors->first('form.category')">
                                @foreach (\App\Enums\PostCategory::cases() as $cat)
                                    <option value="{{ $cat->value }}">{{ $cat->label() }}</option>
                                @endforeach
                            </x-form.select>
                        </div>

                        <div>
                            <x-form.label required>Publishing Status</x-form.label>
                            <div class="space-y-2 mt-1.5">
                                <label
                                    class="flex items-center gap-2 p-2.5 rounded-lg border cursor-pointer transition
                                    {{ $form->publish_mode === 'now' ? 'bg-[#fdf2f5] border-[#800033] text-[#800033]' : 'border-gray-200 hover:bg-gray-50' }}">
                                    <input type="radio" wire:model.live="form.publish_mode" value="now"
                                        class="text-[#800033] focus:ring-[#800033]">
                                    <div>
                                        <span
                                            class="font-display uppercase tracking-wider font-semibold block text-xs">Publish
                                            Immediately</span>
                                        <span class="text-[10px] text-gray-500 font-normal">Goes live on the public site
                                            right now</span>
                                    </div>
                                </label>

                                <label
                                    class="flex items-center gap-2 p-2.5 rounded-lg border cursor-pointer transition
                                    {{ $form->publish_mode === 'schedule' ? 'bg-[#fdf2f5] border-[#800033] text-[#800033]' : 'border-gray-200 hover:bg-gray-50' }}">
                                    <input type="radio" wire:model.live="form.publish_mode" value="schedule"
                                        class="text-[#800033] focus:ring-[#800033]">
                                    <div>
                                        <span
                                            class="font-display uppercase tracking-wider font-semibold block text-xs">Schedule
                                            for Later</span>
                                        <span class="text-[10px] text-gray-500 font-normal">Auto-publish on a specified
                                            date & time</span>
                                    </div>
                                </label>

                                <label
                                    class="flex items-center gap-2 p-2.5 rounded-lg border cursor-pointer transition
                                    {{ $form->publish_mode === 'draft' ? 'bg-[#fdf2f5] border-[#800033] text-[#800033]' : 'border-gray-200 hover:bg-gray-50' }}">
                                    <input type="radio" wire:model.live="form.publish_mode" value="draft"
                                        class="text-[#800033] focus:ring-[#800033]">
                                    <div>
                                        <span
                                            class="font-display uppercase tracking-wider font-semibold block text-xs">Save
                                            as Draft</span>
                                        <span class="text-[10px] text-gray-500 font-normal">Private, not visible to the
                                            public</span>
                                    </div>
                                </label>
                            </div>
                        </div>

                        @if ($form->publish_mode === 'schedule')
                            <div class="p-3 bg-amber-50 border border-amber-200 rounded-lg space-y-1.5 transition">
                                <x-form.label required class="text-amber-900">Set Date & Time to Go Live</x-form.label>
                                <input wire:model="form.scheduled_at" type="datetime-local"
                                    class="w-full border border-amber-300 rounded px-3 py-2 text-xs bg-white focus:border-[#800033] focus:outline-none text-[#2d0012]" />
                                @error('form.scheduled_at')
                                    <span class="text-[10px] text-red-500 font-semibold block">{{ $message }}</span>
                                @enderror
                            </div>
                        @endif

                        <div class="pt-2 border-t border-[#f9e6ec]">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" wire:model="form.is_pinned"
                                    class="rounded border-gray-300 text-[#800033] focus:ring-[#800033]">
                                <span
                                    class="font-display tracking-wider uppercase text-gray-700 font-semibold text-xs">Pin
                                    to Top of Bulletins</span>
                            </label>
                        </div>

                        <div class="flex flex-col gap-2 pt-4 border-t border-[#f9e6ec]">
                            @if ($form->publish_mode === 'now')
                                <x-utils.button type="submit" color="gold" size="md" class="w-full"
                                    loadingText="Publishing...">
                                    Publish Immediately
                                </x-utils.button>
                            @elseif($form->publish_mode === 'schedule')
                                <x-utils.button type="submit" color="gold" size="md" class="w-full"
                                    loadingText="Scheduling...">
                                    Schedule Article
                                </x-utils.button>
                            @else
                                <x-utils.button type="submit" color="primary" size="md" class="w-full"
                                    loadingText="Saving...">
                                    Save Draft
                                </x-utils.button>
                            @endif

                            @if ($form->publish_mode !== 'draft')
                                <button type="button" wire:click="save('draft')"
                                    class="text-[11px] text-gray-500 hover:text-[#800033] text-center transition font-display uppercase tracking-wider">
                                    or save as private draft
                                </button>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg border border-[#f9e6ec] shadow overflow-hidden">
                    <div class="bg-[#2d0012] px-5 py-3 border-b border-[#4a001c]">
                        <h3 class="text-white font-display text-xs uppercase tracking-wider">Featured Cover Photo</h3>
                    </div>
                    <div class="p-5 space-y-3">
                        @if ($form->featuredImage)
                            <div class="relative rounded-lg overflow-hidden border border-[#f9e6ec]">
                                <img src="{{ $form->featuredImage->temporaryUrl() }}" alt="Preview"
                                    class="w-full h-40 object-cover" />
                                <button type="button" wire:click="$set('form.featuredImage', null)"
                                    class="absolute top-2 right-2 bg-red-600 text-white rounded-full p-1 shadow hover:bg-red-700 transition"
                                    title="Remove image">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        @elseif ($form->existingFeaturedImage)
                            <div class="relative rounded-lg overflow-hidden border border-[#f9e6ec]">
                                <img src="{{ $form->existingFeaturedImage }}" alt="Current Cover"
                                    class="w-full h-40 object-cover" />
                                <button type="button" wire:click="$wire.form.removeFeaturedImage()"
                                    class="absolute top-2 right-2 bg-red-600 text-white rounded-full p-1 shadow hover:bg-red-700 transition"
                                    title="Delete current cover">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        @else
                            <label
                                class="border-2 border-dashed border-[#e8b4c4] bg-[#fdf2f5]/30 rounded-lg p-6 flex flex-col items-center justify-center cursor-pointer hover:border-[#800033] hover:bg-[#fdf2f5] transition">
                                <svg class="w-8 h-8 text-[#800033]/60 mb-2" fill="none" stroke="currentColor"
                                    stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                                </svg>
                                <span
                                    class="text-xs font-display tracking-wider uppercase text-[#800033] font-semibold">Upload
                                    Cover Photo</span>
                                <span class="text-[10px] text-gray-400 mt-0.5">PNG, JPG, WebP up to 5MB</span>
                                <input type="file" wire:model="form.featuredImage" accept="image/*"
                                    class="hidden" />
                            </label>
                        @endif
                        @error('form.featuredImage')
                            <span class="text-[10px] text-red-500 font-semibold block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="bg-white rounded-lg border border-[#f9e6ec] shadow overflow-hidden">
                    <div class="bg-[#2d0012] px-5 py-3 border-b border-[#4a001c]">
                        <h3 class="text-white font-display text-xs uppercase tracking-wider">Brief Summary / Excerpt
                        </h3>
                    </div>
                    <div class="p-5">
                        <textarea wire:model="form.excerpt" rows="4"
                            placeholder="Write a short 1-2 sentence teaser summary for search snippets and preview cards..."
                            class="w-full border border-[#f9e6ec] rounded px-3 py-2 text-xs bg-[#fdf2f5]/30 focus:border-[#800033] focus:outline-none text-[#2d0012] resize-none"></textarea>
                        @error('form.excerpt')
                            <span class="text-[10px] text-red-500 font-semibold mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
