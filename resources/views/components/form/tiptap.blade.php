@props([
    'tempToken' => null,
    'postId' => null,
    'placeholder' => 'Write your announcement or news article content here...',
    'error' => null,
])

<div 
    wire:ignore
    x-data="setupTiptapEditor({
        content: @entangle($attributes->wire('model')),
        tempToken: @js($tempToken),
        postId: @js($postId),
        placeholder: @js($placeholder),
        uploadUrl: '{{ route('admin.posts.media.upload') }}',
        csrfToken: '{{ csrf_token() }}'
    })"
    class="border border-[#f9e6ec] rounded-lg overflow-hidden bg-white shadow-sm focus-within:border-[#800033] transition"
>
    <div class="bg-[#fdf2f5] border-b border-[#f9e6ec] px-3 py-2 flex flex-wrap items-center gap-1 text-xs">
        <button type="button" @click="toggleHeading(2)"
            :class="{ 'bg-[#4a001c] text-white': isActive('heading', { level: 2 }) }"
            class="px-2.5 py-1 rounded font-display tracking-wider uppercase text-[#4a001c] hover:bg-[#f9e6ec] transition"
            title="Heading 2">
            H2
        </button>

        <button type="button" @click="toggleHeading(3)"
            :class="{ 'bg-[#4a001c] text-white': isActive('heading', { level: 3 }) }"
            class="px-2.5 py-1 rounded font-display tracking-wider uppercase text-[#4a001c] hover:bg-[#f9e6ec] transition"
            title="Heading 3">
            H3
        </button>

        <span class="w-[1px] h-4 bg-[#e8b4c4] mx-1"></span>

        <button type="button" @click="toggleBold()"
            :class="{ 'bg-[#4a001c] text-white': isActive('bold') }"
            class="p-1.5 rounded font-bold text-[#4a001c] hover:bg-[#f9e6ec] transition"
            title="Bold">
            <b>B</b>
        </button>

        <button type="button" @click="toggleItalic()"
            :class="{ 'bg-[#4a001c] text-white': isActive('italic') }"
            class="p-1.5 rounded italic text-[#4a001c] hover:bg-[#f9e6ec] transition"
            title="Italic">
            <i>I</i>
        </button>

        <button type="button" @click="toggleStrike()"
            :class="{ 'bg-[#4a001c] text-white': isActive('strike') }"
            class="p-1.5 rounded line-through text-[#4a001c] hover:bg-[#f9e6ec] transition"
            title="Strikethrough">
            S
        </button>

        <span class="w-[1px] h-4 bg-[#e8b4c4] mx-1"></span>

        <button type="button" @click="toggleBulletList()"
            :class="{ 'bg-[#4a001c] text-white': isActive('bulletList') }"
            class="p-1.5 rounded text-[#4a001c] hover:bg-[#f9e6ec] transition"
            title="Bullet List">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75Zm0 5.25h.007v.008H3.75V12Zm0 5.25h.007v.008H3.75V17.25Z"/></svg>
        </button>

        <button type="button" @click="toggleOrderedList()"
            :class="{ 'bg-[#4a001c] text-white': isActive('orderedList') }"
            class="p-1.5 rounded text-[#4a001c] hover:bg-[#f9e6ec] transition"
            title="Numbered List">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M4.5 7.5V4.5l-1.5 1.5M3 13.5h3v-1.5c0-.828-.672-1.5-1.5-1.5S3 11.172 3 12v1.5Zm0 6h3V18c0-.828-.672-1.5-1.5-1.5S3 17.172 3 18v1.5Z"/></svg>
        </button>

        <button type="button" @click="toggleBlockquote()"
            :class="{ 'bg-[#4a001c] text-white': isActive('blockquote') }"
            class="p-1.5 rounded text-[#4a001c] hover:bg-[#f9e6ec] transition"
            title="Blockquote">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 0 1 .865-.501 48.172 48.172 0 0 0 3.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v5.018Z"/></svg>
        </button>

        <span class="w-[1px] h-4 bg-[#e8b4c4] mx-1"></span>

        <button type="button" @click="setLink()"
            :class="{ 'bg-[#4a001c] text-white': isActive('link') }"
            class="p-1.5 rounded text-[#4a001c] hover:bg-[#f9e6ec] transition"
            title="Insert Link">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 0 1 1.242 7.244l-4.5 4.5a4.5 4.5 0 0 1-6.364-6.364l1.757-1.757m13.35-.622 1.757-1.757a4.5 4.5 0 0 0-6.364-6.364l-4.5 4.5a4.5 4.5 0 0 0 1.242 7.244"/></svg>
        </button>

        <label 
            :class="{ 'opacity-50 pointer-events-none': isUploading }"
            class="p-1.5 rounded text-[#4a001c] hover:bg-[#f9e6ec] cursor-pointer flex items-center gap-1 transition"
            title="Upload and insert inline image"
        >
            <input type="file" @change="uploadImage($event)" accept="image/png,image/jpeg,image/webp" class="hidden">
            <template x-if="!isUploading">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z"/></svg>
            </template>
            <template x-if="isUploading">
                <svg class="w-4 h-4 animate-spin text-[#800033]" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
            </template>
            <span class="font-display uppercase tracking-wider text-[10px]">Add Image</span>
        </label>
    </div>

    <div class="p-4">
        <div x-ref="editorElement" class="tiptap"></div>
    </div>

    @if ($error)
        <div class="px-4 pb-2 text-[11px] text-red-500 font-semibold">{{ $error }}</div>
    @endif
</div>