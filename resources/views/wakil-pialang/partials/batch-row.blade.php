<div data-wakil-pialang-row class="rounded-xl border border-black/8 bg-onyx/50 p-4">
    <div class="mb-4 flex items-center justify-between gap-3">
        <p class="text-sm font-semibold text-ivory">Wakil Pialang</p>
        <button type="button" data-remove-wakil-pialang-row class="inline-flex items-center gap-1.5 rounded-lg border border-red-400/25 bg-red-500/8 px-3 py-1.5 text-xs font-medium text-red-700/80 transition-all hover:border-red-400/40 hover:bg-red-500/16 hover:text-red-800"><i class="fa-solid fa-trash text-[10px]"></i> Hapus Baris</button>
    </div>
    <div class="grid gap-4 md:grid-cols-3">
        <div>
            <label for="nama-{{ $index }}" class="mb-2 block text-xs font-semibold uppercase tracking-[0.16em] text-smoke">Nama <span class="text-gold-soft">*</span></label>
            <input type="text" id="nama-{{ $index }}" name="items[{{ $index }}][nama]" value="{{ $item['nama'] ?? '' }}" placeholder="Nama lengkap" required class="w-full rounded-xl border {{ $errors->has('items.'.$index.'.nama') ? 'border-red-400/60' : 'border-black/8' }} bg-onyx px-4 py-3 text-sm text-champagne placeholder:text-smoke/40 transition-colors focus:border-gold/35 focus:outline-none focus:ring-2 focus:ring-gold/15">
            <x-forms.field-error field="items.{{ $index }}.nama" />
        </div>
        <div>
            <label for="no-identitas-{{ $index }}" class="mb-2 block text-xs font-semibold uppercase tracking-[0.16em] text-smoke">No. Identitas <span class="text-gold-soft">*</span></label>
            <input type="text" id="no-identitas-{{ $index }}" name="items[{{ $index }}][no_identitas]" value="{{ $item['no_identitas'] ?? '' }}" placeholder="Nomor identitas" required class="w-full rounded-xl border {{ $errors->has('items.'.$index.'.no_identitas') ? 'border-red-400/60' : 'border-black/8' }} bg-onyx px-4 py-3 text-sm text-champagne placeholder:text-smoke/40 transition-colors focus:border-gold/35 focus:outline-none focus:ring-2 focus:ring-gold/15">
            <x-forms.field-error field="items.{{ $index }}.no_identitas" />
        </div>
        <div>
            <label for="status-{{ $index }}" class="mb-2 block text-xs font-semibold uppercase tracking-[0.16em] text-smoke">Status <span class="text-gold-soft">*</span></label>
            <select id="status-{{ $index }}" name="items[{{ $index }}][status]" required class="w-full rounded-xl border {{ $errors->has('items.'.$index.'.status') ? 'border-red-400/60' : 'border-black/8' }} bg-onyx px-4 py-3 text-sm text-champagne transition-colors focus:border-gold/35 focus:outline-none focus:ring-2 focus:ring-gold/15">
                @foreach (\App\Models\WakilPialang::STATUS_OPTIONS as $value => $label)
                    <option value="{{ $value }}" @selected(($item['status'] ?? 'aktif') === $value)>{{ $label }}</option>
                @endforeach
            </select>
            <x-forms.field-error field="items.{{ $index }}.status" />
        </div>
    </div>
</div>
