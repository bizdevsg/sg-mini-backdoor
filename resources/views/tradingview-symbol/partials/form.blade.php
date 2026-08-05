@php($tradingviewSymbol = $tradingviewSymbol ?? null)
@php($confirmTitle = $confirmTitle ?? 'Simpan data?')
@php($confirmMessage = $confirmMessage ?? 'Pastikan data yang diisi sudah benar sebelum dilanjutkan.')
@php($confirmActionLabel = $confirmActionLabel ?? 'Ya, simpan')

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
        <h3 class="text-base font-semibold text-ivory">Detail Kode TradingView</h3>
        <p class="mt-0.5 text-xs text-smoke">Isi nama simbol, kode websocket internal, dan kode chart TradingView.</p>
    </div>

    {{-- Name Input --}}
    <div>
        <label for="name" class="mb-2 block text-xs font-semibold uppercase tracking-[0.16em] text-smoke">
            Name <span class="text-gold-soft">*</span>
        </label>
        <input type="text" id="name" name="name" value="{{ old('name', $tradingviewSymbol?->name) }}"
            class="w-full rounded-xl border bg-onyx px-4 py-3 text-sm text-champagne placeholder:text-smoke/40 focus:border-gold/35 focus:outline-none focus:ring-2 focus:ring-gold/12 transition-colors {{ $errors->has('name') ? 'border-red-400/60' : 'border-black/8' }}"
            placeholder="Contoh: Emas Spot" required>
        @error('name')
            <p class="mt-1.5 text-xs font-medium text-red-700">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid gap-5 md:grid-cols-2">
        {{-- symbol_ws Input --}}
        <div>
            <label for="symbol_ws" class="mb-2 block text-xs font-semibold uppercase tracking-[0.16em] text-smoke">
                symbol_ws <span class="text-gold-soft">*</span>
            </label>
            <input type="text" id="symbol_ws" name="symbol_ws" value="{{ old('symbol_ws', $tradingviewSymbol?->symbol_ws) }}"
                class="w-full rounded-xl border bg-onyx px-4 py-3 font-mono text-sm text-gold-soft placeholder:text-smoke/40 focus:border-gold/35 focus:outline-none focus:ring-2 focus:ring-gold/12 transition-colors {{ $errors->has('symbol_ws') ? 'border-red-400/60' : 'border-black/8' }}"
                placeholder="Contoh: XAUUSD" required>
            <p class="mt-1.5 text-[11px] text-smoke/70">Kode simbol yang dipakai feed websocket internal.</p>
            @error('symbol_ws')
                <p class="mt-1.5 text-xs font-medium text-red-700">{{ $message }}</p>
            @enderror
        </div>

        {{-- symbol_tv Input --}}
        <div>
            <label for="symbol_tv" class="mb-2 block text-xs font-semibold uppercase tracking-[0.16em] text-smoke">
                symbol_tv <span class="text-gold-soft">*</span>
            </label>
            <input type="text" id="symbol_tv" name="symbol_tv" value="{{ old('symbol_tv', $tradingviewSymbol?->symbol_tv) }}"
                class="w-full rounded-xl border bg-onyx px-4 py-3 font-mono text-sm text-gold-soft placeholder:text-smoke/40 focus:border-gold/35 focus:outline-none focus:ring-2 focus:ring-gold/12 transition-colors {{ $errors->has('symbol_tv') ? 'border-red-400/60' : 'border-black/8' }}"
                placeholder="Contoh: OANDA:XAUUSD" required>
            <p class="mt-1.5 text-[11px] text-smoke/70">Kode simbol yang dipakai widget chart TradingView.</p>
            @error('symbol_tv')
                <p class="mt-1.5 text-xs font-medium text-red-700">{{ $message }}</p>
            @enderror
        </div>
    </div>
</div>

{{-- Bottom Action Buttons --}}
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
