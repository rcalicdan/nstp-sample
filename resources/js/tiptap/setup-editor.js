import { Editor } from '@tiptap/core';
import StarterKit from '@tiptap/starter-kit';
import Link from '@tiptap/extension-link';
import Placeholder from '@tiptap/extension-placeholder';
import { ResizableImage } from './resizable-image';

export function setupTiptapEditor(config) {
    let editor = null;

    return {
        content: config.content,
        isUploading: false,
        updatedAt: Date.now(),

        init() {
            const self = this;

            editor = new Editor({
                element: this.$refs.editorElement,
                extensions: [
                    StarterKit.configure({
                        heading: { levels: [2, 3] },
                        link: false,
                    }),
                    ResizableImage.configure({
                        inline: false,
                        allowBase64: false,
                    }),
                    Link.configure({
                        openOnClick: false,
                        HTMLAttributes: {
                            class: 'text-[#800033] underline font-medium hover:text-[#4a001c]',
                        },
                    }),
                    Placeholder.configure({
                        placeholder: config.placeholder || 'Write your content here...',
                    }),
                ],
                content: this.content,
                onUpdate: () => {
                    self.content = editor.getHTML();
                    self.updatedAt = Date.now();
                },
                onSelectionUpdate: () => {
                    self.updatedAt = Date.now();
                },
            });

            this.$watch('content', (newVal) => {
                if (editor && newVal !== editor.getHTML()) {
                    editor.commands.setContent(newVal || '', false);
                }
            });
        },

        isActive(type, opts = {}) {
            this.updatedAt;
            return editor ? editor.isActive(type, opts) : false;
        },

        toggleHeading(level) {
            if (editor) editor.chain().focus().toggleHeading({ level }).run();
        },

        toggleBold() {
            if (editor) editor.chain().focus().toggleBold().run();
        },

        toggleItalic() {
            if (editor) editor.chain().focus().toggleItalic().run();
        },

        toggleStrike() {
            if (editor) editor.chain().focus().toggleStrike().run();
        },

        toggleBulletList() {
            if (editor) editor.chain().focus().toggleBulletList().run();
        },

        toggleOrderedList() {
            if (editor) editor.chain().focus().toggleOrderedList().run();
        },

        toggleBlockquote() {
            if (editor) editor.chain().focus().toggleBlockquote().run();
        },

        setLink() {
            if (!editor) return;
            const previousUrl = editor.getAttributes('link').href;
            const url = window.prompt('Enter link URL:', previousUrl || '');
            if (url === null) return;
            if (url === '') {
                editor.chain().focus().extendMarkRange('link').unsetLink().run();
                return;
            }
            editor.chain().focus().extendMarkRange('link').setLink({ href: url }).run();
        },

        uploadImage(event) {
            const file = event.target.files[0];
            if (!file) return;

            if (file.size > 5 * 1024 * 1024) {
                alert('Image file size must be less than 5MB.');
                return;
            }

            this.isUploading = true;

            const formData = new FormData();
            formData.append('image', file);
            if (config.tempToken) formData.append('temp_token', config.tempToken);
            if (config.postId) formData.append('post_id', config.postId);

            fetch(config.uploadUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': config.csrfToken,
                    'Accept': 'application/json',
                },
                body: formData,
            })
            .then(res => {
                if (!res.ok) throw new Error('Upload failed');
                return res.json();
            })
            .then(data => {
                if (editor) {
                    editor.chain().focus().setImage({ src: data.url }).run();
                }
            })
            .catch(err => {
                alert('Failed to upload image. Please try again.');
                console.error(err);
            })
            .finally(() => {
                this.isUploading = false;
                event.target.value = '';
            });
        },

        destroy() {
            if (editor) {
                editor.destroy();
                editor = null;
            }
        },
    };
}