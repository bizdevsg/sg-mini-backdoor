@php($wakilPialang = $wakilPialang ?? null)
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
        <h3 class="text-base font-semibold text-ivory">Detail Wakil Pialang Berjangka</h3>
        <p class="mt-0.5 text-xs text-smoke">Isi identitas, kategori, dan status wakil pialang berjangka.</p>
    </div>

    <div class="grid gap-5 md:grid-cols-2">
        <div>
            <label for="nama" class="mb-2 block text-xs font-semibold uppercase tracking-[0.16em] text-smoke">
                Nama <span class="text-gold-soft">*</span>
            </label>
            <input type="text" id="nama" name="nama" value="{{ old('nama', $wakilPialang?->nama) }}"
                class="{{ $inputClass }} {{ $errors->has('nama') ? 'border-red-400/60' : 'border-black/8' }}"
                placeholder="Nama lengkap" required>
            <x-forms.field-error field="nama" />
        </div>

        <div>
            <label for="no_identitas" class="mb-2 block text-xs font-semibold uppercase tracking-[0.16em] text-smoke">
                No. Identitas <span class="text-gold-soft">*</span>
            </label>
            <input type="text" id="no_identitas" name="no_identitas"
                value="{{ old('no_identitas', $wakilPialang?->no_identitas) }}"
                class="{{ $inputClass }} {{ $errors->has('no_identitas') ? 'border-red-400/60' : 'border-black/8' }}"
                placeholder="Nomor identitas" required>
            <x-forms.field-error field="no_identitas" />
        </div>

        <div class="md:col-span-2">
            <label for="status" class="mb-2 block text-xs font-semibold uppercase tracking-[0.16em] text-smoke">
                Status <span class="text-gold-soft">*</span>
            </label>
            <select id="status" name="status"
                class="{{ $inputClass }} {{ $errors->has('status') ? 'border-red-400/60' : 'border-black/8' }}"
                required>
                @foreach (\App\Models\WakilPialang::STATUS_OPTIONS as $value => $label)
                    <option value="{{ $value }}" @selected(old('status', $wakilPialang?->status ?? 'aktif') === $value)>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
            <x-forms.field-error field="status" />
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
