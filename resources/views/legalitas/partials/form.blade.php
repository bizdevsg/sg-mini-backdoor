@php($legalitas = $legalitas ?? null)

@if ($errors->any())
    <div class="flex items-center gap-3 rounded-xl border border-red-500/30 bg-red-950/40 px-4 py-3 text-sm text-red-200 shadow-lg">
        <i class="fa-solid fa-triangle-exclamation text-base text-red-400"></i>
        <div>
            <p class="font-medium text-red-300">Terdapat kesalahan pengisian:</p>
            <p class="text-xs text-red-200/80">{{ $errors->first() }}</p>
        </div>
    </div>
@endif

<div class="rounded-2xl border border-white/8 bg-white/3 p-6 space-y-5">
    <div class="border-b border-white/6 pb-4">
        <h3 class="text-base font-semibold text-white">Detail Legalitas Perusahaan</h3>
        <p class="mt-0.5 text-xs text-smoke">Isi judul dokumen, nomor izin/SK resmi, dan deskripsi hukum.</p>
    </div>

    <div class="grid gap-5 md:grid-cols-2">
        {{-- Title Input --}}
        <div>
            <label for="title" class="mb-2 block text-xs font-semibold uppercase tracking-[0.16em] text-smoke">
                Judul Dokumen Legalitas <span class="text-gold-soft">*</span>
            </label>
            <input type="text" id="title" name="title" value="{{ old('title', $legalitas?->title) }}"
                class="w-full rounded-xl border bg-onyx px-4 py-3 text-sm text-champagne placeholder:text-smoke/40 focus:border-gold/35 focus:outline-none focus:ring-2 focus:ring-gold/12 transition-colors {{ $errors->has('title') ? 'border-red-400/60' : 'border-white/8' }}"
                placeholder="Contoh: Izin Usaha Perdagangan Berjangka" required>
            @error('title')
                <p class="mt-1.5 text-xs font-medium text-red-300">{{ $message }}</p>
            @enderror
        </div>

        {{-- Nomor Input --}}
        <div>
            <label for="nomor" class="mb-2 block text-xs font-semibold uppercase tracking-[0.16em] text-smoke">
                Nomor Izin / SK Resmi <span class="text-gold-soft">*</span>
            </label>
            <input type="text" id="nomor" name="nomor" value="{{ old('nomor', $legalitas?->nomor) }}"
                class="w-full rounded-xl border bg-onyx px-4 py-3 font-mono text-sm text-gold-soft placeholder:text-smoke/40 focus:border-gold/35 focus:outline-none focus:ring-2 focus:ring-gold/12 transition-colors {{ $errors->has('nomor') ? 'border-red-400/60' : 'border-white/8' }}"
                placeholder="Contoh: 123/BAPPEBTI/SK-III/2026" required>
            @error('nomor')
                <p class="mt-1.5 text-xs font-medium text-red-300">{{ $message }}</p>
            @enderror
        </div>
    </div>

    {{-- Description Input --}}
    <div>
        <label for="description" class="mb-2 block text-xs font-semibold uppercase tracking-[0.16em] text-smoke">
            Deskripsi Legalitas <span class="text-gold-soft">*</span>
        </label>
        <textarea id="description" name="description" rows="6"
            class="w-full rounded-xl border bg-onyx px-4 py-3 text-sm text-champagne placeholder:text-smoke/40 focus:border-gold/35 focus:outline-none focus:ring-2 focus:ring-gold/12 transition-colors {{ $errors->has('description') ? 'border-red-400/60' : 'border-white/8' }}"
            placeholder="Tuliskan deskripsi ringkas cakupan izin atau landasan hukum legalitas ini..." required>{{ old('description', filled($legalitas?->description) ? trim(strip_tags($legalitas->description)) : '') }}</textarea>
        <p class="mt-2 text-[11px] text-smoke/70">Jelaskan instansi penerbit, tanggal penetapan, atau ruang lingkup kewenangan lisensi ini.</p>
        @error('description')
            <p class="mt-1.5 text-xs font-medium text-red-300">{{ $message }}</p>
        @enderror
    </div>

    {{-- Info Box --}}
    <div class="rounded-xl border border-gold/20 bg-gold/8 p-4 text-xs leading-6 text-gold-soft/90">
        <div class="flex items-center gap-2 font-semibold text-gold-soft mb-0.5">
            <i class="fa-solid fa-circle-info"></i> Informasi Publikasi
        </div>
        Dokumen legalitas ini akan langsung ditampilkan di halaman legalitas & transparansi perusahaan untuk publik.
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
