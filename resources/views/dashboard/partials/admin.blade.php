@php
    $quickLinks = [
        [
            'href' => route('produk.index', ['section' => 'jfx']),
            'icon' => 'fa-solid fa-layer-group',
            'label' => 'Produk Multilateral',
            'description' => "{$produkJFXcount} produk aktif",
            'accent' => 'purple',
        ],
        [
            'href' => route('produk.index', ['section' => 'spa']),
            'icon' => 'fa-solid fa-handshake',
            'label' => 'Produk Bilateral',
            'description' => "{$produkSPAcount} produk aktif",
            'accent' => 'purple',
        ],
        [
            'href' => route('banner.index'),
            'icon' => 'fa-solid fa-image',
            'label' => 'Banner',
            'description' => "{$bannerCount} banner aktif",
            'accent' => 'white',
        ],
        [
            'href' => route('pengumuman.index'),
            'icon' => 'fa-solid fa-bullhorn',
            'label' => 'Pengumuman',
            'description' => "{$informasiCount} tayang",
            'accent' => 'white',
        ],
        [
            'href' => route('penghargaan.index'),
            'icon' => 'fa-solid fa-award',
            'label' => 'Penghargaan',
            'description' => "{$penghargaanCount} terdokumentasi",
            'accent' => 'white',
        ],
        [
            'href' => route('terms-and-conditions.show'),
            'icon' => 'fa-solid fa-scroll',
            'label' => 'Syarat & Ketentuan',
            'description' => 'Dokumen kebijakan utama',
            'accent' => 'white',
        ],
        [
            'href' => route('privacy-policy.show'),
            'icon' => 'fa-solid fa-shield-halved',
            'label' => 'Kebijakan Privasi',
            'description' => 'Dokumen privasi perusahaan',
            'accent' => 'white',
        ],
    ];

    $stats = [
        [
            'title' => 'Total Produk Multilateral',
            'value' => $produkJFXcount,
            'icon' => 'fa-solid fa-globe',
            'description' => 'Seluruh katalog produk multilateral.',
            'tone' => 'purple',
        ],
        [
            'title' => 'Total Produk Bilateral',
            'value' => $produkSPAcount,
            'icon' => 'fa-solid fa-handshake',
            'description' => 'Seluruh katalog produk bilateral.',
            'tone' => 'white',
        ],
        [
            'title' => 'Total Banner',
            'value' => $bannerCount,
            'icon' => 'fa-solid fa-image',
            'description' => 'Banner yang ditampilkan.',
            'tone' => 'white',
        ],
        [
            'title' => 'Pengumuman',
            'value' => $informasiCount,
            'icon' => 'fa-solid fa-bullhorn',
            'description' => 'Materi informasi yang tersedia.',
            'tone' => 'white',
        ],
        [
            'title' => 'Penghargaan',
            'value' => $penghargaanCount,
            'icon' => 'fa-solid fa-award',
            'description' => 'Dokumentasi penghargaan aktif.',
            'tone' => 'white',
        ],
    ];

    $miniStats = [
        ['label' => 'Produk', 'value' => $produkJFXcount + $produkSPAcount],
        ['label' => 'Banner Aktif', 'value' => $bannerCount],
        ['label' => 'Pengumuman', 'value' => $informasiCount],
        ['label' => 'Penghargaan', 'value' => $penghargaanCount],
    ];

    $totalContentCount = $produkJFXcount + $produkSPAcount + $bannerCount + $informasiCount + $penghargaanCount;
    $safeTotal = max($totalContentCount, 1);
    $barSegments = [
        ['label' => 'Multilateral', 'value' => $produkJFXcount, 'color' => 'bg-[rgba(139,92,246,0.9)]'],
        ['label' => 'Bilateral', 'value' => $produkSPAcount, 'color' => 'bg-[rgba(167,139,250,0.7)]'],
        ['label' => 'Banner', 'value' => $bannerCount, 'color' => 'bg-smoke/50'],
        ['label' => 'Pengumuman', 'value' => $informasiCount, 'color' => 'bg-white/30'],
        ['label' => 'Penghargaan', 'value' => $penghargaanCount, 'color' => 'bg-white/15'],
    ];
    $barSegments = array_map(
        static fn (array $segment): array => [
            ...$segment,
            'pct' => round(($segment['value'] / $safeTotal) * 100, 1),
        ],
        $barSegments,
    );
