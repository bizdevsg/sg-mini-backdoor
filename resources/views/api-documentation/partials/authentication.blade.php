<div>
    <div class="mb-5 overflow-hidden rounded-2xl border border-black/8 bg-black/3">
        <div class="border-b border-black/6 px-5 py-4">
            <p class="text-sm font-semibold text-ivory">Cara Kerja Authentication</p>
            <p class="mt-1 text-xs leading-5 text-smoke">
                Public API ini tidak memakai login session atau JWT per user. Sebagai gantinya, akses dibatasi memakai
                kombinasi API key dan identitas client. Ini cocok untuk kebutuhan frontend website terpisah dan mobile
                app yang hanya perlu membaca data publik dari backdoor.
            </p>
        </div>
        <div class="grid gap-0 divide-y divide-black/6 sm:grid-cols-2 sm:divide-x sm:divide-y-0">
            <div class="px-5 py-4">
                <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-smoke/50">Website Flow</p>
                <p class="mt-2 text-xs leading-5 text-smoke">
                    Website wajib kirim <span class="font-mono text-champagne">{{ $apiKeyHeader }}</span> dan
                    <span class="font-mono text-champagne">Origin</span> yang telah didaftarkan di System Settings.
                </p>
            </div>
            <div class="px-5 py-4">
                <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-smoke/50">Mobile App Flow</p>
                <p class="mt-2 text-xs leading-5 text-smoke">
                    Mobile app wajib kirim <span class="font-mono text-champagne">{{ $apiKeyHeader }}</span> dan
                    <span class="font-mono text-champagne">{{ $mobileClientHeader }}</span> tanpa perlu origin browser.
                </p>
            </div>
        </div>
    </div>

    <div class="mb-4 flex items-center gap-3">
        <div
            class="flex h-7 w-7 items-center justify-center rounded-lg border border-gold/25 bg-gold/15 text-xs font-bold text-gold-soft">
            <i class="fa-solid fa-key text-[11px]"></i>
        </div>
        <h2 class="text-base font-semibold text-ivory">Authentication</h2>
        <div class="h-px flex-1 bg-black/6"></div>
    </div>

    <div class="space-y-4">
        <div class="overflow-hidden rounded-2xl border border-black/8 bg-black/3">
            <div class="border-b border-black/6 px-5 py-4">
                <p class="text-sm font-semibold text-ivory">Website</p>
                <p class="mt-1 text-xs leading-5 text-smoke">
                    Gunakan mode ini untuk frontend browser atau website. Request wajib kirim API key dan header
                    <span class="font-mono text-champagne">Origin</span> yang sudah didaftarkan di backdoor.
                </p>
            </div>
            <div class="space-y-3 px-5 py-4">
                <div class="rounded-xl border border-black/8 bg-onyx px-4 py-3">
                    <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-smoke/50">Header Wajib</p>
                    <div class="mt-2 space-y-1 font-mono text-[11px]">
                        <p class="text-champagne">{{ $apiKeyHeader }}: {{ filled($apiKeyValue) ? $apiKeyValue : 'your-api-key' }}</p>
                        <p class="text-champagne">Origin: {{ $webOriginExample }}</p>
                    </div>
                </div>
                <div class="rounded-xl border border-black/8 bg-onyx px-4 py-3 text-xs text-smoke">
                    <p>Jika header <span class="font-mono text-champagne">Origin</span> tidak dikirim, API akan menolak request.</p>
                    <p class="mt-1">Jika origin tidak cocok dengan daftar di backdoor, API juga akan menolak request.</p>
                    <p class="mt-1">Mode ini dipakai untuk domain frontend production maupun development yang memang sudah didaftarkan.</p>
                </div>
            </div>
        </div>

        <div class="overflow-hidden rounded-2xl border border-black/8 bg-black/3">
            <div class="border-b border-black/6 px-5 py-4">
                <p class="text-sm font-semibold text-ivory">Mobile App</p>
                <p class="mt-1 text-xs leading-5 text-smoke">
                    Gunakan mode ini untuk aplikasi native Android atau iOS. Request tidak perlu kirim
                    <span class="font-mono text-champagne">Origin</span>, tapi wajib kirim header client type.
                </p>
            </div>
            <div class="space-y-3 px-5 py-4">
                <div class="rounded-xl border border-black/8 bg-onyx px-4 py-3">
                    <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-smoke/50">Header Wajib</p>
                    <div class="mt-2 space-y-1 font-mono text-[11px]">
                        <p class="text-champagne">{{ $apiKeyHeader }}: {{ filled($apiKeyValue) ? $apiKeyValue : 'your-api-key' }}</p>
                        <p class="text-emerald-700">{{ $mobileClientHeader }}: {{ $mobileClientValue }}</p>
                    </div>
                </div>
                <div class="rounded-xl border border-black/8 bg-onyx px-4 py-3 text-xs text-smoke">
                    <p>Mode ini dipakai kalau request datang dari aplikasi native dan tidak punya header
                        <span class="font-mono text-champagne">Origin</span>.
                    </p>
                    <p class="mt-1">Kalau header <span class="font-mono text-champagne">{{ $mobileClientHeader }}</span> tidak dikirim atau nilainya salah, request akan ditolak.</p>
                    <p class="mt-1">Pendekatan ini memisahkan aplikasi native dari validasi origin khusus browser.</p>
                </div>
            </div>
        </div>

        <div class="grid gap-4 lg:grid-cols-2">
            <div class="rounded-2xl border border-black/8 bg-black/3 px-5 py-4">
                <p class="text-sm font-semibold text-ivory">Kapan Website Ditolak</p>
                <div class="mt-3 space-y-3 text-xs leading-5 text-smoke">
                    <p>Header <span class="font-mono text-champagne">{{ $apiKeyHeader }}</span> tidak ada atau nilainya salah.</p>
                    <p>Header <span class="font-mono text-champagne">Origin</span> tidak dikirim.</p>
                    <p>Origin tidak ada di daftar allowed origin yang tersimpan di backdoor.</p>
                    <p>Public API sedang dimatikan dari System Settings.</p>
                </div>
            </div>

            <div class="rounded-2xl border border-black/8 bg-black/3 px-5 py-4">
                <p class="text-sm font-semibold text-ivory">Kapan Mobile App Ditolak</p>
                <div class="mt-3 space-y-3 text-xs leading-5 text-smoke">
                    <p>Header <span class="font-mono text-champagne">{{ $apiKeyHeader }}</span> tidak ada atau tidak valid.</p>
                    <p>Header <span class="font-mono text-champagne">{{ $mobileClientHeader }}</span> tidak dikirim.</p>
                    <p>Value header <span class="font-mono text-champagne">{{ $mobileClientHeader }}</span> bukan
                        <span class="font-mono text-champagne">{{ $mobileClientValue }}</span>.
                    </p>
                    <p>Public API sedang dimatikan dari backdoor.</p>
                </div>
            </div>
        </div>

        @if (filled($apiKeyValue))
            <div class="overflow-hidden rounded-2xl border border-black/8 bg-[#120f0c]">
                <div class="relative">
                    <div class="absolute right-3 top-3">
                        <button type="button" data-copy-button data-copy-text="{{ $apiKeyValue }}"
                            data-copy-label-default="Copy Key" data-copy-label-success="Copied!"
                            data-copy-label-error="Failed"
                            class="inline-flex items-center gap-1.5 rounded-full border border-white/10 bg-white/5 px-2 py-1 text-[10px] font-medium text-white/70 transition-colors hover:border-white/18 hover:bg-white/8 hover:text-white">
                            <i class="fa-regular fa-copy text-[10px]"></i>
                            <span data-copy-label>Copy Key</span>
                        </button>
                    </div>

                    <div class="space-y-1 px-4 py-3 font-mono text-[12px] leading-6">
                        <div class="flex gap-3">
                            <span class="select-none text-gold/50">$</span>
                            <span class="text-gold">{{ $apiKeyHeader }}</span>
                        </div>
                        <div class="flex gap-3">
                            <span class="select-none text-gold/50">&gt;</span>
                            <span class="break-all text-white/90">{{ $apiKeyValue }}</span>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div
                class="rounded-2xl border border-amber-400/20 bg-amber-400/8 px-4 py-4 text-sm text-amber-800">
                <p class="font-medium">API key belum dikonfigurasi.</p>
                <p class="mt-1 text-xs text-amber-800/80">
                    Isi value API key di <span class="font-mono">.env</span> agar request ke
                    <span class="font-mono">/api/v1</span> bisa diautentikasi.
                </p>
            </div>
        @endif
    </div>
</div>
