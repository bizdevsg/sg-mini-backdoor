<div id="api-security" class="rounded-lg border border-white/10 bg-white/[0.02]">
    <div class="border-b border-white/10 px-5 py-4">
        <h2 class="text-sm font-semibold text-white">API &amp; Security</h2>
        <p class="mt-0.5 text-xs text-smoke/60">
            Kontrol status API publik, origin frontend yang diizinkan, dan catatan rotasi API key.
        </p>
    </div>

    <form action="{{ route('client-area.update-api-security') }}" method="POST" class="space-y-5 px-5 py-5">
        @csrf
        @method('PUT')

        <div class="flex items-start justify-between gap-4 rounded-lg border border-white/8 bg-black/20 px-4 py-4">
            <div class="min-w-0">
                <p class="text-sm font-medium text-white">Public API Aktif</p>
                <p class="mt-1 text-xs leading-relaxed text-smoke/60">
                    Jika dimatikan, seluruh endpoint `/api/v1/*` akan mengembalikan response `503`.
                </p>
            </div>

            <label class="relative inline-flex shrink-0 cursor-pointer items-center">
                <input type="hidden" name="api_enabled" value="0">
                <input type="checkbox" name="api_enabled" value="1" class="peer sr-only"
                    {{ old('api_enabled', $settings['api_enabled']) ? 'checked' : '' }}>
                <span
                    class="inline-flex h-6 w-11 items-center rounded-full bg-zinc-700 transition peer-checked:bg-emerald-500 peer-focus-visible:ring-2 peer-focus-visible:ring-gold/50">
                    <span
                        class="ml-1 inline-block h-4.5 w-4.5 rounded-full bg-white shadow transition-transform peer-checked:translate-x-5"
                        style="height:1.125rem;width:1.125rem;"></span>
                </span>
            </label>
        </div>

        <div class="grid gap-5 lg:grid-cols-2">
            <div class="space-y-2">
                <label for="allowed_origin_frontend" class="text-xs font-medium uppercase tracking-wide text-smoke/50">
                    Allowed Origin Frontend
                </label>
                <textarea id="allowed_origin_frontend" name="allowed_origin_frontend" rows="5"
                    class="w-full rounded-lg border border-white/10 bg-black/30 px-3 py-2 text-sm text-white outline-none transition placeholder:text-smoke/35 focus:border-gold/40"
                    placeholder="https://frontend-dev.test&#10;https://frontend-prod.com">{{ old('allowed_origin_frontend', $settings['allowed_origin_frontend']) }}</textarea>
                <p class="text-xs text-smoke/50">Pisahkan dengan baris baru atau koma. Jika diisi, request wajib mengirim header `Origin` yang sesuai.</p>
            </div>

            <div class="space-y-5">
                <div class="space-y-2">
                    <label for="api_key_rotation_notice" class="text-xs font-medium uppercase tracking-wide text-smoke/50">
                        API Key Rotation Notice
                    </label>
                    <input id="api_key_rotation_notice" name="api_key_rotation_notice" type="text"
                        value="{{ old('api_key_rotation_notice', $settings['api_key_rotation_notice']) }}"
                        class="w-full rounded-lg border border-white/10 bg-black/30 px-3 py-2 text-sm text-white outline-none transition placeholder:text-smoke/35 focus:border-gold/40"
                        placeholder="Contoh: Rotasi API key dijadwalkan 15 Agustus 2026.">
                    <p class="text-xs text-smoke/50">Akan dikirim sebagai header response `X-API-Key-Rotation-Notice` ke API publik.</p>
                </div>
            </div>
        </div>

        @if ($errors->hasAny(['api_enabled', 'allowed_origin_frontend', 'api_key_rotation_notice']))
            <div class="rounded-lg border border-rose-500/20 bg-rose-500/10 px-4 py-3 text-xs text-rose-200">
                @foreach ($errors->only(['api_enabled', 'allowed_origin_frontend', 'api_key_rotation_notice']) as $messages)
                    @foreach ($messages as $message)
                        <p>{{ $message }}</p>
                    @endforeach
                @endforeach
            </div>
        @endif

        <div class="flex items-center justify-between gap-4 border-t border-white/10 pt-4">
            <p class="text-xs text-smoke/55">
                Setting ini berlaku untuk semua endpoint publik yang memakai API key.
            </p>
            <button type="submit"
                class="inline-flex items-center justify-center rounded-lg border border-gold/25 bg-gold/10 px-4 py-2 text-sm font-medium text-champagne transition hover:bg-gold/15">
                Simpan API &amp; Security
            </button>
        </div>
    </form>
</div>