@endphp

<section class="space-y-6">

    {{-- =========================================================
         HERO CARD — Operational / Professional (Purple)
         ========================================================= --}}
    <div class="relative overflow-hidden rounded-[32px] border border-white/8 bg-[radial-gradient(ellipse_70%_70%_at_0%_0%,rgba(139,92,246,0.2),transparent),radial-gradient(ellipse_40%_50%_at_100%_100%,rgba(139,92,246,0.08),transparent),linear-gradient(160deg,rgba(255,255,255,0.04)_0%,rgba(255,255,255,0.01)_100%)] shadow-[0_32px_80px_rgba(0,0,0,0.4)]">

        {{-- Ambient glows --}}
        <div class="pointer-events-none absolute -left-10 -top-10 h-80 w-80 rounded-full bg-[rgba(139,92,246,0.1)] blur-[100px]"></div>
        <div class="pointer-events-none absolute -right-20 bottom-0 h-60 w-60 rounded-full bg-[rgba(139,92,246,0.07)] blur-[80px]"></div>

        {{-- Top shimmer line — purple --}}
        <div class="pointer-events-none absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-[rgba(139,92,246,0.6)] to-transparent"></div>

        <div class="relative grid gap-0 lg:grid-cols-[1fr_auto]">
            <div class="space-y-6 p-7 lg:p-10">

                {{-- Role badge --}}
                <div class="flex items-center gap-3">
                    <span class="inline-flex items-center gap-2 rounded-full border border-[rgba(139,92,246,0.35)] bg-[rgba(139,92,246,0.1)] px-3.5 py-1.5 text-[10px] font-semibold uppercase tracking-[0.24em] text-[rgba(196,181,253,0.9)]">
                        <span class="h-1.5 w-1.5 animate-pulse rounded-full bg-[rgba(139,92,246,0.9)]"></span>
                        Dashboard Admin / SGB
                    </span>
                    <span class="inline-flex items-center gap-1.5 rounded-full border border-white/10 bg-white/5 px-2.5 py-1 text-[9px] font-semibold uppercase tracking-[0.2em] text-smoke/80">
                        <i class="fa-solid fa-briefcase text-[8px]"></i>
                        Operasional
                    </span>
                </div>

                {{-- Headline --}}
                <div class="space-y-3">
                    <h1 class="max-w-2xl text-3xl font-semibold leading-[1.15] tracking-[-0.04em] text-white lg:text-[2.75rem]">
                        Pantau semua konten utama<br>
                        <span class="bg-gradient-to-r from-[rgba(167,139,250,1)] via-[rgba(196,181,253,0.95)] to-[rgba(167,139,250,0.8)] bg-clip-text text-transparent">dari satu panel operasional.</span>
                    </h1>
                    <p class="max-w-xl text-sm leading-7 text-smoke lg:text-base">
                        Kelola katalog produk, banner aktif, pengumuman, penghargaan, serta dokumen kebijakan dari satu beranda admin utama.
                    </p>
                </div>

                {{-- CTA buttons --}}
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('produk.index', ['section' => 'jfx']) }}"
                        class="inline-flex items-center gap-2 rounded-xl bg-[rgba(139,92,246,1)] px-5 py-2.5 text-sm font-semibold text-white shadow-[0_4px_20px_rgba(139,92,246,0.4)] transition-all duration-200 hover:bg-[rgba(109,40,217,1)] hover:shadow-[0_6px_28px_rgba(139,92,246,0.55)]">
                        <i class="fa-solid fa-layer-group text-xs"></i>
                        Kelola Produk
                    </a>
                    <a href="{{ route('pengumuman.index') }}"
                        class="inline-flex items-center gap-2 rounded-xl border border-white/12 bg-white/6 px-5 py-2.5 text-sm font-medium text-white backdrop-blur-sm transition-all duration-200 hover:border-white/20 hover:bg-white/10">
                        <i class="fa-solid fa-bullhorn text-xs"></i>
                        Kelola Pengumuman
                    </a>
                </div>

                {{-- Mini stats --}}
                <div class="flex flex-wrap items-center gap-x-6 gap-y-3 border-t border-white/6 pt-5">
                    @foreach ($miniStats as $miniStat)
                        <div class="flex items-baseline gap-2">
                            <span class="text-2xl font-semibold text-white">{{ $miniStat['value'] }}</span>
                            <span class="text-xs text-smoke">{{ $miniStat['label'] }}</span>
                        </div>
                        @if (! $loop->last)
                            <span class="h-4 w-px bg-white/10"></span>
                        @endif
                    @endforeach
                </div>
            </div>

            {{-- Right panel: Total counter --}}
            <div class="flex items-center justify-center border-t border-white/6 p-7 lg:border-l lg:border-t-0 lg:p-10">
                <div class="relative flex h-52 w-52 flex-col items-center justify-center rounded-full border border-[rgba(139,92,246,0.25)] bg-gradient-to-br from-[rgba(139,92,246,0.14)] via-[rgba(139,92,246,0.06)] to-transparent shadow-[inset_0_1px_1px_rgba(255,255,255,0.06),0_20px_60px_rgba(0,0,0,0.3)]">
                    {{-- Animated dashed ring — purple --}}
                    <svg class="absolute inset-0 h-full w-full -rotate-90 animate-[spin_30s_linear_infinite]" viewBox="0 0 208 208">
                        <circle cx="104" cy="104" r="100" fill="none" stroke="rgba(139,92,246,0.4)" stroke-width="1.5" stroke-dasharray="8 5" />
                    </svg>

                    <div class="z-10 space-y-1 text-center">
                        <p class="text-[10px] font-semibold uppercase tracking-[0.22em] text-[rgba(196,181,253,0.7)]">Total Konten</p>
                        <p class="text-5xl font-semibold text-white">{{ $totalContentCount }}</p>
                        <p class="text-xs text-smoke">item dikelola</p>
                    </div>

                    <div class="absolute -bottom-5 left-1/2 -translate-x-1/2 whitespace-nowrap rounded-full border border-white/10 bg-noir/90 px-4 py-1.5 text-[11px] font-medium text-[rgba(196,181,253,0.8)] shadow-lg backdrop-blur-sm">
                        <i class="fa-solid fa-clock-rotate-left mr-1.5 text-[9px] text-[rgba(139,92,246,0.7)]"></i>
                        {{ $recentProducts->count() }} produk terbaru
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- =========================================================
         PRODUCT OVERVIEW SUMMARY — Unik untuk Admin
         ========================================================= --}}
    <div class="grid gap-3 sm:grid-cols-2">
        <div class="overflow-hidden rounded-2xl border border-[rgba(139,92,246,0.2)] bg-[radial-gradient(ellipse_80%_80%_at_0%_0%,rgba(139,92,246,0.1),transparent)] px-6 py-5">
            <div class="flex items-center gap-4">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl border border-[rgba(139,92,246,0.3)] bg-[rgba(139,92,246,0.15)] text-[rgba(196,181,253,0.9)]">
                    <i class="fa-solid fa-layer-group text-lg"></i>
                </div>
                <div>
                    <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-[rgba(196,181,253,0.6)]">Produk Multilateral</p>
                    <p class="text-3xl font-semibold text-white">{{ $produkJFXcount }}</p>
                    <p class="text-xs text-smoke">katalog aktif</p>
                </div>
            </div>
            <div class="mt-4 border-t border-white/6 pt-3">
                <a href="{{ route('produk.index', ['section' => 'jfx']) }}"
                    class="inline-flex items-center gap-1.5 text-xs font-medium text-[rgba(196,181,253,0.8)] transition-colors hover:text-[rgba(196,181,253,1)]">
                    Lihat katalog <i class="fa-solid fa-arrow-right text-[9px]"></i>
                </a>
            </div>
        </div>

        <div class="overflow-hidden rounded-2xl border border-[rgba(139,92,246,0.14)] bg-[radial-gradient(ellipse_80%_80%_at_100%_0%,rgba(139,92,246,0.07),transparent)] px-6 py-5">
            <div class="flex items-center gap-4">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl border border-[rgba(139,92,246,0.2)] bg-[rgba(139,92,246,0.1)] text-[rgba(196,181,253,0.7)]">
                    <i class="fa-solid fa-handshake text-lg"></i>
                </div>
                <div>
                    <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-[rgba(196,181,253,0.5)]">Produk Bilateral</p>
                    <p class="text-3xl font-semibold text-white">{{ $produkSPAcount }}</p>
                    <p class="text-xs text-smoke">katalog aktif</p>
                </div>
            </div>
            <div class="mt-4 border-t border-white/6 pt-3">
                <a href="{{ route('produk.index', ['section' => 'spa']) }}"
                    class="inline-flex items-center gap-1.5 text-xs font-medium text-[rgba(196,181,253,0.7)] transition-colors hover:text-[rgba(196,181,253,1)]">
                    Lihat katalog <i class="fa-solid fa-arrow-right text-[9px]"></i>
                </a>
            </div>
        </div>
    </div>

    {{-- =========================================================
         QUICK LINKS
         ========================================================= --}}
    <div>
        <p class="mb-3 text-[10px] font-semibold uppercase tracking-[0.22em] text-smoke/60">Akses cepat</p>
        <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 xl:grid-cols-4">
            @foreach ($quickLinks as $link)
                @php $isPurple = $link['accent'] === 'purple'; @endphp
                <a href="{{ $link['href'] }}"
                    class="{{ $isPurple
                        ? 'group relative flex flex-col gap-3 overflow-hidden rounded-2xl border border-[rgba(139,92,246,0.3)] bg-[rgba(139,92,246,0.1)] p-4 transition-all duration-300 hover:-translate-y-0.5 hover:border-[rgba(139,92,246,0.45)] hover:bg-[rgba(139,92,246,0.16)]'
                        : 'group relative flex flex-col gap-3 overflow-hidden rounded-2xl border border-white/8 bg-white/4 p-4 transition-all duration-300 hover:-translate-y-0.5 hover:border-white/14 hover:bg-white/7' }}">
                    <div class="{{ $isPurple
                        ? 'flex h-9 w-9 items-center justify-center rounded-xl border border-[rgba(139,92,246,0.35)] bg-[rgba(139,92,246,0.18)] text-sm text-[rgba(196,181,253,0.95)]'
                        : 'flex h-9 w-9 items-center justify-center rounded-xl border border-white/10 bg-white/6 text-sm text-smoke group-hover:text-champagne' }}">
                        <i class="{{ $link['icon'] }}"></i>
                    </div>
                    <div>
                        <p class="{{ $isPurple ? 'text-sm font-medium leading-tight text-[rgba(196,181,253,0.95)]' : 'text-sm font-medium leading-tight text-white/80 group-hover:text-white' }}">
                            {{ $link['label'] }}
                        </p>
                        <p class="mt-0.5 text-xs text-smoke">{{ $link['description'] }}</p>
                    </div>
                </a>
            @endforeach
        </div>
    </div>

    {{-- =========================================================
         STATS + BAR CHART
         ========================================================= --}}
    <div class="space-y-3">
        <div class="flex flex-col gap-4 overflow-hidden rounded-2xl border border-white/8 bg-white/3 px-6 py-5 sm:flex-row sm:items-center sm:justify-between">
            <div class="space-y-1">
                <p class="text-[10px] font-semibold uppercase tracking-[0.22em] text-smoke/60">Ringkasan konten</p>
                <p class="text-lg font-semibold text-white">Statistik Pengelolaan</p>
                <p class="text-sm text-smoke">
                    Total <span class="font-medium text-[rgba(196,181,253,0.9)]">{{ $totalContentCount }}</span> item dikelola.
                </p>
            </div>

            <div class="w-full sm:w-72">
                <div class="flex h-2 w-full overflow-hidden rounded-full bg-white/6">
                    @foreach ($barSegments as $segment)
                        @if ($segment['value'] > 0)
                            <div class="{{ $segment['color'] }} h-full" style="width: {{ $segment['pct'] }}%" title="{{ $segment['label'] }}: {{ $segment['value'] }}"></div>
                        @endif
                    @endforeach
                </div>
                <div class="mt-3 flex flex-wrap gap-x-4 gap-y-1.5">
                    @foreach ($barSegments as $segment)
                        <div class="flex items-center gap-1.5">
                            <span class="{{ $segment['color'] }} h-1.5 w-1.5 rounded-full ring-1 ring-white/10"></span>
                            <span class="text-[10px] text-smoke">{{ $segment['label'] }}</span>
                            <span class="text-[10px] font-medium text-[rgba(196,181,253,0.7)]">{{ $segment['value'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
            @foreach ($stats as $stat)
                @php $isPurpleTone = $stat['tone'] === 'purple'; @endphp
                <article class="{{ $isPurpleTone
                    ? 'rounded-2xl border border-[rgba(139,92,246,0.3)] bg-[rgba(139,92,246,0.1)] p-5'
                    : 'rounded-2xl border border-white/8 bg-white/3 p-5' }}">
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <p class="text-[10px] font-semibold uppercase tracking-[0.18em] {{ $isPurpleTone ? 'text-[rgba(196,181,253,0.7)]' : 'text-smoke/70' }}">
                                {{ $stat['title'] }}
                            </p>
                            <p class="mt-1 text-3xl font-semibold text-white">{{ $stat['value'] }}</p>
                        </div>
                        <div class="{{ $isPurpleTone
                            ? 'flex h-12 w-12 items-center justify-center rounded-2xl border border-[rgba(139,92,246,0.35)] bg-[rgba(139,92,246,0.18)] text-[rgba(196,181,253,0.95)]'
                            : 'flex h-12 w-12 items-center justify-center rounded-2xl border border-white/10 bg-white/6 text-smoke' }}">
                            <i class="{{ $stat['icon'] }} text-lg"></i>
                        </div>
                    </div>
                    <p class="mt-4 text-sm leading-6 text-smoke">{{ $stat['description'] }}</p>
                </article>
            @endforeach
        </div>
    </div>

    {{-- =========================================================
         RECENT PRODUCTS TABLE
         ========================================================= --}}
    <div class="overflow-hidden rounded-2xl border border-white/8 bg-white/3">
        <div class="flex flex-col gap-4 border-b border-white/6 px-6 py-5 sm:flex-row sm:items-center sm:justify-between">
            <div class="space-y-1">
                <p class="text-[10px] font-semibold uppercase tracking-[0.22em] text-smoke/60">Produk terbaru</p>
                <h2 class="text-xl font-semibold tracking-[-0.03em] text-white">Aktivitas Katalog Terbaru</h2>
                <p class="text-sm text-smoke">{{ $recentProducts->count() }} produk paling baru yang baru masuk ke sistem.</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('produk.index', ['section' => 'jfx']) }}"
                    class="inline-flex items-center gap-1.5 rounded-lg border border-[rgba(139,92,246,0.2)] bg-[rgba(139,92,246,0.07)] px-3.5 py-2 text-xs font-medium text-[rgba(196,181,253,0.8)] transition-all duration-200 hover:border-[rgba(139,92,246,0.35)] hover:bg-[rgba(139,92,246,0.14)] hover:text-[rgba(196,181,253,1)]">
                    <i class="fa-solid fa-layer-group text-[10px]"></i>
                    Multilateral
                </a>
                <a href="{{ route('produk.index', ['section' => 'spa']) }}"
                    class="inline-flex items-center gap-1.5 rounded-lg border border-white/10 bg-white/5 px-3.5 py-2 text-xs font-medium text-smoke transition-all duration-200 hover:border-white/18 hover:bg-white/8 hover:text-champagne">
                    <i class="fa-solid fa-handshake text-[10px]"></i>
                    Bilateral
                </a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="border-b border-white/5 bg-noir/50 text-left text-[10px] font-semibold uppercase tracking-[0.18em] text-smoke/70">
                        <th class="w-12 px-6 py-3.5">#</th>
                        <th class="px-4 py-3.5">Nama Produk</th>
                        <th class="px-4 py-3.5">Kategori</th>
                        <th class="hidden px-4 py-3.5 lg:table-cell">Deskripsi</th>
                        <th class="px-4 py-3.5 text-right">Dibuat</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse ($recentProducts as $index => $product)
                        <tr class="group transition-colors duration-150 hover:bg-[rgba(139,92,246,0.04)]">
                            <td class="px-6 py-4">
                                <span class="flex h-6 w-6 items-center justify-center rounded-md bg-white/5 text-xs font-medium text-smoke group-hover:bg-[rgba(139,92,246,0.12)] group-hover:text-[rgba(196,181,253,0.9)]">
                                    {{ $index + 1 }}
                                </span>
                            </td>
                            <td class="max-w-[220px] px-4 py-4">
                                <p class="truncate font-medium text-white">{{ $product->nama_produk }}</p>
                            </td>
                            <td class="px-4 py-4">
                                <span class="inline-flex items-center rounded-md border border-[rgba(139,92,246,0.3)] bg-[rgba(139,92,246,0.12)] px-2.5 py-1 text-[10px] font-medium text-[rgba(196,181,253,0.9)]">
                                    {{ $product->kategori }}
                                </span>
                            </td>
                            <td class="hidden max-w-xs px-4 py-4 lg:table-cell">
                                <p class="truncate text-sm text-smoke">
                                    {{ \Illuminate\Support\Str::limit(strip_tags($product->deskripsi_produk), 80) }}
                                </p>
                            </td>
                            <td class="px-4 py-4 text-right">
                                <div class="space-y-0.5">
                                    <p class="text-xs font-medium text-[rgba(196,181,253,0.8)]">{{ $product->created_at?->format('d M Y') ?? '-' }}</p>
                                    <p class="text-[10px] text-smoke/60">{{ $product->created_at?->format('H:i') ?? '' }}</p>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center">
                                <div class="mx-auto flex max-w-xs flex-col items-center gap-3">
                                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl border border-[rgba(139,92,246,0.2)] bg-[rgba(139,92,246,0.07)] text-[rgba(196,181,253,0.7)]">
                                        <i class="fa-solid fa-box-open text-lg"></i>
                                    </div>
                                    <p class="text-sm text-smoke">Belum ada produk terbaru untuk ditampilkan.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="flex items-center justify-between border-t border-white/6 bg-noir/30 px-6 py-4">
            <p class="text-xs text-smoke">
                Menampilkan {{ $recentProducts->count() }} dari {{ $produkJFXcount + $produkSPAcount }} total produk
            </p>
            <a href="{{ route('produk.index', ['section' => 'jfx']) }}"
                class="inline-flex items-center gap-1.5 rounded-lg border border-[rgba(139,92,246,0.2)] bg-[rgba(139,92,246,0.07)] px-3.5 py-2 text-xs font-medium text-[rgba(196,181,253,0.8)] transition-all duration-200 hover:border-[rgba(139,92,246,0.35)] hover:bg-[rgba(139,92,246,0.14)] hover:text-[rgba(196,181,253,1)]">
                Lihat Semua Produk
                <i class="fa-solid fa-arrow-right text-[9px]"></i>
            </a>
        </div>
    </div>
</section>
