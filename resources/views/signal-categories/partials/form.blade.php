@php($signalCategory = $signalCategory ?? null)

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
        <h3 class="text-base font-semibold text-white">Detail Kategori Signal</h3>
        <p class="mt-0.5 text-xs text-smoke">Tentukan kategori untuk mengelompokkan konten signal.</p>
    </div>

    <div>
        <label for="name" class="mb-2 block text-xs font-semibold uppercase tracking-[0.16em] text-smoke">
            Nama Kategori <span class="text-gold-soft">*</span>
        </label>
        <input type="text" id="name" name="name" value="{{ old('name', $signalCategory?->name) }}"
            class="w-full rounded-xl border bg-onyx px-4 py-3 text-sm text-champagne placeholder:text-smoke/40 focus:border-gold/35 focus:outline-none focus:ring-2 focus:ring-gold/15 transition-colors {{ $errors->has('name') ? 'border-red-400/60' : 'border-white/8' }}"
            placeholder="Contoh: Forex Harian" required>
        <x-forms.field-error field="name" />
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
