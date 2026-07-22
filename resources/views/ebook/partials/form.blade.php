@php($ebook = $ebook ?? null)
@php($ebookCategory = $ebookCategory ?? $ebook?->category)
@php($currentImageUrl = $ebook?->image_url)

@if ($errors->any())
    <div class="flex items-center gap-3 rounded-xl border border-red-500/30 bg-red-950/40 px-4 py-3 text-sm text-red-200 shadow-lg">
        <i class="fa-solid fa-triangle-exclamation text-base text-red-400"></i>
        <div>
            <p class="font-medium text-red-300">Terdapat kesalahan pengisian:</p>
            <p class="text-xs text-red-200/80">{{ $errors->first() }}</p>
        </div>
    </div>
@endif

<div class="grid gap-6 lg:grid-cols-[1fr_360px]">

    {{-- LEFT COLUMN: Main Info & Description --}}
    <div class="space-y-6">
        <div class="rounded-2xl border border-white/8 bg-white/3 p-6 space-y-5">
            {{-- Header --}}
            <div class="border-b border-white/6 pb-4">
                <h3 class="text-base font-semibold text-white">Detail Ebook</h3>
                <p class="mt-0.5 text-xs text-smoke">Isi judul dan deskripsi lengkap dokumen ebook.</p>
            </div>

            {{-- Title Input --}}
            <div>
                <label for="title" class="mb-2 block text-xs font-semibold uppercase tracking-[0.16em] text-smoke">
                    Judul Ebook <span class="text-gold-soft">*</span>
                </label>
                <input type="text" id="title" name="title" value="{{ old('title', $ebook?->title) }}"
                    class="w-full rounded-xl border bg-onyx px-4 py-3 text-sm text-champagne placeholder:text-smoke/40 focus:border-gold/35 focus:outline-none focus:ring-2 focus:ring-gold/12 transition-colors {{ $errors->has('title') ? 'border-red-400/60' : 'border-white/8' }}"
                    placeholder="Contoh: Panduan Dasar Trading Multilateral 2026" required>
                @error('title')
                    <p class="mt-1.5 text-xs font-medium text-red-300">{{ $message }}</p>
                @enderror
            </div>

            {{-- Category Pill Display --}}
            <div>
                <span class="mb-2 block text-xs font-semibold uppercase tracking-[0.16em] text-smoke">
                    Kategori Ebook
                </span>
                <div class="inline-flex items-center gap-2 rounded-xl border border-gold/25 bg-gold/10 px-4 py-2.5 text-sm font-semibold text-gold-soft">
                    <i class="fa-solid fa-folder-open text-xs"></i>
                    {{ $ebookCategory?->name ?? '-' }}
                </div>
            </div>

            {{-- Description TinyMCE Editor --}}
            <div>
                <label for="description" class="mb-2 block text-xs font-semibold uppercase tracking-[0.16em] text-smoke">
                    Deskripsi Ebook <span class="text-gold-soft">*</span>
                </label>
                <x-forms.tinymce-editor id="description" name="description" :value="old('description', $ebook?->description)"
                    :height="360" placeholder="Tuliskan deskripsi ringkas isi ebook ini..." required
                    helper="Gunakan toolbar TinyMCE untuk menyusun ringkasan atau poin-poin utama ebook." />
            </div>
        </div>
    </div>

    {{-- RIGHT COLUMN: Attachments & Previews --}}
    <div class="space-y-6">

        {{-- Cover Image Upload Card --}}
        <div class="rounded-2xl border border-white/8 bg-white/3 p-5 space-y-4">
            <div>
                <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-smoke/70">Cover Image</p>
                <p class="mt-0.5 text-xs text-smoke/80">Gambar sampul ebook (JPG, PNG, WebP, AVIF).</p>
            </div>

            <div>
                <input type="file" id="image" name="image"
                    accept=".jpg,.jpeg,.png,.webp,.avif,image/jpeg,image/png,image/webp,image/avif"
                    class="block w-full rounded-xl border bg-onyx px-3.5 py-2.5 text-xs text-champagne file:mr-3 file:rounded-lg file:border-0 file:bg-white/10 file:px-3 file:py-1.5 file:text-xs file:font-semibold file:text-champagne hover:file:bg-gold hover:file:text-obsidian focus:border-gold/35 focus:outline-none focus:ring-2 focus:ring-gold/12 transition-all cursor-pointer {{ $errors->has('image') ? 'border-red-400/60' : 'border-white/8' }}">
                @error('image')
                    <p class="mt-1.5 text-xs font-medium text-red-300">{{ $message }}</p>
                @enderror
            </div>

            {{-- Current Image Preview --}}
            @if ($currentImageUrl)
                <div class="rounded-xl border border-white/8 bg-onyx p-3 space-y-2">
                    <p class="text-[10px] font-semibold uppercase tracking-[0.16em] text-smoke/60">Cover Saat Ini</p>
                    <div class="overflow-hidden rounded-lg border border-white/8">
                        <img src="{{ $currentImageUrl }}" alt="{{ $ebook->title }}" class="h-44 w-full object-cover">
                    </div>
                    <p class="break-all font-mono text-[10px] text-smoke/70" title="{{ $ebook->image }}">{{ $ebook->image }}</p>
                </div>
            @else
                <div class="flex min-h-32 flex-col items-center justify-center gap-2 rounded-xl border border-dashed border-white/12 bg-onyx/50 p-4 text-center">
                    <i class="fa-solid fa-image text-lg text-smoke/40"></i>
                    <p class="text-xs text-smoke/50">Belum ada cover yang diunggah.</p>
                </div>
            @endif
        </div>

        {{-- PDF File Upload Card --}}
        <div class="rounded-2xl border border-white/8 bg-white/3 p-5 space-y-4">
            <div>
                <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-smoke/70">File Dokumen PDF</p>
                <p class="mt-0.5 text-xs text-smoke/80">Berkas PDF ebook (maksimal 20 MB).</p>
            </div>

            <div>
                <input type="file" id="file" name="file" accept=".pdf,application/pdf"
                    class="block w-full rounded-xl border bg-onyx px-3.5 py-2.5 text-xs text-champagne file:mr-3 file:rounded-lg file:border-0 file:bg-white/10 file:px-3 file:py-1.5 file:text-xs file:font-semibold file:text-champagne hover:file:bg-gold hover:file:text-obsidian focus:border-gold/35 focus:outline-none focus:ring-2 focus:ring-gold/12 transition-all cursor-pointer {{ $errors->has('file') ? 'border-red-400/60' : 'border-white/8' }}">
                @error('file')
                    <p class="mt-1.5 text-xs font-medium text-red-300">{{ $message }}</p>
                @enderror
            </div>

            {{-- Current File Status --}}
            @if ($ebook?->file_url)
                <div class="rounded-xl border border-white/8 bg-onyx p-3.5 space-y-2">
                    <p class="text-[10px] font-semibold uppercase tracking-[0.16em] text-smoke/60">File PDF Aktif</p>
                    <a href="{{ $ebook->file_url }}" target="_blank" rel="noreferrer"
                        class="inline-flex items-center gap-2 rounded-lg border border-white/10 bg-white/5 px-3.5 py-2 text-xs font-medium text-white transition-all hover:bg-white/10">
                        <i class="fa-solid fa-file-pdf text-red-400 text-sm"></i>
                        Unduh PDF Aktif
                        <i class="fa-solid fa-download text-[10px] text-smoke"></i>
                    </a>
                    <p class="break-all font-mono text-[10px] text-smoke/70" title="{{ $ebook->file }}">{{ $ebook->file }}</p>
                </div>
            @endif
        </div>

    </div>
</div>

{{-- Bottom Action Buttons --}}
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
