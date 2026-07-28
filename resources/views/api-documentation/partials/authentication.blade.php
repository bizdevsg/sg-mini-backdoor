<div id="authentication" class="scroll-mt-6">
    <div class="mb-4 flex items-center gap-3">
        <div
            class="flex h-7 w-7 items-center justify-center rounded-lg border border-gold/25 bg-gold/15 text-xs font-bold text-gold-soft">
            <i class="fa-solid fa-key text-[11px]"></i>
        </div>
        <h2 class="text-base font-semibold text-white">Authentication</h2>
        <div class="h-px flex-1 bg-white/6"></div>
    </div>

    <div class="overflow-hidden rounded-2xl border border-white/8 bg-white/3">
        <div class="border-b border-white/6 p-5">
            <p class="text-sm leading-6 text-smoke">
                Semua endpoint <span class="font-mono text-champagne">api/v1</span> diproteksi menggunakan custom header
                <span class="font-medium text-champagne">{{ $apiKeyHeader }}</span>.
            </p>
        </div>

        @if (filled($apiKeyValue))
            <div class="overflow-hidden border-b border-white/6 bg-[#120f0c]">
                <div class="relative">
                    <div class="absolute right-3 top-3">
                        <button type="button" data-copy-button data-copy-text="{{ $apiKeyValue }}"
                            data-copy-label-default="Copy Key" data-copy-label-success="Copied!"
                            data-copy-label-error="Failed"
                            class="inline-flex items-center gap-1.5 rounded-full border border-white/10 bg-white/5 px-2 py-1 text-[10px] font-medium text-champagne/80 transition-colors hover:border-white/18 hover:bg-white/8 hover:text-white">
                            <i class="fa-regular fa-copy text-[10px]"></i>
                            <span data-copy-label>Copy Key</span>
                        </button>
                    </div>

                    <div class="space-y-1 px-4 py-3 font-mono text-[12px] leading-6">
                        <div class="flex gap-3">
                            <span class="select-none text-gold-soft/50">$</span>
                            <span class="text-gold-soft">{{ $apiKeyHeader }}</span>
                        </div>
                        <div class="flex gap-3">
                            <span class="select-none text-gold-soft/50">&gt;</span>
                            <span class="break-all text-white/90">{{ $apiKeyValue }}</span>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div
                class="mx-5 my-4 rounded-2xl border border-amber-400/20 bg-amber-400/8 px-4 py-4 text-sm text-amber-100">
                <p class="font-medium">API key belum dikonfigurasi.</p>
                <p class="mt-1 text-xs text-amber-100/80">
                    Isi <span class="font-mono">API_KEY</span> di file <span class="font-mono">.env</span> agar semua
                    request ke <span class="font-mono">/api/v1</span> bisa diautentikasi.
                </p>
            </div>
        @endif
    </div>
</div>
