@php($signal = $signal ?? null)
@php($signalCategory = $signalCategory ?? $signal?->category)
@php($currentImageUrl = $signal?->image_url)
@php($signalLabel = $signal ? strtoupper($signal->potensi) . ' ' . $signal->timeframe : 'Signal')
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

<div class="grid gap-6 lg:grid-cols-[1fr_360px]">
    <div class="space-y-6">
        <div class="space-y-5 rounded-2xl border border-black/8 bg-black/3 p-6">
            <div class="border-b border-black/6 pb-4">
                <h3 class="text-base font-semibold text-ivory">Detail Signal</h3>
                <p class="mt-0.5 text-xs text-smoke">
                    Lengkapi kategori, potensi, timeframe, target profit, stop loss, dan sumber signal.
                </p>
            </div>

            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <label class="mb-2 block text-xs font-semibold uppercase tracking-[0.16em] text-smoke">
                        Kategori Signal
                    </label>
                    <div class="flex items-center gap-2 rounded-xl border border-black/8 bg-onyx px-4 py-3 text-sm text-champagne">
                        <i class="fa-solid fa-folder text-xs text-gold-soft"></i>
                        {{ $signal?->category?->name ?? $signalCategory->name }}
                    </div>
                    <input type="hidden" name="category_id" value="{{ $signal?->category_id ?? $signalCategory->id }}">
                    <x-forms.field-error field="category_id" />
                </div>

                <div>
                    <label for="sumber" class="mb-2 block text-xs font-semibold uppercase tracking-[0.16em] text-smoke">
                        Sumber <span class="text-gold-soft">*</span>
                    </label>
                    <input type="text" id="sumber" name="sumber" value="{{ old('sumber', $signal?->sumber) }}"
                        class="w-full rounded-xl border bg-onyx px-4 py-3 text-sm text-champagne placeholder:text-smoke/40 focus:border-gold/35 focus:outline-none focus:ring-2 focus:ring-gold/12 {{ $errors->has('sumber') ? 'border-red-400/60' : 'border-black/8' }}"
                        placeholder="Contoh: TradingView / Tim Riset" required>
                    <x-forms.field-error field="sumber" />
                </div>
            </div>

            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <label for="potensi" class="mb-2 block text-xs font-semibold uppercase tracking-[0.16em] text-smoke">
                        Potensi <span class="text-gold-soft">*</span>
                    </label>
                    <select id="potensi" name="potensi"
                        class="w-full rounded-xl border bg-onyx px-4 py-3 text-sm text-champagne focus:border-gold/35 focus:outline-none focus:ring-2 focus:ring-gold/12 {{ $errors->has('potensi') ? 'border-red-400/60' : 'border-black/8' }}"
                        required>
                        <option value="">Pilih potensi</option>
                        @foreach (\App\Models\Signal::POTENSI_OPTIONS as $potensi)
                            <option value="{{ $potensi }}" @selected(old('potensi', $signal?->potensi) === $potensi)>
                                {{ strtoupper($potensi) }}
                            </option>
                        @endforeach
                    </select>
                    <x-forms.field-error field="potensi" />
                </div>

                <div>
                    <label for="timeframe" class="mb-2 block text-xs font-semibold uppercase tracking-[0.16em] text-smoke">
                        Timeframe <span class="text-gold-soft">*</span>
                    </label>
                    <select id="timeframe" name="timeframe"
                        class="w-full rounded-xl border bg-onyx px-4 py-3 text-sm text-champagne focus:border-gold/35 focus:outline-none focus:ring-2 focus:ring-gold/12 {{ $errors->has('timeframe') ? 'border-red-400/60' : 'border-black/8' }}"
                        required>
                        <option value="">Pilih timeframe</option>
                        @foreach (\App\Models\Signal::TIMEFRAME_OPTIONS as $timeframe)
                            <option value="{{ $timeframe }}" @selected(old('timeframe', $signal?->timeframe) === $timeframe)>
                                {{ $timeframe }}
                            </option>
                        @endforeach
                    </select>
                    <x-forms.field-error field="timeframe" />
                </div>
            </div>

            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <label for="taking_profit" class="mb-2 block text-xs font-semibold uppercase tracking-[0.16em] text-smoke">
                        Taking Profit <span class="text-gold-soft">*</span>
                    </label>
                    <input type="text" id="taking_profit" name="taking_profit" value="{{ old('taking_profit', $signal?->taking_profit) }}"
                        class="w-full rounded-xl border bg-onyx px-4 py-3 text-sm text-champagne placeholder:text-smoke/40 focus:border-gold/35 focus:outline-none focus:ring-2 focus:ring-gold/12 {{ $errors->has('taking_profit') ? 'border-red-400/60' : 'border-black/8' }}"
                        placeholder="Contoh: 2365 - 2372" required>
                    <x-forms.field-error field="taking_profit" />
                </div>

                <div>
                    <label for="stop_loss" class="mb-2 block text-xs font-semibold uppercase tracking-[0.16em] text-smoke">
                        Stop Loss <span class="text-gold-soft">*</span>
                    </label>
                    <input type="text" id="stop_loss" name="stop_loss" value="{{ old('stop_loss', $signal?->stop_loss) }}"
                        class="w-full rounded-xl border bg-onyx px-4 py-3 text-sm text-champagne placeholder:text-smoke/40 focus:border-gold/35 focus:outline-none focus:ring-2 focus:ring-gold/12 {{ $errors->has('stop_loss') ? 'border-red-400/60' : 'border-black/8' }}"
                        placeholder="Contoh: 2350" required>
                    <x-forms.field-error field="stop_loss" />
                </div>
            </div>
        </div>
    </div>

    <div class="space-y-6">
        <div class="space-y-4 rounded-2xl border border-black/8 bg-black/3 p-5">
            <div>
                <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-smoke/70">Gambar Signal</p>
                <p class="mt-0.5 text-xs text-smoke/80">Unggah gambar pendukung signal.</p>
            </div>

            <input type="file" id="image" name="image"
                accept=".jpg,.jpeg,.png,.webp,.avif,image/jpeg,image/png,image/webp,image/avif"
                class="block w-full rounded-xl border bg-onyx px-3.5 py-2.5 text-xs text-champagne file:mr-3 file:rounded-lg file:border-0 file:bg-white/10 file:px-3 file:py-1.5 file:text-xs file:font-semibold file:text-champagne hover:file:bg-gold hover:file:text-obsidian {{ $errors->has('image') ? 'border-red-400/60' : 'border-black/8' }}">
            <x-forms.field-error field="image" />

            @if ($currentImageUrl)
                <div class="space-y-2 rounded-xl border border-black/8 bg-onyx p-3">
                    <p class="text-[10px] font-semibold uppercase tracking-[0.16em] text-smoke/60">Gambar Saat Ini</p>
                    <div class="overflow-hidden rounded-lg border border-black/8">
                        <img src="{{ $currentImageUrl }}" alt="{{ $signalLabel }}" class="h-44 w-full object-cover">
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<div class="flex items-center justify-end gap-3 border-t border-black/6 pt-6">
    <a href="{{ $cancelUrl }}"
        class="inline-flex items-center justify-center gap-2 rounded-xl border border-white/10 bg-white/5 px-5 py-2.5 text-sm font-medium text-smoke transition-all duration-200 hover:border-white/20 hover:bg-white/10 hover:text-ivory">
        Batal
    </a>
    <button type="submit"
        data-confirm-submit
        data-confirm-intent="save"
        data-confirm-title="{{ $confirmTitle }}"
        data-confirm-message="{{ $confirmMessage }}"
        data-confirm-action-label="{{ $confirmActionLabel }}"
        class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-500 px-6 py-2.5 text-sm font-semibold text-white transition-all duration-200 hover:bg-blue-600">
        <i class="fa-solid fa-check text-xs"></i>
        {{ $submitLabel }}
    </button>
</div>
