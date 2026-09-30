import ApexCharts from 'apexcharts';
import { Editor } from '@tiptap/core';
import StarterKit from '@tiptap/starter-kit';
import Image from '@tiptap/extension-image';
import Link from '@tiptap/extension-link';
import Placeholder from '@tiptap/extension-placeholder';

window.ApexCharts = ApexCharts;

const CustomImage = Image.extend({
    addAttributes() {
        return {
            ...this.parent?.(),
            width: {
                default: '100%',
                parseHTML: element => element.getAttribute('data-width') || '100%',
                renderHTML: attributes => ({
                    'data-width': attributes.width || '100%',
                }),
            },
            alignment: {
                default: 'center',
                parseHTML: element => element.getAttribute('data-alignment') || 'center',
                renderHTML: attributes => ({
                    'data-alignment': attributes.alignment || 'center',
                }),
            },
            caption: {
                default: '',
                parseHTML: element => {
                    const figcaption = element.querySelector('figcaption');
                    return figcaption ? figcaption.textContent.trim() : (element.getAttribute('data-caption') || '');
                },
                renderHTML: attributes => ({
                    'data-caption': attributes.caption || '',
                }),
            },
        };
    },

    renderHTML({ HTMLAttributes }) {
        const { caption, alignment, width, ...imgAttrs } = HTMLAttributes;
        const align = alignment || 'center';
        const w = width || '100%';

        let marginStyle = 'margin-left: auto; margin-right: auto;';
        if (align === 'left') marginStyle = 'margin-left: 0; margin-right: auto;';
        if (align === 'right') marginStyle = 'margin-left: auto; margin-right: 0;';

        const containerStyle = `width: ${w}; max-width: 100%; ${marginStyle}`;

        if (caption && caption.trim() !== '') {
            return [
                'figure',
                {
                    class: 'tiptap-figure',
                    'data-alignment': align,
                    'data-width': w,
                    style: containerStyle,
                },
                [
                    'img',
                    {
                        ...imgAttrs,
                        'data-alignment': align,
                        'data-width': w,
                        'data-caption': caption,
                        alt: caption,
                        class: 'rounded-xl shadow-md w-full h-auto block',
                    },
                ],
                [
                    'figcaption',
                    {
                        class: 'image-caption text-xs text-gray-500 mt-2 text-center italic font-body',
                    },
                    caption,
                ],
            ];
        }

        return [
            'img',
            {
                ...imgAttrs,
                'data-alignment': align,
                'data-width': w,
                style: `width: ${w}; ${marginStyle}`,
                class: 'rounded-xl shadow-md h-auto block',
            },
        ];
    },

    parseHTML() {
        return [
            {
                tag: 'figure.tiptap-figure',
                getAttrs: element => {
                    const img = element.querySelector('img');
                    const figcaption = element.querySelector('figcaption');
                    if (!img) return false;

                    return {
                        src: img.getAttribute('src'),
                        alt: img.getAttribute('alt') || '',
                        width: element.getAttribute('data-width') || img.getAttribute('data-width') || '100%',
                        alignment: element.getAttribute('data-alignment') || img.getAttribute('data-alignment') || 'center',
                        caption: figcaption ? figcaption.textContent.trim() : (img.getAttribute('data-caption') || ''),
                    };
                },
            },
            {
                tag: 'img[src]',
                getAttrs: element => ({
                    src: element.getAttribute('src'),
                    alt: element.getAttribute('alt') || '',
                    width: element.getAttribute('data-width') || '100%',
                    alignment: element.getAttribute('data-alignment') || 'center',
                    caption: element.getAttribute('data-caption') || '',
                }),
            },
        ];
    },
});

window.setupTiptapEditor = function (config) {
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
                    CustomImage.configure({
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
        setImageWidth(width) {
            if (editor) {
                editor.chain().focus().updateAttributes('image', { width }).run();
                this.updatedAt = Date.now();
            }
        },

        setImageAlign(alignment) {
            if (editor) {
                editor.chain().focus().updateAttributes('image', { alignment }).run();
                this.updatedAt = Date.now();
            }
        },

        promptCaption() {
            if (!editor) return;
            const currentCaption = editor.getAttributes('image').caption || '';
            const newCaption = window.prompt('Enter image caption (leave empty to remove):', currentCaption);

            if (newCaption !== null) {
                editor.chain().focus().updateAttributes('image', { caption: newCaption.trim() }).run();
                this.updatedAt = Date.now();
            }
        },

        getImageCaption() {
            this.updatedAt;
            return editor ? (editor.getAttributes('image').caption || '') : '';
        },

        deleteImage() {
            if (editor) {
                editor.chain().focus().deleteSelection().run();
                this.updatedAt = Date.now();
            }
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
};