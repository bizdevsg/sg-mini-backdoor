@php($informasi = $informasi ?? null)
@php($currentImageUrl = $informasi?->image_url)

@if ($errors->any())
    <div class="rounded-xl border border-red-500/30 bg-red-950/30 px-4 py-3 text-sm text-red-200">
        {{ $errors->first() }}
    </div>
@endif

@once
    @push('styles')
        <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/quill-table-better@1/dist/quill-table-better.css" rel="stylesheet">

        <style>
            .ql-toolbar.ql-snow {
                border: 1px solid rgb(255 255 255 / 0.08);
                background: #12151c;
                border-radius: 1rem 1rem 0 0;
            }

            .ql-container.ql-snow {
                border: 1px solid rgb(255 255 255 / 0.08);
                border-top: 0;
                background: #171b23;
                color: #e7eaf0;
                border-radius: 0 0 1rem 1rem;
            }

            .ql-editor {
                min-height: 20rem;
                color: #e7eaf0;
            }

            .ql-editor.ql-blank::before {
                color: #8f98aa;
                font-style: normal;
            }

            .ql-snow .ql-stroke {
                stroke: #e7eaf0;
            }

            .ql-snow .ql-fill,
            .ql-snow .ql-picker {
                fill: #e7eaf0;
                color: #e7eaf0;
            }

            .ql-snow .ql-picker-label,
            .ql-snow .ql-picker-options {
                border-color: rgb(255 255 255 / 0.08);
                background: #171b23;
                color: #e1c980;
            }

            .ql-snow .ql-picker.ql-expanded .ql-picker-label,
            .ql-snow .ql-picker.ql-expanded .ql-picker-options,
            .ql-snow .ql-tooltip {
                border-color: rgb(255 255 255 / 0.08);
                background: #171b23;
                color: #e7eaf0;
            }

            .ql-snow .ql-tooltip input[type='text'] {
                border: 1px solid rgb(255 255 255 / 0.08);
                background: #12151c;
                color: #e7eaf0;
            }

            .ql-snow .ql-tooltip a.ql-action,
            .ql-snow .ql-tooltip a.ql-remove {
                color: #e1c980;
            }

            .ql-snow .ql-toolbar button:hover,
            .ql-snow .ql-toolbar button:focus,
            .ql-snow .ql-toolbar button.ql-active,
            .ql-snow .ql-toolbar .ql-picker-label:hover,
            .ql-snow .ql-toolbar .ql-picker-label.ql-active,
            .ql-snow .ql-toolbar .ql-picker-item:hover,
            .ql-snow .ql-toolbar .ql-picker-item.ql-selected {
                color: #e1c980;
            }

            .ql-snow .ql-toolbar button:hover .ql-stroke,
            .ql-snow .ql-toolbar button:focus .ql-stroke,
            .ql-snow .ql-toolbar button.ql-active .ql-stroke,
            .ql-snow .ql-toolbar .ql-picker-label:hover .ql-stroke,
            .ql-snow .ql-toolbar .ql-picker-label.ql-active .ql-stroke {
                stroke: #e1c980;
            }

            .ql-snow .ql-toolbar button:hover .ql-fill,
            .ql-snow .ql-toolbar button:focus .ql-fill,
            .ql-snow .ql-toolbar button.ql-active .ql-fill,
            .ql-snow .ql-toolbar .ql-picker-label:hover .ql-fill,
            .ql-snow .ql-toolbar .ql-picker-label.ql-active .ql-fill {
                fill: #e1c980;
            }

            .ql-editor a {
                color: #e1c980;
            }

            .ql-editor blockquote {
                border-left: 4px solid #caa64a;
                padding-left: 1rem;
                color: #e7eaf0;
            }

            .ql-editor code,
            .ql-editor pre {
                background: #12151c;
                color: #e7eaf0;
            }

            .ql-editor table {
                width: 100%;
                border-collapse: collapse;
            }

            .ql-editor th,
            .ql-editor td {
                border: 1px solid rgb(255 255 255 / 0.12);
                padding: 0.75rem;
                vertical-align: top;
            }

            .ql-editor th {
                background: rgb(255 255 255 / 0.06);
                color: #f8fafc;
                font-weight: 600;
            }

            .ql-table-menus-container,
            .ql-table-select-container,
            .ql-table-dropdown-list,
            .ql-table-dropdown-properties-list,
            .ql-table-properties-form,
            .ql-table-color-container .color-picker .color-picker-select,
            .ql-table-color-container .color-picker .color-picker-palette {
                border: 1px solid rgb(255 255 255 / 0.12);
                background: #12151c;
                box-shadow: 0 20px 45px rgb(0 0 0 / 0.45);
            }

            .ql-table-properties-form .properties-form-header,
            .ql-table-dropdown-label,
            .ql-table-dropdown-properties-label,
            .ql-table-select-container .ql-table-select-label {
                color: #f8fafc;
            }

            .ql-table-properties-form .properties-form-header,
            .ql-table-divider {
                border-color: rgb(255 255 255 / 0.08);
            }

            .label-field-view-input-wrapper>label {
                background: #12151c;
                color: #caa64a;
            }

            .ql-table-input,
            .ql-table-color-container,
            .ql-table-color-container .label-field-view-color .property-input,
            .ql-table-properties-form .property-input,
            .ql-table-dropdown-properties,
            .ql-table-properties-form .properties-form-row .ql-table-check-container,
            .ql-table-color-container .color-picker,
            .ql-table-color-container .color-picker .color-button {
                border-color: rgb(255 255 255 / 0.12);
                background: #171b23;
                color: #e7eaf0;
            }

            .ql-table-dropdown,
            .ql-table-dropdown-properties,
            .ql-table-dropdown-list li,
            .ql-table-dropdown-properties-list li,
            .ql-table-properties-form .properties-form-row .ql-table-check-container .ql-table-tooltip-hover,
            .ql-table-color-container .color-picker .color-picker-select .erase-container,
            .ql-table-properties-form .properties-form-action-row>button {
                color: #e7eaf0;
            }

            .ql-table-dropdown:hover,
            .ql-table-dropdown-properties:hover,
            .ql-table-dropdown-list li:hover,
            .ql-table-dropdown-properties-list li:hover,
            .ql-table-properties-form .properties-form-row .ql-table-check-container .ql-table-tooltip-hover:hover,
            .ql-table-color-container .color-picker .color-picker-select .erase-container:hover,
            .ql-table-properties-form .properties-form-action-row>button:hover {
                background: rgb(255 255 255 / 0.06);
            }

            .ql-table-input:focus,
            .ql-table-color-container .label-field-view-color .property-input:focus,
            .ql-table-properties-form .property-input:focus,
            .ql-table-input-focus,
            .ql-table-color-container .color-picker .color-picker-select>.erase-container,
            .ql-table-selected,
            .ql-table-properties-form .ql-table-dropdown-selected,
            .ql-table-properties-form .ql-table-color-selected {
                border-color: #caa64a;
                box-shadow: 0 0 0 3px rgb(202 166 74 / 0.18);
            }

            .ql-table-properties-form .properties-form-row .ql-table-check-container .ql-table-btns-checked {
                background: rgb(202 166 74 / 0.15);
            }

            .ql-table-properties-form .properties-form-row .ql-table-check-container .ql-table-btns-checked>svg path {
                stroke: #e1c980;
            }

            .ql-table-color-container .color-picker .color-picker-select .erase-container>button,
            .ql-table-properties-form .properties-form-action-row>button {
                background: transparent;
            }

            .ql-table-properties-form .properties-form-action-row>button:first-child {
                color: #e1c980;
            }

            .ql-table-properties-form .properties-form-action-row>button:last-child {
                color: #8f98aa;
            }

            .ql-table-tooltip::before {
                border-bottom-color: #12151c !important;
            }

            .sg-quill-wrapper {
                display: none;
            }

            .sg-quill-wrapper.is-ready {
                display: block;
            }
        </style>
    @endpush

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/quill-table-better@1/dist/quill-table-better.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const wrapperElement = document.querySelector('[data-quill-wrapper]');
                const editorElement = document.querySelector('[data-quill-editor]');
                const inputElement = document.querySelector('[data-quill-input]');
                const formElement = inputElement?.closest('form');

                if (!wrapperElement || !editorElement || !inputElement || typeof Quill === 'undefined') {
                    return;
                }

                try {
                    if (typeof QuillTableBetter !== 'undefined') {
                        Quill.register({
                            'modules/table-better': QuillTableBetter
                        }, true);
                    }

                    const quill = new Quill(editorElement, {
                        theme: 'snow',
                        placeholder: 'Tulis isi pengumuman di sini.',
                        modules: {
                            table: false,
                            toolbar: [
                                [{
                                    header: [1, 2, 3, 4, false]
                                }],
                                [{
                                    font: []
                                }],
                                [{
                                    size: ['small', false, 'large', 'huge']
                                }],
                                ['bold', 'italic', 'underline', 'strike'],
                                [{
                                    color: []
                                }, {
                                    background: []
                                }],
                                [{
                                    script: 'super'
                                }, {
                                    script: 'sub'
                                }],
                                [{
                                    list: 'ordered'
                                }, {
                                    list: 'bullet'
                                }],
                                [{
                                    indent: '-1'
                                }, {
                                    indent: '+1'
                                }],
                                [{
                                    align: []
                                }],
                                ['blockquote', 'code-block'],
                                ['link', 'image', 'video'],
                                ['table-better'],
                                ['clean'],
                            ],
                            'table-better': typeof QuillTableBetter !== 'undefined' ? {
                                language: 'en_US',
                                menus: ['column', 'row', 'merge', 'table', 'cell', 'wrap', 'copy', 'delete'],
                                toolbarTable: true,
                                toolbarButtons: {
                                    whiteList: ['bold', 'italic', 'underline', 'strike', 'size', 'color',
                                        'background', 'font', 'list', 'header', 'align', 'link', 'image'
                                    ],
                                    singleWhiteList: ['link', 'image'],
                                },
                            } : {},
                            keyboard: typeof QuillTableBetter !== 'undefined' ? {
                                bindings: QuillTableBetter.keyboardBindings,
                            } : {},
                        },
                    });

                    if (inputElement.value.trim() !== '') {
                        const delta = quill.clipboard.convert({
                            html: inputElement.value
                        });

                        quill.updateContents(delta, Quill.sources.SILENT);
                    }

                    const syncEditor = () => {
                        inputElement.value = quill.root.innerHTML === '<p><br></p>' ? '' : quill.root.innerHTML;
                    };

                    quill.root.setAttribute('spellcheck', 'false');
                    quill.root.setAttribute('data-gramm', 'false');
                    quill.on('text-change', syncEditor);
                    formElement?.addEventListener('submit', syncEditor);

                    wrapperElement.classList.add('is-ready');
                    inputElement.classList.add('hidden');
                    syncEditor();
                } catch (error) {
                    console.error('Quill init failed:', error);
                }
            });
        </script>
    @endpush
