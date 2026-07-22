@php($informasi = $informasi ?? null)
@php($currentImageUrl = $informasi?->image_url)

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

    {{-- LEFT COLUMN: Title & Main Content --}}
    <div class="space-y-6">
        <div class="rounded-2xl border border-white/8 bg-white/3 p-6 space-y-5">
            {{-- Header --}}
            <div class="border-b border-white/6 pb-4">
                <h3 class="text-base font-semibold text-white">Informasi Utama</h3>
                <p class="mt-0.5 text-xs text-smoke">Tuliskan judul dan isi lengkap pengumuman resmi.</p>
            </div>

            {{-- Title Input --}}
            <div>
                <label for="title" class="mb-2 block text-xs font-semibold uppercase tracking-[0.16em] text-smoke">
                    Judul Pengumuman <span class="text-gold-soft">*</span>
                </label>
                <input type="text" id="title" name="title" value="{{ old('title', $informasi?->title) }}"
                    class="w-full rounded-xl border bg-onyx px-4 py-3 text-sm text-champagne placeholder:text-smoke/40 focus:border-gold/35 focus:outline-none focus:ring-2 focus:ring-gold/12 transition-colors {{ $errors->has('title') ? 'border-red-400/60' : 'border-white/8' }}"
                    placeholder="Contoh: Jadwal Maintenance Sistem & Layanan" required>
                @error('title')
                    <p class="mt-1.5 text-xs font-medium text-red-300">{{ $message }}</p>
                @enderror
            </div>

            {{-- TinyMCE Content --}}
            <div>
                <label for="content" class="mb-2 block text-xs font-semibold uppercase tracking-[0.16em] text-smoke">
                    Isi Konten <span class="text-gold-soft">*</span>
                </label>
                <x-forms.tinymce-editor id="content" name="content" :value="old('content', $informasi?->content)" :height="500"
                    placeholder="Tuliskan materi pengumuman di sini..." required
                    helper="Gunakan toolbar TinyMCE untuk menyusun format paragraf, daftar list, tabel, link, atau kutipan." />
            </div>
        </div>
    </div>

    {{-- RIGHT COLUMN: Image Upload & Preview --}}
    <div class="space-y-6">
        
        {{-- Image Upload Card --}}
        <div class="rounded-2xl border border-white/8 bg-white/3 p-5 space-y-4">
            <div>
                <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-smoke/70">Gambar Sampul</p>
                <p class="mt-0.5 text-xs text-smoke/80">Lampirkan gambar pendukung pengumuman.</p>
            </div>

            <div>
                <label for="image" class="mb-2 block text-xs font-medium text-white">
                    Pilih File Gambar {{ $informasi ? '(Opsional)' : '' }}
                </label>
                <input type="file" id="image" name="image"
                    accept=".jpg,.jpeg,.png,.webp,.avif,image/jpeg,image/png,image/webp,image/avif"
                    class="block w-full rounded-xl border bg-onyx px-3.5 py-2.5 text-xs text-champagne file:mr-3 file:rounded-lg file:border-0 file:bg-white/10 file:px-3 file:py-1.5 file:text-xs file:font-semibold file:text-champagne hover:file:bg-gold hover:file:text-obsidian focus:border-gold/35 focus:outline-none focus:ring-2 focus:ring-gold/12 transition-all cursor-pointer {{ $errors->has('image') ? 'border-red-400/60' : 'border-white/8' }}">
                <p class="mt-2 text-[11px] text-smoke/60">Upload format JPG, PNG, WebP, atau AVIF.</p>
                @error('image')
                    <p class="mt-1.5 text-xs font-medium text-red-300">{{ $message }}</p>
                @enderror
            </div>

            {{-- Preview Image --}}
            @if ($currentImageUrl)
                <div class="rounded-xl border border-white/8 bg-onyx p-3 space-y-2">
                    <p class="text-[10px] font-semibold uppercase tracking-[0.16em] text-smoke/60">Gambar Saat Ini</p>
                    <div class="overflow-hidden rounded-lg border border-white/8">
                        <img src="{{ $currentImageUrl }}" alt="{{ $informasi->title }}" class="h-44 w-full object-cover">
                    </div>
                    <p class="break-all font-mono text-[10px] text-smoke/70" title="{{ $informasi->image }}">{{ $informasi->image }}</p>
                </div>
            @else
                <div class="flex min-h-36 flex-col items-center justify-center gap-2 rounded-xl border border-dashed border-white/12 bg-onyx/50 p-4 text-center">
                    <i class="fa-solid fa-image text-lg text-smoke/40"></i>
                    <p class="text-xs text-smoke/50">Belum ada gambar yang diunggah.</p>
                </div>
            @endif
        </div>

        {{-- Info Box --}}
        <div class="rounded-2xl border border-gold/20 bg-gold/8 p-5 text-xs leading-6 text-gold-soft/90">
            <div class="flex items-center gap-2 font-semibold text-gold-soft mb-1">
                <i class="fa-solid fa-circle-info"></i> Catatan Publikasi
            </div>
            Pengumuman yang disimpan akan langsung tersedia untuk publik sesuai dengan tanggal pembuatannya.
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
