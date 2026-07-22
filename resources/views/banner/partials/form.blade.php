@php($banner = $banner ?? null)
@php($currentImageUrl = $banner?->image_url)

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

    {{-- LEFT COLUMN: Main Form Fields --}}
    <div class="space-y-6">
        <div class="rounded-2xl border border-white/8 bg-white/3 p-6 space-y-5">
            {{-- Section Title --}}
            <div class="border-b border-white/6 pb-4">
                <h3 class="text-base font-semibold text-white">Detail Banner</h3>
                <p class="mt-0.5 text-xs text-smoke">Lengkapi judul, urutan tampil, dan berkas gambar banner.</p>
            </div>

            {{-- Title Input --}}
            <div>
                <label for="title" class="mb-2 block text-xs font-semibold uppercase tracking-[0.16em] text-smoke">
                    Judul Banner <span class="text-gold-soft">*</span>
                </label>
                <div class="relative">
                    <input type="text" id="title" name="title"
                        value="{{ old('title', $banner?->title) }}"
                        placeholder="Contoh: Promo Special Summer 2026"
                        class="w-full rounded-xl border bg-onyx px-4 py-3 text-sm text-champagne placeholder:text-smoke/40 focus:border-gold/35 focus:outline-none focus:ring-2 focus:ring-gold/12 transition-colors {{ $errors->has('title') ? 'border-red-400/60' : 'border-white/8' }}"
                        required>
                </div>
                <p class="mt-2 text-[11px] text-smoke/70">
                    Wajib diisi. URL Slug akan dihasilkan secara otomatis berdasarkan judul banner.
                </p>
                @error('title')
                    <p class="mt-1.5 text-xs font-medium text-red-300">{{ $message }}</p>
                @enderror
            </div>

            {{-- Sort Order Input --}}
            <div>
                <label for="sort_order" class="mb-2 block text-xs font-semibold uppercase tracking-[0.16em] text-smoke">
                    Urutan Tampil Slide
                </label>
                <div class="relative max-w-xs">
                    <input type="number" id="sort_order" name="sort_order" min="0" max="9999"
                        value="{{ old('sort_order', $banner?->sort_order ?? 0) }}"
                        class="w-full rounded-xl border bg-onyx px-4 py-2.5 text-sm text-champagne placeholder:text-smoke/40 focus:border-gold/35 focus:outline-none focus:ring-2 focus:ring-gold/12 transition-colors {{ $errors->has('sort_order') ? 'border-red-400/60' : 'border-white/8' }}">
                </div>
                <p class="mt-2 text-[11px] text-smoke/70">Angka lebih kecil (cth: 0, 1, 2) akan ditampilkan lebih awal di carousel.</p>
                @error('sort_order')
                    <p class="mt-1.5 text-xs font-medium text-red-300">{{ $message }}</p>
                @enderror
            </div>

            {{-- Image File Input --}}
            <div>
                <label for="image" class="mb-2 block text-xs font-semibold uppercase tracking-[0.16em] text-smoke">
                    Berkas Gambar Banner {{ $banner ? '(Opsional jika tidak diganti)' : '*' }}
                </label>
                <input type="file" id="image" name="image"
                    accept=".jpg,.jpeg,.png,.webp,.avif,image/jpeg,image/png,image/webp,image/avif"
                    class="block w-full rounded-xl border bg-onyx px-4 py-3 text-xs text-champagne file:mr-4 file:rounded-lg file:border-0 file:bg-white/10 file:px-3 file:py-1.5 file:text-xs file:font-semibold file:text-champagne hover:file:bg-gold hover:file:text-obsidian focus:border-gold/35 focus:outline-none focus:ring-2 focus:ring-gold/12 transition-all cursor-pointer {{ $errors->has('image') ? 'border-red-400/60' : 'border-white/8' }}">
                <p class="mt-2 text-[11px] text-smoke/70">
                    Format: JPG, PNG, WebP, atau AVIF. Berkas akan otomatis dioptimasi untuk kinerja web terbaik.
                </p>
                @error('image')
                    <p class="mt-1.5 text-xs font-medium text-red-300">{{ $message }}</p>
                @enderror
            </div>

            {{-- Terms & Conditions TinyMCE --}}
            <div>
                <label for="terms_and_conditions" class="mb-2 block text-xs font-semibold uppercase tracking-[0.16em] text-smoke">
                    Syarat dan Ketentuan Banner
                </label>
                <x-forms.tinymce-editor id="terms_and_conditions" name="terms_and_conditions"
                    :value="old('terms_and_conditions', $banner?->terms_and_conditions)" :height="320"
                    placeholder="Masukkan syarat dan ketentuan banner jika ada..."
                    helper="Gunakan opsi format TinyMCE (list, link, bold, dll.) untuk menata dokumen syarat & ketentuan." />
            </div>
        </div>
    </div>

    {{-- RIGHT COLUMN: Status Toggle & Preview --}}
    <div class="space-y-6">

        {{-- Status Toggle Card --}}
        <div class="rounded-2xl border border-white/8 bg-white/3 p-5">
            <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-smoke/70">Status Publikasi</p>
            
            <div class="mt-3 flex items-start gap-3 rounded-xl border border-white/6 bg-onyx/60 p-4">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" id="is_active" name="is_active" value="1" @checked(old('is_active', $banner?->is_active ?? true))
                    class="mt-0.5 h-4 w-4 rounded border-white/20 bg-onyx text-gold focus:ring-gold/30 cursor-pointer">
                <div>
                    <label for="is_active" class="text-sm font-semibold text-white cursor-pointer select-none">
                        Tampilkan Banner (Aktif)
                    </label>
                    <p class="mt-1 text-xs leading-5 text-smoke/80">
                        Jika dicentang, banner akan langsung terlihat di slide carousel beranda frontend.
                    </p>
                </div>
            </div>
            @error('is_active')
                <p class="mt-2 text-xs font-medium text-red-300">{{ $message }}</p>
            @enderror
        </div>

        {{-- Image Preview Card --}}
        <div class="rounded-2xl border border-white/8 bg-white/3 p-5">
            <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-smoke/70">Preview Image</p>

            @if ($currentImageUrl)
                <div class="relative mt-3 overflow-hidden rounded-xl border border-white/10 bg-onyx group">
                    <img src="{{ $currentImageUrl }}" alt="Banner {{ $banner->id }}" class="h-52 w-full object-cover">
                    <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent p-3">
                        <p class="break-all font-mono text-[10px] text-champagne/90" title="{{ $banner->image }}">
                            {{ $banner->image }}
                        </p>
                    </div>
                </div>
            @else
                <div class="mt-3 flex min-h-52 flex-col items-center justify-center gap-2 rounded-xl border border-dashed border-white/12 bg-onyx/50 p-6 text-center">
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl border border-white/8 bg-white/4 text-smoke/50">
                        <i class="fa-solid fa-image text-lg"></i>
                    </div>
                    <p class="text-xs text-smoke/60">Belum ada gambar yang dipilih.</p>
                </div>
            @endif
        </div>

        {{-- Info Box --}}
        <div class="rounded-2xl border border-gold/20 bg-gold/8 p-5 text-xs leading-6 text-gold-soft/90">
            <div class="flex items-center gap-2 font-semibold text-gold-soft mb-1">
                <i class="fa-solid fa-circle-info"></i> Info Pengelolaan
            </div>
            Modul banner digunakan untuk mengatur gambar visual promosi utama pada beranda website. Pastikan rasio gambar sesuai standar.
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
