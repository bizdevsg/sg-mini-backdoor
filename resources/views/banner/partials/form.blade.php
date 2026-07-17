@php($banner = $banner ?? null)
@php($currentImageUrl = $banner?->image_url)

@if ($errors->any())
    <div class="rounded-xl border border-red-500/30 bg-red-950/30 px-4 py-3 text-sm text-red-200">
        {{ $errors->first() }}
    </div>
@endif

<div class="grid gap-6 xl:grid-cols-[1.15fr_0.85fr]">
    <div class="rounded-2xl border border-white/8 bg-white/4 p-6">
        <div class="grid gap-5">
            <div>
                <label for="title" class="mb-2 block text-sm font-medium text-white">Title</label>
                <input type="text" id="title" name="title"
                    value="{{ old('title', $banner?->title) }}"
                    placeholder="Contoh: Hero Homepage Juli"
                    class="w-full rounded-lg border bg-onyx px-4 py-3 text-champagne placeholder:text-smoke focus:border-gold/30 focus:outline-none focus:ring-2 focus:ring-gold/15 {{ $errors->has('title') ? 'border-red-400/60' : 'border-white/8' }}"
                    required>
                <p class="mt-2 text-xs text-smoke">
                    Wajib diisi. Slug akan dibuat otomatis berdasarkan title.
                </p>
                @error('title')
                    <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="sort_order" class="mb-2 block text-sm font-medium text-white">Urutan tampil slide</label>
                <input type="number" id="sort_order" name="sort_order" min="0" max="9999"
                    value="{{ old('sort_order', $banner?->sort_order ?? 0) }}"
                    class="w-full rounded-lg border bg-onyx px-4 py-3 text-champagne placeholder:text-smoke focus:border-gold/30 focus:outline-none focus:ring-2 focus:ring-gold/15 {{ $errors->has('sort_order') ? 'border-red-400/60' : 'border-white/8' }}">
                <p class="mt-2 text-xs text-smoke">Angka lebih kecil akan tampil lebih dulu di carousel.</p>
                @error('sort_order')
                    <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="image" class="mb-2 block text-sm font-medium text-white">Image banner</label>
                <input type="file" id="image" name="image"
                    accept=".jpg,.jpeg,.png,.webp,.avif,image/jpeg,image/png,image/webp,image/avif"
                    class="block w-full rounded-lg border bg-onyx px-4 py-3 text-sm text-champagne file:mr-4 file:rounded-md file:border-0 file:bg-white file:px-3 file:py-2 file:text-sm file:font-medium file:text-obsidian hover:file:bg-slate-200 focus:border-gold/30 focus:outline-none focus:ring-2 focus:ring-gold/15 {{ $errors->has('image') ? 'border-red-400/60' : 'border-white/8' }}">
                <p class="mt-2 text-xs text-smoke">
                    Upload JPG, PNG, WebP, atau AVIF. Gambar akan dioptimasi dan disimpan sebagai AVIF atau WebP.
                </p>
                @error('image')
                    <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="terms_and_conditions" class="mb-2 block text-sm font-medium text-white">Syarat dan Ketentuan</label>
                <x-forms.tinymce-editor id="terms_and_conditions" name="terms_and_conditions"
                    :value="old('terms_and_conditions', $banner?->terms_and_conditions)" :height="360"
                    placeholder="Masukkan syarat dan ketentuan banner jika diperlukan."
                    helper="Editor TinyMCE aktif. Gunakan list, link, tabel, atau format teks bila diperlukan." />
            </div>
        </div>
    </div>

    <div class="space-y-6">
        <div class="rounded-2xl border border-white/8 bg-white/4 p-6">
            <div class="flex items-start gap-3">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" id="is_active" name="is_active" value="1" @checked(old('is_active', $banner?->is_active ?? true))
                    class="mt-1 h-4 w-4 rounded border-white/15 bg-onyx text-gold-soft focus:ring-gold/30">
                <div>
                    <label for="is_active" class="text-sm font-medium text-white">Banner aktif</label>
                    <p class="mt-1 text-sm leading-6 text-smoke">
                        Hanya image yang aktif yang akan ikut muncul di carousel frontend.
                    </p>
                </div>
            </div>
            @error('is_active')
                <p class="mt-3 text-sm text-red-300">{{ $message }}</p>
            @enderror
        </div>

        <div class="rounded-2xl border border-white/8 bg-white/4 p-6">
            <p class="text-xs font-medium uppercase tracking-[0.18em] text-smoke">Preview image</p>

            @if ($currentImageUrl)
                <div class="mt-4 overflow-hidden rounded-2xl border border-white/8 bg-onyx">
                    <img src="{{ $currentImageUrl }}" alt="Banner {{ $banner->id }}" class="h-64 w-full object-cover">
                </div>
                <p class="mt-3 break-all text-xs text-smoke">{{ $banner->image }}</p>
            @else
                <div
                    class="mt-4 flex min-h-64 items-center justify-center rounded-2xl border border-dashed border-white/12 bg-onyx px-4 text-center text-sm text-smoke">
                    Belum ada image banner yang tersimpan.
                </div>
            @endif
        </div>

        <div class="rounded-2xl border border-gold/20 bg-gold/10 p-6 text-sm leading-7 text-gold-soft">
            Modul ini menyimpan title, slug otomatis, image carousel, syarat dan ketentuan, status aktif, dan urutan tampil.
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
