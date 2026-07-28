<div id="getting-started" class="scroll-mt-6">
    <div class="mb-4 flex items-center gap-3">
        <div
            class="flex h-7 w-7 items-center justify-center rounded-lg border border-emerald-500/25 bg-emerald-500/15 text-xs font-bold text-emerald-400">
            <i class="fa-solid fa-flag-checkered text-[11px]"></i>
        </div>
        <h2 class="text-base font-semibold text-white">Getting Started</h2>
        <div class="h-px flex-1 bg-white/6"></div>
    </div>

    <div class="grid gap-4 sm:grid-cols-3">
        <div class="relative overflow-hidden rounded-2xl border border-white/8 bg-white/3 p-5">
            <div class="absolute -right-4 -top-4 h-16 w-16 rounded-full bg-gold/5 blur-xl"></div>
            <div class="mb-4 flex items-center gap-3">
                <div
                    class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl border border-gold/25 bg-gold/10 text-sm font-bold text-gold-soft">
                    1</div>
                <p class="text-sm font-semibold text-white">Dapatkan API Key</p>
            </div>
            <p class="text-xs leading-5 text-smoke">
                API key diset di file <span class="font-mono text-champagne">.env</span> server. Hubungi administrator
                untuk mendapatkan value-nya.
            </p>
            @if (filled($apiKeyValue))
                <div
                    class="mt-3 flex items-center gap-2 rounded-xl border border-emerald-500/20 bg-emerald-500/8 px-3 py-2">
                    <i class="fa-solid fa-circle-check text-[11px] text-emerald-400"></i>
                    <span class="text-[11px] font-medium text-emerald-300">API key tersedia</span>
                </div>
            @else
                <div
                    class="mt-3 flex items-center gap-2 rounded-xl border border-amber-400/20 bg-amber-400/8 px-3 py-2">
                    <i class="fa-solid fa-triangle-exclamation text-[11px] text-amber-400"></i>
                    <span class="text-[11px] font-medium text-amber-300">Belum dikonfigurasi</span>
                </div>
            @endif
        </div>

        <div class="relative overflow-hidden rounded-2xl border border-white/8 bg-white/3 p-5">
            <div class="absolute -right-4 -top-4 h-16 w-16 rounded-full bg-blue-500/5 blur-xl"></div>
            <div class="mb-4 flex items-center gap-3">
                <div
                    class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl border border-blue-400/25 bg-blue-400/10 text-sm font-bold text-blue-300">
                    2</div>
                <p class="text-sm font-semibold text-white">Set Header Auth</p>
            </div>
            <p class="text-xs leading-5 text-smoke">
                Tambahkan header <span class="font-mono text-champagne">{{ $apiKeyHeader }}</span> ke setiap request.
            </p>
            <div
                class="mt-3 truncate rounded-lg border border-white/8 bg-black/40 px-3 py-2 font-mono text-[10px] text-champagne/80">
                {{ $apiKeyHeader }}: •••••••••
            </div>
        </div>

        <div class="relative overflow-hidden rounded-2xl border border-white/8 bg-white/3 p-5">
            <div class="absolute -right-4 -top-4 h-16 w-16 rounded-full bg-purple-500/5 blur-xl"></div>
            <div class="mb-4 flex items-center gap-3">
                <div
                    class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl border border-purple-400/25 bg-purple-400/10 text-sm font-bold text-purple-300">
                    3</div>
                <p class="text-sm font-semibold text-white">Hit Endpoint</p>
            </div>
            <p class="text-xs leading-5 text-smoke">
                Request ke <span class="font-mono text-champagne">{{ $apiBaseUrl }}/{endpoint}</span> dengan header
                yang
                sudah diset. Response dalam format JSON.
            </p>
            <div
                class="mt-3 rounded-lg border border-white/8 bg-black/40 px-3 py-2 font-mono text-[10px] text-emerald-400/80">
                HTTP 200 OK · application/json
            </div>
        </div>
    </div>
</div>