@endonce

<div class="space-y-6">
    <div class="rounded-2xl border border-white/8 bg-white/4 p-6">
        <div class="grid gap-5">
            <div>
                <label for="title" class="mb-2 block text-sm font-medium text-white">Judul</label>
                <input type="text" id="title" name="title" value="{{ old('title', $informasi?->title) }}"
                    class="w-full rounded-lg border bg-onyx px-4 py-3 text-champagne placeholder:text-smoke focus:border-gold/30 focus:outline-none focus:ring-2 focus:ring-gold/15 {{ $errors->has('title') ? 'border-red-400/60' : 'border-white/8' }}"
                    placeholder="Contoh: Jadwal maintenance sistem" required>
                @error('title')
                    <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="content" class="mb-2 block text-sm font-medium text-white">Konten</label>
                <div data-quill-wrapper
                    class="sg-quill-wrapper {{ $errors->has('content') ? 'rounded-2xl border border-red-400/60 overflow-hidden' : '' }}">
                    <div data-quill-editor></div>
                </div>
                <textarea id="content" name="content" data-quill-input rows="12"
                    class="w-full rounded-lg border bg-onyx px-4 py-3 text-champagne placeholder:text-smoke focus:border-gold/30 focus:outline-none focus:ring-2 focus:ring-gold/15 {{ $errors->has('content') ? 'border-red-400/60' : 'border-white/8' }}"
                    placeholder="Tulis isi pengumuman di sini." required>{{ old('content', $informasi?->content) }}</textarea>
                <p class="mt-2 text-xs text-smoke">Kalau editor berhasil dimuat, textarea ini otomatis berubah jadi rich text editor lengkap dengan tombol tabel.</p>
                @error('content')
                    <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="image" class="mb-2 block text-sm font-medium text-white">Image</label>
                <input type="file" id="image" name="image"
                    accept=".jpg,.jpeg,.png,.webp,.avif,image/jpeg,image/png,image/webp,image/avif"
                    class="block w-full rounded-lg border bg-onyx px-4 py-3 text-sm text-champagne file:mr-4 file:rounded-md file:border-0 file:bg-white file:px-3 file:py-2 file:text-sm file:font-medium file:text-obsidian hover:file:bg-slate-200 focus:border-gold/30 focus:outline-none focus:ring-2 focus:ring-gold/15 {{ $errors->has('image') ? 'border-red-400/60' : 'border-white/8' }}">
                <p class="mt-2 text-xs text-smoke">Upload JPG, PNG, WebP, atau AVIF. File akan disimpan sebagai AVIF atau WebP.</p>
                @error('image')
                    <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                @enderror
            </div>

            @if ($currentImageUrl)
                <div class="rounded-xl border border-white/8 bg-onyx p-4">
                    <p class="text-xs font-medium uppercase tracking-[0.18em] text-smoke">Image saat ini</p>
                    <div class="mt-4 overflow-hidden rounded-xl border border-white/8">
                        <img src="{{ $currentImageUrl }}" alt="{{ $informasi->title }}" class="h-48 w-full object-cover">
                    </div>
                    <p class="mt-3 break-all text-xs text-smoke">{{ $informasi->image }}</p>
                </div>
            @endif
        </div>
    </div>
</div>

<div class="flex flex-col gap-3 sm:flex-row sm:justify-end">
    <a href="{{ $cancelUrl }}"
        class="inline-flex items-center justify-center rounded-lg border border-white/8 bg-white/5 px-5 py-3 text-sm font-medium text-white transition-colors hover:bg-white/10">
        Batal
    </a>
    <button type="submit"
        class="inline-flex items-center justify-center rounded-lg bg-white px-5 py-3 text-sm font-medium text-obsidian transition-colors hover:bg-slate-200">
        {{ $submitLabel }}
    </button>
</div>
