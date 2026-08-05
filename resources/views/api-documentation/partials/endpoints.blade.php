<div>
    <div class="mb-5 overflow-hidden rounded-2xl border border-black/8 bg-black/3">
        <div class="border-b border-black/6 px-5 py-4">
            <p class="text-sm font-semibold text-ivory">Struktur Endpoint</p>
            <p class="mt-1 text-xs leading-5 text-smoke">
                Semua endpoint di bawah ini berada dalam namespace
                <span class="font-mono text-champagne">{{ $apiBaseUrl }}</span>. Pengelompokan dibuat berdasarkan menu
                data agar tim frontend lebih cepat mencari resource yang dibutuhkan.
            </p>
        </div>
        <div class="grid gap-0 divide-y divide-black/6 sm:grid-cols-3 sm:divide-x sm:divide-y-0">
            <div class="px-5 py-4">
                <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-smoke/50">List Endpoint</p>
                <p class="mt-2 text-xs leading-5 text-smoke">
                    Digunakan untuk mengambil kumpulan data, biasanya mendukung pagination, search, atau filter.
                </p>
            </div>
            <div class="px-5 py-4">
                <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-smoke/50">Detail Endpoint</p>
                <p class="mt-2 text-xs leading-5 text-smoke">
                    Digunakan untuk mengambil satu data spesifik berdasarkan slug atau identifier lain.
                </p>
            </div>
            <div class="px-5 py-4">
                <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-smoke/50">Kategori Endpoint</p>
                <p class="mt-2 text-xs leading-5 text-smoke">
                    Dipakai ketika resource memiliki pengelompokan seperti kategori berita, signal, atau ebook.
                </p>
            </div>
        </div>
    </div>

    <div class="mb-4 flex items-center gap-3">
        <div
            class="flex h-7 w-7 items-center justify-center rounded-lg border border-blue-500/25 bg-blue-500/15 text-xs font-bold text-blue-700">
            <i class="fa-solid fa-route text-[11px]"></i>
        </div>
        <h2 class="text-base font-semibold text-ivory">Endpoints</h2>
        <div class="h-px flex-1 bg-black/6"></div>
    </div>

    <div class="space-y-4">
        @foreach ($endpointGroups as $group)
            <div class="overflow-hidden rounded-2xl border border-black/8 bg-black/3">
                <div class="flex items-center gap-3 border-b border-black/6 px-5 py-3.5">
                    <div>
                        <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-smoke/50">{{ $group['title'] }}</p>
                        <p class="text-sm font-semibold text-ivory">{{ $group['description'] }}</p>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="border-b border-black/6 bg-onyx text-left text-[10px] font-semibold uppercase tracking-[0.18em] text-smoke/50">
                                <th class="w-20 px-5 py-3">Method</th>
                                <th class="px-5 py-3">Endpoint</th>
                                <th class="px-5 py-3">Catatan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-black/5">
                            @foreach ($group['endpoints'] as $endpoint)
                                <tr class="align-top transition-colors hover:bg-black/3">
                                    <td class="px-5 py-3">
                                        @php
                                            $methodColor = match ($endpoint['method']) {
                                                'POST' => 'border-emerald-400/25 bg-emerald-400/10 text-emerald-700',
                                                'PUT', 'PATCH' => 'border-yellow-400/25 bg-yellow-400/10 text-yellow-700',
                                                'DELETE' => 'border-red-400/25 bg-red-400/10 text-red-700',
                                                default => 'border-blue-400/25 bg-blue-400/10 text-blue-800',
                                            };
                                        @endphp
                                        <span
                                            class="{{ $methodColor }} inline-flex rounded-md border px-2 py-0.5 text-[10px] font-bold uppercase tracking-[0.16em]">
                                            {{ $endpoint['method'] }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-3 font-mono text-xs text-champagne">{{ $endpoint['path'] }}</td>
                                    <td class="px-5 py-3 text-sm text-smoke">{{ $endpoint['notes'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-5 grid gap-4 lg:grid-cols-2">
        <div class="rounded-2xl border border-black/8 bg-black/3 px-5 py-4">
            <p class="text-sm font-semibold text-ivory">Konvensi yang Dipakai</p>
            <div class="mt-3 space-y-3 text-xs leading-5 text-smoke">
                <p>Path tanpa parameter biasanya dipakai untuk list data.</p>
                <p>Path dengan <span class="font-mono text-champagne">{slug}</span> dipakai untuk detail data.</p>
                <p>Mayoritas endpoint public pada dokumentasi ini bersifat <span class="font-mono text-champagne">GET</span>.</p>
                <p>Response dibaca oleh frontend terpisah, jadi struktur data harus dianggap sebagai kontrak integrasi.</p>
            </div>
        </div>

        <div class="rounded-2xl border border-black/8 bg-black/3 px-5 py-4">
            <p class="text-sm font-semibold text-ivory">Catatan Integrasi</p>
            <div class="mt-3 space-y-3 text-xs leading-5 text-smoke">
                <p>Mulai dari endpoint list dulu sebelum memakai endpoint detail.</p>
                <p>Pastikan slug yang dipakai di endpoint detail berasal dari data list yang valid.</p>
                <p>Kalau frontend memerlukan cache, simpan hasil endpoint list yang sering dipakai.</p>
                <p>Jika ada penambahan menu baru di backdoor, sebaiknya section endpoint di dokumentasi ikut diperbarui.</p>
            </div>
        </div>
    </div>
</div>
