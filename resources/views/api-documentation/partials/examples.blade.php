<div>
    <div class="mb-5 overflow-hidden rounded-2xl border border-white/8 bg-white/3">
        <div class="border-b border-white/6 px-5 py-4">
            <p class="text-sm font-semibold text-white">Cara Membaca Contoh Request</p>
            <p class="mt-1 text-xs leading-5 text-smoke">
                Contoh pada halaman ini memakai <span class="font-mono text-champagne">cURL</span> agar bisa dites
                cepat dari terminal, CMD, PowerShell, atau dijadikan referensi saat setup request di Postman maupun code
                frontend.
            </p>
        </div>
        <div class="grid gap-0 divide-y divide-white/6 sm:grid-cols-3 sm:divide-x sm:divide-y-0">
            <div class="px-5 py-4">
                <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-smoke/50">URL</p>
                <p class="mt-2 text-xs leading-5 text-smoke">
                    Menunjukkan endpoint yang dipanggil, termasuk path resource yang sedang diuji.
                </p>
            </div>
            <div class="px-5 py-4">
                <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-smoke/50">Header</p>
                <p class="mt-2 text-xs leading-5 text-smoke">
                    Menunjukkan API key dan identitas client yang harus dikirim bersama request.
                </p>
            </div>
            <div class="px-5 py-4">
                <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-smoke/50">Use Case</p>
                <p class="mt-2 text-xs leading-5 text-smoke">
                    Tiap contoh dipilih untuk mewakili pola integrasi website dan mobile app yang paling umum.
                </p>
            </div>
        </div>
    </div>

    <div class="mb-4 flex items-center gap-3">
        <div
            class="flex h-7 w-7 items-center justify-center rounded-lg border border-orange-500/25 bg-orange-500/15 text-xs font-bold text-orange-300">
            <i class="fa-solid fa-terminal text-[11px]"></i>
        </div>
        <h2 class="text-base font-semibold text-white">cURL Examples</h2>
        <div class="h-px flex-1 bg-white/6"></div>
    </div>

    <div class="space-y-4">
        @foreach ($requestExamples as $example)
            <div class="overflow-hidden rounded-2xl border border-white/8 bg-[#120f0c]">
                <div class="flex items-center justify-between gap-3 border-b border-white/6 bg-[#1b1611] px-4 py-2.5">
                    <div class="flex items-center gap-2">
                        <span class="h-2.5 w-2.5 rounded-full bg-[#ff5f56]"></span>
                        <span class="h-2.5 w-2.5 rounded-full bg-[#ffbd2e]"></span>
                        <span class="h-2.5 w-2.5 rounded-full bg-[#27c93f]"></span>
                    </div>
                    <p class="min-w-0 flex-1 truncate text-sm font-medium text-white">{{ $example['label'] }}</p>
                    <button type="button" data-copy-button data-copy-text="{{ $example['command'] }}"
                        data-copy-label-default="Copy" data-copy-label-success="Copied" data-copy-label-error="Failed"
                        class="inline-flex items-center gap-1.5 rounded-lg border border-white/10 bg-white/5 px-3 py-1.5 text-[11px] font-medium text-champagne/80 transition-colors hover:border-white/18 hover:bg-white/8 hover:text-white">
                        <i class="fa-regular fa-copy text-[10px]"></i>
                        <span data-copy-label>Copy</span>
                    </button>
                </div>
                <pre class="overflow-x-auto whitespace-pre-wrap break-all px-4 py-4 font-mono text-[11px] leading-6 text-champagne"><code>{{ $example['command'] }}</code></pre>
            </div>
        @endforeach

        <div class="rounded-2xl border border-white/8 bg-white/3 px-5 py-4">
            <p class="text-sm font-semibold text-white">Response Error yang Perlu Diketahui</p>
            <div class="mt-3 space-y-2 text-xs text-smoke">
                <p><span class="font-mono text-rose-300">403</span> - API key tidak valid.</p>
                <p><span class="font-mono text-rose-300">403</span> - Allowed Origin Frontend belum dikonfigurasi untuk web client.</p>
                <p><span class="font-mono text-rose-300">403</span> - Header Origin wajib dikirim untuk web client.</p>
                <p><span class="font-mono text-rose-300">403</span> - Origin frontend tidak diizinkan.</p>
                <p><span class="font-mono text-amber-300">503</span> - Public API sedang dinonaktifkan.</p>
            </div>
        </div>

        <div class="grid gap-4 lg:grid-cols-2">
            <div class="rounded-2xl border border-white/8 bg-white/3 px-5 py-4">
                <p class="text-sm font-semibold text-white">Cara Test dari Terminal</p>
                <div class="mt-3 space-y-3 text-xs leading-5 text-smoke">
                    <p>Salin salah satu command lalu jalankan apa adanya di terminal.</p>
                    <p>Ganti domain, endpoint, atau header bila environment yang dipakai berbeda.</p>
                    <p>Perhatikan response body dan status code untuk memastikan authentication sudah benar.</p>
                    <p>Kalau response gagal, cocokkan error dengan daftar status di bawah contoh request.</p>
                </div>
            </div>

            <div class="rounded-2xl border border-white/8 bg-white/3 px-5 py-4">
                <p class="text-sm font-semibold text-white">Cara Ubah ke Postman</p>
                <div class="mt-3 space-y-3 text-xs leading-5 text-smoke">
                    <p>Ambil method dan URL dari command cURL.</p>
                    <p>Pindahkan setiap <span class="font-mono text-champagne">--header</span> ke tab Headers di Postman.</p>
                    <p>Untuk web, pastikan header <span class="font-mono text-champagne">Origin</span> ikut dimasukkan.</p>
                    <p>Untuk mobile app, pastikan header <span class="font-mono text-champagne">{{ $mobileClientHeader }}</span> ikut dimasukkan.</p>
                </div>
            </div>
        </div>
    </div>
</div>
