@props([
    'id',
    'name',
    'value' => '',
    'rows' => 6,
    'height' => 360,
    'placeholder' => '',
    'required' => false,
    'error' => null,
    'helper' => null,
])

@php
    $fieldName = $error ?: $name;
    $baseClasses = 'w-full rounded-lg border bg-onyx px-4 py-3 text-champagne placeholder:text-smoke focus:border-gold/30 focus:outline-none focus:ring-2 focus:ring-gold/15';
    $stateClasses = $errors->has($fieldName) ? 'border-red-400/60' : 'border-white/8';
    $helperId = $helper ? $id . '-help' : null;
    $clientErrorId = $required ? $id . '-client-error' : null;
    $errorId = $errors->has($fieldName) ? $id . '-error' : null;
    $describedBy = trim(implode(' ', array_filter([$helperId, $clientErrorId, $errorId, $attributes->get('aria-describedby')])));
@endphp

@once
    @push('styles')
        <script src="https://cdn.tiny.cloud/1/xv7eemu094bxfmbh4f4ff4wubpe7jydmpwxj699930in6w7e/tinymce/8/tinymce.min.js"
            referrerpolicy="origin" crossorigin="anonymous"></script>

        <style>
            .tox.tox-tinymce {
                border: 1px solid rgb(255 255 255 / 0.08);
                border-radius: 1rem;
                overflow: hidden;
                box-shadow: 0 20px 45px rgb(0 0 0 / 0.2);
            }

            html.sg-tinymce-fullscreen,
            body.sg-tinymce-fullscreen,
            html.tox-fullscreen,
            body.tox-fullscreen {
                overflow: hidden !important;
            }

            .tox.tox-tinymce.tox-tinymce--disabled {
                opacity: 0.7;
            }

            .tox.tox-tinymce.tox-fullscreen {
                border-color: transparent;
                border-radius: 0;
                overflow: visible;
                box-shadow: none;
                z-index: 70;
            }

            .tox.tox-tinymce-aux,
            .tox .tox-silver-sink,
            .tox-tinymce-aux .tox-dialog-wrap,
            .tox-tinymce-aux .tox-menu {
                z-index: 80 !important;
            }

            .tox .tox-statusbar,
            .tox .tox-toolbar-overlord,
            .tox .tox-menubar {
                background: #1c1712;
            }

            .tox.tox-tinymce.sg-tinymce-invalid {
                border-color: rgb(248 113 113 / 0.6);
                box-shadow: 0 0 0 1px rgb(248 113 113 / 0.2);
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const editorSelector = 'textarea[data-tinymce-editor]';
                const textareas = document.querySelectorAll(editorSelector);
                const uploadUrl = @js(route('tinymce.images.store'));

                if (!textareas.length || typeof tinymce === 'undefined') {
                    return;
                }

                const syncFullscreenLayoutState = () => {
                    const isFullscreen = document.querySelector('.tox.tox-tinymce.tox-fullscreen') !== null;

                    document.documentElement.classList.toggle('sg-tinymce-fullscreen', isFullscreen);
                    document.body.classList.toggle('sg-tinymce-fullscreen', isFullscreen);
                };

                const scheduleFullscreenLayoutStateSync = () => {
                    window.requestAnimationFrame(syncFullscreenLayoutState);
                };

                const uploadImage = async (file, csrfToken, progressCallback = null) => {
                    const formData = new FormData();
                    formData.append('image', file, file.name || 'editor-image');

                    const response = await fetch(uploadUrl, {
                        method: 'POST',
                        credentials: 'same-origin',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                        },
                        body: formData,
                    });

                    const payload = await response.json().catch(() => ({}));

                    if (!response.ok || typeof payload.location !== 'string') {
                        const message = payload?.errors?.image?.[0]
                            || payload?.message
                            || 'Upload gambar gagal.';

                        throw new Error(message);
                    }

                    if (typeof progressCallback === 'function') {
                        progressCallback(100);
                    }

                    return payload.location;
                };

                textareas.forEach((textarea) => {
                    if (!textarea.id || tinymce.get(textarea.id)) {
                        return;
                    }

                    const height = Number(textarea.dataset.tinymceHeight || 360);
                    const placeholder = textarea.dataset.tinymcePlaceholder || '';
                    const form = textarea.closest('form');
                    const csrfToken = form?.querySelector('input[name="_token"]')?.value || '';
                    const clientErrorElement = document.getElementById(`${textarea.id}-client-error`);
                    const setClientError = (editor, message = '') => {
                        const container = editor.getContainer();

                        if (clientErrorElement) {
                            clientErrorElement.textContent = message;
                            clientErrorElement.classList.toggle('hidden', message === '');
                        }

                        container?.classList.toggle('sg-tinymce-invalid', message !== '');
                    };

                    tinymce.init({
                        target: textarea,
                        height,
                        min_height: height,
                        menubar: 'file edit view insert format tools table help',
                        plugins: 'advlist anchor autolink charmap code codesample emoticons fullscreen help image insertdatetime link lists media preview searchreplace table visualblocks visualchars wordcount',
                        toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media table codesample blockquote | emoticons charmap insertdatetime | visualblocks visualchars removeformat | code fullscreen preview help',
                        toolbar_mode: 'sliding',
                        contextmenu: 'link image table',
                        browser_spellcheck: true,
                        promotion: false,
                        branding: false,
                        resize: true,
                        image_title: true,
                        image_advtab: true,
                        image_class_list: [{
                                title: 'Default',
                                value: ''
                            },
                            {
                                title: 'Responsive',
                                value: 'img-responsive'
                            },
                            {
                                title: 'Responsive Full',
                                value: 'img-responsive img-full'
                            },
                            {
                                title: 'Responsive Narrow',
                                value: 'img-responsive img-narrow'
                            }
                        ],
                        automatic_uploads: true,
                        paste_data_images: true,
                        convert_urls: false,
                        link_default_protocol: 'https',
                        file_picker_types: 'image',
                        table_toolbar: 'tableprops tabledelete | tableinsertrowbefore tableinsertrowafter tabledeleterow | tableinsertcolbefore tableinsertcolafter tabledeletecol | tablecellprops',
                        skin: 'oxide-dark',
                        content_css: 'dark',
                        placeholder,
                        images_upload_handler: async (blobInfo, progress) => {
                            if (!csrfToken) {
                                throw new Error('CSRF token tidak ditemukan.');
                            }

                            return uploadImage(blobInfo.blob(), csrfToken, progress);
                        },
                        file_picker_callback: (callback, _value, meta) => {
                            if (meta.filetype !== 'image') {
                                return;
                            }

                            const input = document.createElement('input');
                            input.type = 'file';
                            input.accept = 'image/*';

                            input.addEventListener('change', async () => {
                                const file = input.files?.[0];

                                if (!file) {
                                    return;
                                }

                                try {
                                    const url = await uploadImage(file, csrfToken);
                                    callback(url, {
                                        alt: file.name,
                                    });
                                } catch (error) {
                                    const message = error instanceof Error ? error.message : 'Upload gambar gagal.';
                                    tinymce.activeEditor?.windowManager.alert(message);
                                }
                            });

                            input.click();
                        },
                        content_style: `
                            body {
                                background: #272019;
                                color: #e7eaf0;
                                font-family: "Segoe UI", sans-serif;
                                font-size: 14px;
                                line-height: 1.7;
                            }
                            a { color: #e2c78f; }
                            blockquote {
                                border-left: 4px solid #c7a15a;
                                margin: 1.25rem 0;
                                padding-left: 1rem;
                            }
                            code, pre {
                                background: #1c1712;
                                color: #f8fafc;
                            }
                            img {
                                display: block;
                                max-width: 100%;
                                height: auto;
                            }
                            img.img-responsive {
                                display: block;
                                max-width: 100%;
                                height: auto;
                            }
                            img.img-full {
                                width: 100%;
                            }
                            img.img-narrow {
                                width: min(100%, 28rem);
                                margin-left: auto;
                                margin-right: auto;
                            }
                            figure.image {
                                max-width: 100%;
                                margin: 1.25rem auto;
                            }
                            figure.image img {
                                max-width: 100%;
                                height: auto;
                            }
                            table {
                                width: 100%;
                                border-collapse: collapse;
                            }
                            th, td {
                                border: 1px solid rgb(255 255 255 / 0.12);
                                padding: 0.75rem;
                                vertical-align: top;
                            }
                            th {
                                background: rgb(255 255 255 / 0.06);
                                color: #f8fafc;
                            }
                        `,
                        setup: (editor) => {
                            editor.on('init change input undo redo setcontent', () => {
                                setClientError(editor);
                            });

                            editor.on('init FullscreenStateChanged remove', () => {
                                scheduleFullscreenLayoutStateSync();
                            });
                        },
                    });
                });

                document.querySelectorAll('form').forEach((form) => {
                    form.addEventListener('submit', (event) => {
                        if (typeof tinymce === 'undefined') {
                            return;
                        }

                        tinymce.triggerSave();

                        const requiredEditors = form.querySelectorAll('textarea[data-tinymce-editor][data-tinymce-required="true"]');

                        for (const textarea of requiredEditors) {
                            const editor = tinymce.get(textarea.id);

                            if (!editor) {
                                continue;
                            }

                            const html = editor.getContent({format: 'html'}).trim();
                            const text = editor.getContent({format: 'text'}).replace(/\u00a0/g, ' ').trim();
                            const hasRichContent = /<(img|table|video|iframe|embed|object|audio)\b/i.test(html);
                            const clientErrorElement = document.getElementById(`${textarea.id}-client-error`);
                            const isEmpty = text === '' && !hasRichContent;

                            if (clientErrorElement) {
                                clientErrorElement.textContent = isEmpty ? 'Field ini wajib diisi.' : '';
                                clientErrorElement.classList.toggle('hidden', !isEmpty);
                            }

                            editor.getContainer()?.classList.toggle('sg-tinymce-invalid', isEmpty);

                            if (isEmpty) {
                                event.preventDefault();
                                editor.focus();
                                break;
                            }
                        }
                    });
                });
            });
        </script>
    @endpush
@endonce

<div class="space-y-2">
    <textarea id="{{ $id }}" name="{{ $name }}" rows="{{ $rows }}" data-tinymce-editor
        data-tinymce-height="{{ $height }}" data-tinymce-placeholder="{{ $placeholder }}"
        data-tinymce-required="{{ $required ? 'true' : 'false' }}"
        @if ($describedBy !== '') aria-describedby="{{ $describedBy }}" @endif
        @if ($required) aria-required="true" @endif
        {{ $attributes->merge(['class' => $baseClasses . ' ' . $stateClasses]) }}>{{ $value }}</textarea>

    @if ($helper)
        <p id="{{ $helperId }}" class="text-xs text-smoke">{{ $helper }}</p>
    @endif

    @if ($required)
        <p id="{{ $clientErrorId }}" class="hidden text-sm text-red-300"></p>
    @endif

    @error($fieldName)
        <p id="{{ $errorId }}" class="text-sm text-red-300">{{ $message }}</p>
    @enderror
</div>
