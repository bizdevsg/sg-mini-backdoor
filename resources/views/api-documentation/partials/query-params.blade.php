<div id="query-params" class="scroll-mt-6">
    <div class="mb-4 flex items-center gap-3">
        <div
            class="flex h-7 w-7 items-center justify-center rounded-lg border border-purple-500/25 bg-purple-500/15 text-xs font-bold text-purple-300">
            <i class="fa-solid fa-sliders text-[11px]"></i>
        </div>
        <h2 class="text-base font-semibold text-white">Query Parameters</h2>
        <div class="h-px flex-1 bg-white/6"></div>
    </div>

    <div class="overflow-hidden rounded-2xl border border-white/8 bg-white/3">
        <div class="grid divide-y divide-white/6 sm:grid-cols-3 sm:divide-x sm:divide-y-0">
            <div class="px-5 py-4">
                <p class="mb-1 font-mono text-xs text-champagne">?page=1&per_page=10</p>
                <p class="text-[11px] leading-5 text-smoke">Pagination — halaman dan jumlah item per halaman.</p>
            </div>
            <div class="px-5 py-4">
                <p class="mb-1 font-mono text-xs text-champagne">?search=keyword</p>
                <p class="text-[11px] leading-5 text-smoke">Pencarian full-text pada list resource.</p>
            </div>
            <div class="px-5 py-4">
                <p class="mb-1 font-mono text-xs text-champagne">?category={slug}</p>
                <p class="text-[11px] leading-5 text-smoke">Filter resource berdasarkan kategori / slug.</p>
            </div>
        </div>

        <div class="border-t border-white/6 bg-white/3 px-5 py-4">
            <div class="flex items-start gap-3">
                <div
                    class="mt-0.5 flex h-7 w-7 shrink-0 items-center justify-center rounded-lg border border-orange-400/20 bg-orange-400/10">
                    <i class="fa-solid fa-box-open text-[11px] text-orange-300"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-white">Postman</p>
                    <p class="mt-1 text-xs leading-5 text-smoke">
                        Buka tab <span class="font-medium text-champagne">Headers</span>, isi key
                        <span class="font-mono text-champagne">{{ $apiKeyHeader }}</span> dengan value API key aktif.
                        Query params bisa diisi di tab <span class="font-medium text-champagne">Params</span>.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
