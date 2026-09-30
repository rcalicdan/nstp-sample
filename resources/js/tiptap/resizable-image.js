import Image from '@tiptap/extension-image';

export const ResizableImage = Image.extend({
    name: 'image',
    inline: false,
    group: 'block',

    addAttributes() {
        return {
            ...this.parent?.(),
            src: {
                default: null,
            },
            alt: {
                default: '',
            },
            width: {
                default: '100%',
                parseHTML: el => el.getAttribute('data-width') || el.querySelector('img')?.getAttribute('data-width') || el.style.width || '100%',
                renderHTML: attrs => ({
                    'data-width': attrs.width || '100%',
                }),
            },
            alignment: {
                default: 'center',
                parseHTML: el => el.getAttribute('data-alignment') || el.querySelector('img')?.getAttribute('data-alignment') || 'center',
                renderHTML: attrs => ({
                    'data-alignment': attrs.alignment || 'center',
                }),
            },
            caption: {
                default: '',
                parseHTML: el => {
                    const fig = el.querySelector('figcaption');
                    if (fig) return fig.textContent.trim();
                    const img = el.querySelector('img');
                    return img ? (img.getAttribute('data-caption') || '') : (el.getAttribute('data-caption') || '');
                },
                renderHTML: attrs => ({
                    'data-caption': attrs.caption || '',
                }),
            },
        };
    },

    renderHTML({ node, HTMLAttributes }) {
        const align = node.attrs.alignment || HTMLAttributes['data-alignment'] || 'center';
        const w = node.attrs.width || HTMLAttributes['data-width'] || '100%';
        const cap = (node.attrs.caption ?? HTMLAttributes['data-caption'] ?? '').trim();
        const src = node.attrs.src || HTMLAttributes.src;
        const alt = node.attrs.alt || HTMLAttributes.alt || cap;

        let marginStyle = 'margin-left: auto; margin-right: auto;';
        if (align === 'left') marginStyle = 'margin-left: 0; margin-right: auto;';
        if (align === 'right') marginStyle = 'margin-left: auto; margin-right: 0;';

        const style = `width: ${w}; max-width: 100%; ${marginStyle}`;

        if (cap !== '') {
            return [
                'figure',
                {
                    class: 'tiptap-figure',
                    'data-alignment': align,
                    'data-width': w,
                    style,
                },
                [
                    'img',
                    {
                        src,
                        alt,
                        'data-alignment': align,
                        'data-width': w,
                        'data-caption': cap,
                        class: 'rounded-xl shadow-md w-full h-auto block',
                    },
                ],
                [
                    'figcaption',
                    { class: 'image-caption text-xs text-gray-500 mt-2 text-center italic font-body' },
                    cap,
                ],
            ];
        }

        return [
            'figure',
            {
                class: 'tiptap-figure',
                'data-alignment': align,
                'data-width': w,
                style,
            },
            [
                'img',
                {
                    src,
                    alt,
                    'data-alignment': align,
                    'data-width': w,
                    class: 'rounded-xl shadow-md w-full h-auto block',
                },
            ],
        ];
    },

    parseHTML() {
        return [
            {
                tag: 'figure.tiptap-figure',
                consuming: true,
                getAttrs: el => {
                    const img = el.querySelector('img');
                    const fig = el.querySelector('figcaption');
                    if (!img) return false;

                    const width = el.getAttribute('data-width') || img.getAttribute('data-width') || el.style.width || '100%';
                    const alignment = el.getAttribute('data-alignment') || img.getAttribute('data-alignment') || 'center';
                    const caption = fig ? fig.textContent.trim() : (img.getAttribute('data-caption') || el.getAttribute('data-caption') || '');

                    return {
                        src: img.getAttribute('src'),
                        alt: img.getAttribute('alt') || '',
                        width,
                        alignment,
                        caption,
                    };
                },
            },
            {
                tag: 'img[src]',
                getAttrs: el => ({
                    src: el.getAttribute('src'),
                    alt: el.getAttribute('alt') || '',
                    width: el.getAttribute('data-width') || el.style.width || '100%',
                    alignment: el.getAttribute('data-alignment') || 'center',
                    caption: el.getAttribute('data-caption') || '',
                }),
            },
        ];
    },

    addNodeView() {
        return ({ node, editor, getPos }) => {
            const container = document.createElement('figure');
            container.className = 'tiptap-image-node-view group relative my-4 block transition-all';

            const applyLayout = (w, a) => {
                container.style.width = w;
                container.style.maxWidth = '100%';

                if (a === 'left') {
                    container.style.marginLeft = '0';
                    container.style.marginRight = 'auto';
                } else if (a === 'right') {
                    container.style.marginLeft = 'auto';
                    container.style.marginRight = '0';
                } else {
                    container.style.marginLeft = 'auto';
                    container.style.marginRight = 'auto';
                }
            };

            applyLayout(node.attrs.width || '100%', node.attrs.alignment || 'center');

            const wrapper = document.createElement('div');
            wrapper.className = 'relative inline-block w-full overflow-visible rounded-xl';

            const img = document.createElement('img');
            img.src = node.attrs.src;
            img.alt = node.attrs.alt || '';
            img.className = 'w-full h-auto rounded-xl shadow-md block select-none pointer-events-none';
            wrapper.appendChild(img);

            const overlay = document.createElement('div');
            overlay.className = 'absolute top-3 right-3 opacity-0 group-hover:opacity-100 transition-opacity duration-150 flex items-center gap-1 bg-[#2d0012]/90 backdrop-blur-md p-1.5 rounded-lg shadow-xl z-20';
            overlay.innerHTML = `
                <button type="button" data-action="align-left" title="Align Left" class="p-1 hover:bg-white/20 rounded text-white text-xs">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h10.5m-10.5 5.25h16.5"/></svg>
                </button>
                <button type="button" data-action="align-center" title="Align Center" class="p-1 hover:bg-white/20 rounded text-white text-xs">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M6.75 12h10.5m-13.5 5.25h16.5"/></svg>
                </button>
                <button type="button" data-action="align-right" title="Align Right" class="p-1 hover:bg-white/20 rounded text-white text-xs">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M9.75 12h10.5m-16.5 5.25h16.5"/></svg>
                </button>
                <span class="w-[1px] h-3.5 bg-white/20 mx-0.5"></span>
                <button type="button" data-action="size-half" title="50% Width" class="px-1.5 py-0.5 hover:bg-white/20 rounded text-white font-mono text-[10px]">50%</button>
                <button type="button" data-action="size-full" title="100% Width" class="px-1.5 py-0.5 hover:bg-white/20 rounded text-white font-mono text-[10px]">100%</button>
                <span class="w-[1px] h-3.5 bg-white/20 mx-0.5"></span>
                <button type="button" data-action="delete" title="Remove Image" class="p-1 bg-red-600/80 hover:bg-red-600 rounded text-white text-xs transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/></svg>
                </button>
            `;

            const commitChange = (key, value) => {
                const pos = typeof getPos === 'function' ? getPos() : null;
                if (pos !== null && editor) {
                    editor.commands.command(({ tr }) => {
                        tr.setNodeAttribute(pos, key, value);
                        return true;
                    });
                }
            };

            overlay.addEventListener('click', (e) => {
                const btn = e.target.closest('button');
                if (!btn) return;
                e.preventDefault();
                e.stopPropagation();

                const action = btn.dataset.action;
                const pos = typeof getPos === 'function' ? getPos() : null;
                if (pos === null) return;

                if (action === 'delete') {
                    editor.chain().focus().setNodeSelection(pos).deleteSelection().run();
                } else if (action === 'align-left' || action === 'align-center' || action === 'align-right') {
                    const alignment = action.replace('align-', '');
                    commitChange('alignment', alignment);
                    applyLayout(container.style.width, alignment);
                } else if (action === 'size-half') {
                    commitChange('width', '50%');
                    applyLayout('50%', node.attrs.alignment || 'center');
                } else if (action === 'size-full') {
                    commitChange('width', '100%');
                    applyLayout('100%', node.attrs.alignment || 'center');
                }
            });

            wrapper.appendChild(overlay);

            // Drag handles
            const rightHandle = document.createElement('div');
            rightHandle.className = 'absolute -right-2 top-1/2 -translate-y-1/2 w-4 h-10 bg-[#800033] hover:bg-[#4a001c] rounded-full shadow-lg cursor-ew-resize opacity-0 group-hover:opacity-100 transition-opacity z-20 flex items-center justify-center';
            rightHandle.innerHTML = '<span class="w-1 h-4 bg-white/60 rounded"></span>';

            const leftHandle = document.createElement('div');
            leftHandle.className = 'absolute -left-2 top-1/2 -translate-y-1/2 w-4 h-10 bg-[#800033] hover:bg-[#4a001c] rounded-full shadow-lg cursor-ew-resize opacity-0 group-hover:opacity-100 transition-opacity z-20 flex items-center justify-center';
            leftHandle.innerHTML = '<span class="w-1 h-4 bg-white/60 rounded"></span>';

            const initResize = (e, direction) => {
                e.preventDefault();
                e.stopPropagation();

                const startX = e.clientX;
                const startWidth = container.offsetWidth;
                const parentWidth = container.parentElement ? container.parentElement.offsetWidth : 800;

                const onMouseMove = (moveEvent) => {
                    const diff = direction === 'right' ? (moveEvent.clientX - startX) : (startX - moveEvent.clientX);
                    let newPx = Math.max(120, Math.min(parentWidth, startWidth + diff * 2));
                    let percent = Math.round((newPx / parentWidth) * 100);
                    percent = Math.max(15, Math.min(100, percent));

                    container.style.width = `${percent}%`;
                };

                const onMouseUp = () => {
                    window.removeEventListener('mousemove', onMouseMove);
                    window.removeEventListener('mouseup', onMouseUp);

                    commitChange('width', container.style.width);
                };

                window.addEventListener('mousemove', onMouseMove);
                window.addEventListener('mouseup', onMouseUp);
            };

            rightHandle.addEventListener('mousedown', (e) => initResize(e, 'right'));
            leftHandle.addEventListener('mousedown', (e) => initResize(e, 'left'));

            wrapper.appendChild(rightHandle);
            wrapper.appendChild(leftHandle);
            container.appendChild(wrapper);

            // In-place inline caption
            const captionInput = document.createElement('input');
            captionInput.type = 'text';
            captionInput.value = node.attrs.caption || '';
            captionInput.placeholder = 'Type a caption for this photo (optional)...';
            captionInput.className = 'w-full text-center text-xs text-gray-500 italic mt-2 bg-transparent border-b border-transparent hover:border-gray-200 focus:border-[#800033] focus:outline-none transition py-0.5 font-body';

            captionInput.addEventListener('keydown', (e) => {
                e.stopPropagation();
                if (e.key === 'Enter') {
                    e.preventDefault();
                    captionInput.blur();
                }
            });
            captionInput.addEventListener('keyup', (e) => e.stopPropagation());
            captionInput.addEventListener('keypress', (e) => e.stopPropagation());
            captionInput.addEventListener('input', (e) => e.stopPropagation());
            captionInput.addEventListener('mousedown', (e) => e.stopPropagation());
            captionInput.addEventListener('click', (e) => e.stopPropagation());

            const saveCaption = () => {
                commitChange('caption', captionInput.value.trim());
            };
            captionInput.addEventListener('change', saveCaption);
            captionInput.addEventListener('blur', saveCaption);

            container.appendChild(captionInput);

            return {
                dom: container,
                stopEvent(event) {
                    return (
                        event.target === captionInput ||
                        overlay.contains(event.target) ||
                        rightHandle.contains(event.target) ||
                        leftHandle.contains(event.target)
                    );
                },
                ignoreMutation() {
                    return true;
                },
                update(updatedNode) {
                    if (updatedNode.type.name !== 'image') return false;

                    applyLayout(updatedNode.attrs.width || '100%', updatedNode.attrs.alignment || 'center');
                    img.src = updatedNode.attrs.src;

                    if (document.activeElement !== captionInput) {
                        captionInput.value = updatedNode.attrs.caption || '';
                    }

                    return true;
                },
            };
        };
    },
});