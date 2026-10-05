@php($wakilPialangCategory = $wakilPialangCategory ?? null)
@php($confirmTitle = $confirmTitle ?? 'Simpan data?')
@php($confirmMessage = $confirmMessage ?? 'Pastikan data yang diisi sudah benar sebelum dilanjutkan.')
@php($confirmActionLabel = $confirmActionLabel ?? 'Ya, simpan')
@php($inputClass = 'w-full rounded-xl border bg-onyx px-4 py-3 text-sm text-champagne placeholder:text-smoke/40 focus:border-gold/35 focus:outline-none focus:ring-2 focus:ring-gold/15 transition-colors')

@if ($errors->any())
    <div class="flex items-center gap-3 rounded-xl border border-red-500/30 bg-red-50/40 px-4 py-3 text-sm text-red-800 shadow-lg">
        <i class="fa-solid fa-triangle-exclamation text-base text-red-600"></i>
        <div>
            <p class="font-medium text-red-700">Terdapat kesalahan pengisian:</p>
            <p class="text-xs text-red-800/80">{{ $errors->first() }}</p>
        </div>
    </div>
@endif

<div class="rounded-2xl border border-black/8 bg-black/3 p-6 space-y-5">
    <div class="border-b border-black/6 pb-4">
        <h3 class="text-base font-semibold text-ivory">Detail Kategori</h3>
        <p class="mt-0.5 text-xs text-smoke">Isi data kantor cabang untuk kategori wakil pialang berjangka.</p>
    </div>

    <div>
        <label for="nama_kategori" class="mb-2 block text-xs font-semibold uppercase tracking-[0.16em] text-smoke">
            Nama Kategori <span class="text-gold-soft">*</span>
        </label>
        <input type="text" id="nama_kategori" name="nama_kategori"
            value="{{ old('nama_kategori', $wakilPialangCategory?->nama_kategori) }}"
            class="{{ $inputClass }} {{ $errors->has('nama_kategori') ? 'border-red-400/60' : 'border-black/8' }}"
            placeholder="Contoh: Kantor Pusat Jakarta" required>
        <x-forms.field-error field="nama_kategori" />
    </div>

    <div>
        <label for="alamat_kantor_cabang" class="mb-2 block text-xs font-semibold uppercase tracking-[0.16em] text-smoke">
            Alamat Kantor Cabang <span class="text-gold-soft">*</span>
        </label>
        <textarea id="alamat_kantor_cabang" name="alamat_kantor_cabang" rows="3"
            class="{{ $inputClass }} {{ $errors->has('alamat_kantor_cabang') ? 'border-red-400/60' : 'border-black/8' }}"
            placeholder="Alamat lengkap kantor cabang" required>{{ old('alamat_kantor_cabang', $wakilPialangCategory?->alamat_kantor_cabang) }}</textarea>
        <x-forms.field-error field="alamat_kantor_cabang" />
    </div>

    <div class="grid gap-5 md:grid-cols-2">
        <div>
            <label for="telp" class="mb-2 block text-xs font-semibold uppercase tracking-[0.16em] text-smoke">
                Telp <span class="text-gold-soft">*</span>
            </label>
            <input type="text" id="telp" name="telp" value="{{ old('telp', $wakilPialangCategory?->telp) }}"
                class="{{ $inputClass }} {{ $errors->has('telp') ? 'border-red-400/60' : 'border-black/8' }}"
                placeholder="Contoh: 021-5551234" required>
            <x-forms.field-error field="telp" />
        </div>

        <div>
            <label for="link_google_maps" class="mb-2 block text-xs font-semibold uppercase tracking-[0.16em] text-smoke">
                Link Google Maps <span class="text-gold-soft">*</span>
            </label>
            <input type="url" id="link_google_maps" name="link_google_maps"
                value="{{ old('link_google_maps', $wakilPialangCategory?->link_google_maps) }}"
                class="{{ $inputClass }} {{ $errors->has('link_google_maps') ? 'border-red-400/60' : 'border-black/8' }}"
                placeholder="https://maps.google.com/..." required>
            <x-forms.field-error field="link_google_maps" />
        </div>
    </div>
</div>

<div class="flex items-center justify-end gap-3 border-t border-black/6 pt-6">
    <a href="{{ $cancelUrl }}"
        class="inline-flex items-center justify-center gap-2 rounded-xl border border-black/10 bg-black/5 px-5 py-2.5 text-sm font-medium text-smoke transition-all duration-200 hover:border-black/18 hover:bg-black/8 hover:text-ivory">
        Batal
    </a>
    <button type="submit"
        data-confirm-submit
        data-confirm-intent="save"
        data-confirm-title="{{ $confirmTitle }}"
        data-confirm-message="{{ $confirmMessage }}"
        data-confirm-action-label="{{ $confirmActionLabel }}"
        class="inline-flex items-center justify-center gap-2 rounded-xl bg-gold px-6 py-2.5 text-sm font-semibold text-obsidian shadow-[0_4px_18px_rgba(199,161,90,0.28)] transition-all duration-200 hover:bg-gold-soft hover:shadow-[0_6px_24px_rgba(199,161,90,0.4)]">
        <i class="fa-solid fa-check text-xs"></i>
        {{ $submitLabel }}
    </button>
</div>
