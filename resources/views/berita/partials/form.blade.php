@php($berita = $berita ?? null)
@php($beritaCategory = $beritaCategory ?? $berita?->category)
@php($currentImageUrl = $berita?->image_url)
@php($authorInitials = ['MRV', 'ASD', 'YDS', 'ARL', 'CP', 'ALG', 'SRH', 'SNM'])

@if ($errors->any())
    <div
        class="flex items-center gap-3 rounded-xl border border-red-500/30 bg-red-950/40 px-4 py-3 text-sm text-red-200 shadow-lg">
        <i class="fa-solid fa-triangle-exclamation text-base text-red-400"></i>
        <div>
            <p class="font-medium text-red-300">Terdapat kesalahan pengisian:</p>
            <p class="text-xs text-red-200/80">{{ $errors->first() }}</p>
        </div>
    </div>
@endif

<div class="grid gap-6 lg:grid-cols-[1fr_360px]">
    <div class="space-y-6">
        <div class="rounded-2xl border border-white/8 bg-white/3 p-6 space-y-5">
            <div class="border-b border-white/6 pb-4">
                <h3 class="text-base font-semibold text-white">Detail Berita</h3>
                <p class="mt-0.5 text-xs text-smoke">Lengkapi metadata, judul bilingual, dan konten bilingual berita.</p>
            </div>


            <div class="w-full">
                <span class="mb-2 block text-xs font-semibold uppercase tracking-[0.16em] text-smoke">Kategori
                    Berita</span>
                <div
                    class="inline-flex w-full items-center gap-2 rounded-xl border border-gold/25 bg-gold/10 px-4 py-2.5 text-sm font-semibold text-gold-soft">
                    <i class="fa-solid fa-folder-open text-xs"></i>
                    {{ $beritaCategory?->name ?? '-' }}
                </div>
            </div>

            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <label for="source"
                        class="mb-2 block text-xs font-semibold uppercase tracking-[0.16em] text-smoke">
                        Source <span class="text-gold-soft">*</span>
                    </label>
                    <input type="text" id="source" name="source" value="{{ old('source', $berita?->source) }}"
                        class="w-full rounded-xl border bg-onyx px-4 py-3 text-sm text-champagne placeholder:text-smoke/40 focus:border-gold/35 focus:outline-none focus:ring-2 focus:ring-gold/12 {{ $errors->has('source') ? 'border-red-400/60' : 'border-white/8' }}"
                        placeholder="Contoh: Reuters" required>
                    <x-forms.field-error field="source" />
                </div>


                <div>
                    <label for="author"
                        class="mb-2 block text-xs font-semibold uppercase tracking-[0.16em] text-smoke">
                        Author <span class="text-gold-soft">*</span>
                    </label>
                    <select id="author" name="author"
                        class="w-full rounded-xl border bg-onyx px-4 py-3 text-sm text-champagne focus:border-gold/35 focus:outline-none focus:ring-2 focus:ring-gold/12 {{ $errors->has('author') ? 'border-red-400/60' : 'border-white/8' }}"
                        required>
                        <option value="" @selected(old('author', $berita?->author) === '')>Pilih inisial author</option>
                        @foreach ($authorInitials as $authorInitial)
                            <option value="{{ $authorInitial }}" @selected(old('author', $berita?->author) === $authorInitial)>
                                {{ $authorInitial }}
                            </option>
                        @endforeach
                    </select>
                    <x-forms.field-error field="author" />
                </div>
            </div>

            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <label for="title_id"
                        class="mb-2 block text-xs font-semibold uppercase tracking-[0.16em] text-smoke">
                        Judul ID <span class="text-gold-soft">*</span>
                    </label>
                    <input type="text" id="title_id" name="title_id"
                        value="{{ old('title_id', $berita?->title_id) }}"
                        class="w-full rounded-xl border bg-onyx px-4 py-3 text-sm text-champagne placeholder:text-smoke/40 focus:border-gold/35 focus:outline-none focus:ring-2 focus:ring-gold/12 {{ $errors->has('title_id') ? 'border-red-400/60' : 'border-white/8' }}"
                        placeholder="Contoh: Update Pergerakan Emas Hari Ini" required>
                    <x-forms.field-error field="title_id" />
                </div>
                <div>
                    <label for="title_en"
                        class="mb-2 block text-xs font-semibold uppercase tracking-[0.16em] text-smoke">
                        Judul EN <span class="text-gold-soft">*</span>
                    </label>
                    <input type="text" id="title_en" name="title_en"
                        value="{{ old('title_en', $berita?->title_en) }}"
                        class="w-full rounded-xl border bg-onyx px-4 py-3 text-sm text-champagne placeholder:text-smoke/40 focus:border-gold/35 focus:outline-none focus:ring-2 focus:ring-gold/12 {{ $errors->has('title_en') ? 'border-red-400/60' : 'border-white/8' }}"
                        placeholder="Example: Gold Movement Update Today" required>
                    <x-forms.field-error field="title_en" />
                </div>
            </div>

            <div>
                <label for="content_id" class="mb-2 block text-xs font-semibold uppercase tracking-[0.16em] text-smoke">
                    Konten ID <span class="text-gold-soft">*</span>
                </label>
                <x-forms.tinymce-editor id="content_id" name="content_id" :value="old('content_id', $berita?->content_id)" :height="340"
                    placeholder="Tuliskan materi berita versi Indonesia..." required
                    helper="Gunakan editor untuk menyusun konten berita versi Indonesia." />
            </div>

            <div>
                <label for="content_en" class="mb-2 block text-xs font-semibold uppercase tracking-[0.16em] text-smoke">
                    Konten EN <span class="text-gold-soft">*</span>
                </label>
                <x-forms.tinymce-editor id="content_en" name="content_en" :value="old('content_en', $berita?->content_en)" :height="340"
                    placeholder="Write the English news content..." required
                    helper="Use the editor for the English version of the news content." />
            </div>
        </div>
    </div>

    <div class="space-y-6">
        <div class="rounded-2xl border border-white/8 bg-white/3 p-5 space-y-4">
            <div>
                <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-smoke/70">Gambar Sampul</p>
                <p class="mt-0.5 text-xs text-smoke/80">Lampirkan gambar pendukung berita.</p>
            </div>

            <input type="file" id="image" name="image"
                accept=".jpg,.jpeg,.png,.webp,.avif,image/jpeg,image/png,image/webp,image/avif"
                class="block w-full rounded-xl border bg-onyx px-3.5 py-2.5 text-xs text-champagne file:mr-3 file:rounded-lg file:border-0 file:bg-white/10 file:px-3 file:py-1.5 file:text-xs file:font-semibold file:text-champagne hover:file:bg-gold hover:file:text-obsidian {{ $errors->has('image') ? 'border-red-400/60' : 'border-white/8' }}">
            <x-forms.field-error field="image" />

            @if ($currentImageUrl)
                <div class="rounded-xl border border-white/8 bg-onyx p-3 space-y-2">
                    <p class="text-[10px] font-semibold uppercase tracking-[0.16em] text-smoke/60">Gambar Saat Ini</p>
                    <div class="overflow-hidden rounded-lg border border-white/8">
                        <img src="{{ $currentImageUrl }}" alt="{{ $berita->title_id }}"
                            class="h-44 w-full object-cover">
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<div class="flex items-center justify-end gap-3 border-t border-white/6 pt-6">
    <a href="{{ $cancelUrl }}"
        class="inline-flex items-center justify-center gap-2 rounded-xl border border-white/10 bg-white/5 px-5 py-2.5 text-sm font-medium text-smoke transition-all duration-200 hover:border-white/18 hover:bg-white/8 hover:text-white">
        Batal
    </a>
    <button type="submit"
        class="inline-flex items-center justify-center gap-2 rounded-xl bg-gold px-6 py-2.5 text-sm font-semibold text-obsidian shadow-[0_4px_18px_rgba(199,161,90,0.28)] transition-all duration-200 hover:bg-gold-soft hover:shadow-[0_6px_24px_rgba(199,161,90,0.4)]">
        <i class="fa-solid fa-check text-xs"></i>
        {{ $submitLabel }}
    </button>
</div>
