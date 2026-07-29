<div>
    <div class="mb-5 overflow-hidden rounded-2xl border border-white/8 bg-white/3">
        <div class="border-b border-white/6 px-5 py-4">
            <p class="text-sm font-semibold text-white">Tentang Halaman Ini</p>
            <p class="mt-1 text-xs leading-5 text-smoke">
                Halaman ini menjelaskan alur dasar saat frontend, Postman, atau aplikasi mobile mulai memakai public API
                <span class="font-mono text-champagne">/api/v1</span>. Urutannya sederhana:
                siapkan API key, tentukan tipe client, lalu kirim request ke endpoint yang dibutuhkan.
            </p>
        </div>
        <div class="grid gap-0 divide-y divide-white/6 sm:grid-cols-3 sm:divide-x sm:divide-y-0">
            <div class="px-5 py-4">
                <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-smoke/50">Base URL</p>
                <p class="mt-2 font-mono text-xs text-champagne">{{ $apiBaseUrl }}</p>
                <p class="mt-2 text-xs leading-5 text-smoke">
                    Semua endpoint pada dokumentasi ini menggunakan base URL yang sama.
                </p>
            </div>
            <div class="px-5 py-4">
                <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-smoke/50">Authentication</p>
                <p class="mt-2 font-mono text-xs text-champagne">{{ $apiKeyHeader }}</p>
                <p class="mt-2 text-xs leading-5 text-smoke">
                    Header ini wajib ada di setiap request, baik dari web, Postman, maupun mobile app.
                </p>
            </div>
            <div class="px-5 py-4">
                <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-smoke/50">Response</p>
                <p class="mt-2 font-mono text-xs text-emerald-300">application/json</p>
                <p class="mt-2 text-xs leading-5 text-smoke">
                    Response public API dikembalikan dalam format JSON agar mudah dikonsumsi frontend terpisah.
                </p>
            </div>
        </div>
    </div>

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
                    1
                </div>
                <p class="text-sm font-semibold text-white">Siapkan API Key</p>
            </div>
            <p class="text-xs leading-5 text-smoke">
                Semua endpoint <span class="font-mono text-champagne">/api/v1</span> wajib memakai header
                <span class="font-mono text-champagne">{{ $apiKeyHeader }}</span>.
            </p>
            <p class="mt-3 text-[11px] leading-5 text-smoke/80">
                API key ini disimpan di sisi server admin dan dipakai oleh client yang sudah diotorisasi. Jangan expose
                value ini ke pihak yang tidak berkepentingan.
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
                    <span class="text-[11px] font-medium text-amber-300">API key belum dikonfigurasi</span>
                </div>
            @endif
        </div>

        <div class="relative overflow-hidden rounded-2xl border border-white/8 bg-white/3 p-5">
            <div class="absolute -right-4 -top-4 h-16 w-16 rounded-full bg-blue-500/5 blur-xl"></div>
            <div class="mb-4 flex items-center gap-3">
                <div
                    class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl border border-blue-400/25 bg-blue-400/10 text-sm font-bold text-blue-300">
                    2
                </div>
                <p class="text-sm font-semibold text-white">Pilih Tipe Client</p>
            </div>
            <p class="text-xs leading-5 text-smoke">
                Web client kirim header <span class="font-mono text-champagne">Origin</span>. Mobile app kirim header
                <span class="font-mono text-champagne">{{ $mobileClientHeader }}</span>.
            </p>
            <p class="mt-3 text-[11px] leading-5 text-smoke/80">
                Pemisahan ini penting karena browser otomatis mengirim origin domain, sedangkan aplikasi native tidak
                punya mekanisme origin yang sama seperti website.
            </p>
            <div class="mt-3 space-y-2 rounded-lg border border-white/8 bg-black/40 px-3 py-2 font-mono text-[10px]">
                <p class="text-champagne/80">Origin: {{ $webOriginExample }}</p>
                <p class="text-emerald-300/80">{{ $mobileClientHeader }}: {{ $mobileClientValue }}</p>
            </div>
        </div>

        <div class="relative overflow-hidden rounded-2xl border border-white/8 bg-white/3 p-5">
            <div class="absolute -right-4 -top-4 h-16 w-16 rounded-full bg-purple-500/5 blur-xl"></div>
            <div class="mb-4 flex items-center gap-3">
                <div
                    class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl border border-purple-400/25 bg-purple-400/10 text-sm font-bold text-purple-300">
                    3
                </div>
                <p class="text-sm font-semibold text-white">Hit Endpoint</p>
            </div>
            <p class="text-xs leading-5 text-smoke">
                Request ke endpoint yang dibutuhkan dengan header client yang sesuai. Semua response dikembalikan
                dalam format JSON.
            </p>
            <p class="mt-3 text-[11px] leading-5 text-smoke/80">
                Setelah request berhasil, frontend cukup membaca field response lalu merender data sesuai kebutuhan
                halaman atau aplikasi.
            </p>
            <div
                class="mt-3 rounded-lg border border-white/8 bg-black/40 px-3 py-2 font-mono text-[10px] text-emerald-400/80">
                HTTP 200 OK - application/json
            </div>
        </div>
    </div>

    <div class="mt-5 grid gap-4 lg:grid-cols-2">
        <div class="rounded-2xl border border-white/8 bg-white/3 px-5 py-4">
            <p class="text-sm font-semibold text-white">Alur Integrasi Singkat</p>
            <div class="mt-3 space-y-3 text-xs leading-5 text-smoke">
                <p><span class="font-medium text-champagne">1.</span> Ambil endpoint yang dibutuhkan dari menu dokumentasi.</p>
                <p><span class="font-medium text-champagne">2.</span> Tambahkan header API key ke request.</p>
                <p><span class="font-medium text-champagne">3.</span> Tentukan apakah request berasal dari website atau mobile app.</p>
                <p><span class="font-medium text-champagne">4.</span> Uji di Postman terlebih dahulu sebelum dipakai di frontend production.</p>
            </div>
        </div>

        <div class="rounded-2xl border border-white/8 bg-white/3 px-5 py-4">
            <p class="text-sm font-semibold text-white">Kesalahan Umum Saat Mulai Integrasi</p>
            <div class="mt-3 space-y-3 text-xs leading-5 text-smoke">
                <p><span class="font-medium text-rose-300">API key salah</span> membuat request langsung ditolak.</p>
                <p><span class="font-medium text-rose-300">Origin web tidak sesuai</span> membuat website tidak bisa akses API.</p>
                <p><span class="font-medium text-rose-300">Lupa header mobile app</span> membuat request aplikasi native ikut ditolak.</p>
                <p><span class="font-medium text-amber-300">API dimatikan dari backdoor</span> membuat semua request public API berhenti sementara.</p>
            </div>
        </div>
    </div>
</div>
