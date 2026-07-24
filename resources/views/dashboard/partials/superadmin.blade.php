@php
    $quickLinks = [
        [
            'href' => route('produk.index', ['section' => 'jfx']),
            'icon' => 'fa-solid fa-layer-group',
            'label' => 'Produk Multilateral',
            'description' => "{$produkJFXcount} produk aktif",
            'accent' => 'gold',
        ],
        [
            'href' => route('produk.index', ['section' => 'spa']),
            'icon' => 'fa-solid fa-handshake',
            'label' => 'Produk Bilateral',
            'description' => "{$produkSPAcount} produk aktif",
            'accent' => 'gold',
        ],
        [
            'href' => route('user-management.index'),
            'icon' => 'fa-solid fa-users-gear',
            'label' => 'User Admin',
            'description' => "{$userCount} pengguna",
            'accent' => 'gold',
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
            'tone' => 'gold',
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
            'description' => 'Banner yang sedang ditampilkan.',
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
        [
            'title' => 'User Admin',
            'value' => $userCount,
            'icon' => 'fa-solid fa-users-gear',
            'description' => "{$superadminCount} superadmin · {$adminCount} admin · {$adminHostCount} admin host.",
            'tone' => 'gold',
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
        ['label' => 'Multilateral', 'value' => $produkJFXcount, 'color' => 'bg-gold'],
        ['label' => 'Bilateral', 'value' => $produkSPAcount, 'color' => 'bg-gold-soft'],
        ['label' => 'Banner', 'value' => $bannerCount, 'color' => 'bg-smoke/50'],
        ['label' => 'Pengumuman', 'value' => $informasiCount, 'color' => 'bg-white/30'],
        ['label' => 'Penghargaan', 'value' => $penghargaanCount, 'color' => 'bg-white/15'],
    ];
    $barSegments = array_map(
        static fn(array $segment): array => [...$segment, 'pct' => round(($segment['value'] / $safeTotal) * 100, 1)],
        $barSegments,
    );

    $safeUserTotal = max($userCount, 1);
    $userBreakdown = [
        [
            'label' => 'Superadmin',
            'value' => $superadminCount,
            'color' => 'bg-gold',
            'pct' => round(($superadminCount / $safeUserTotal) * 100),
        ],
        [
            'label' => 'Admin',
            'value' => $adminCount,
            'color' => 'bg-[rgba(139,92,246,0.8)]',
            'pct' => round(($adminCount / $safeUserTotal) * 100),
        ],
        [
            'label' => 'Admin Host',
            'value' => $adminHostCount,
            'color' => 'bg-blue-500',
            'pct' => round(($adminHostCount / $safeUserTotal) * 100),
        ],
    ];
@endphp

<section class="space-y-6">

    {{-- =========================================================
         HERO CARD — Royal / Full Control (Pure Gold)
         ========================================================= --}}
    <div
        class="relative overflow-hidden rounded-[32px] border border-white/8 bg-[radial-gradient(ellipse_70%_70%_at_0%_0%,rgba(199,161,90,0.25),transparent),radial-gradient(ellipse_50%_60%_at_100%_80%,rgba(199,161,90,0.1),transparent),linear-gradient(160deg,rgba(255,255,255,0.04)_0%,rgba(255,255,255,0.01)_100%)] shadow-[0_32px_80px_rgba(0,0,0,0.4)]">

        {{-- Ambient glows --}}
        <div
            class="pointer-events-none absolute -right-16 -top-16 h-72 w-72 rounded-full bg-gold/10 blur-[90px]">
        </div>
        <div class="pointer-events-none absolute -bottom-12 left-1/4 h-56 w-56 rounded-full bg-gold/8 blur-[70px]"></div>
        <div
            class="pointer-events-none absolute bottom-0 right-1/4 h-40 w-40 rounded-full bg-gold-soft/6 blur-[50px]">
        </div>

        {{-- Top shimmer line — pure gold --}}
        <div
            class="pointer-events-none absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-gold/60 to-transparent">
        </div>

        <div class="relative grid gap-0 lg:grid-cols-[1fr_auto]">
            <div class="space-y-6 p-7 lg:p-10">

                {{-- Role badge --}}
                <div class="flex items-center gap-3">
                    <span
                        class="inline-flex items-center gap-2 rounded-full border border-gold/25 bg-gold/10 px-3.5 py-1.5 text-[10px] font-semibold uppercase tracking-[0.24em] text-gold-soft/90">
                        <span class="h-1.5 w-1.5 animate-pulse rounded-full bg-gold"></span>
                        Dashboard Superadmin / SGB
                    </span>
                    <span
                        class="inline-flex items-center gap-1.5 rounded-full border border-gold/30 bg-gold/8 px-2.5 py-1 text-[9px] font-semibold uppercase tracking-[0.2em] text-gold-soft/80">
                        <i class="fa-solid fa-crown text-[8px]"></i>
                        Full Access
                    </span>
                </div>

                {{-- Headline --}}
                <div class="space-y-3">
                    <h1
                        class="max-w-2xl text-3xl font-semibold leading-[1.15] tracking-[-0.04em] text-white lg:text-[2.75rem]">
                        Kendalikan seluruh panel admin<br>
                        <span
                            class="bg-gradient-to-r from-gold-soft via-champagne to-gold-soft bg-clip-text text-transparent">dengan
                            akses penuh dan ringkas.</span>
                    </h1>
                    <p class="max-w-xl text-sm leading-7 text-smoke lg:text-base">
                        Kelola katalog produk, banner aktif, pengumuman, penghargaan, dokumen kebijakan, dan akun admin
                        dari satu beranda utama superadmin.
                    </p>
                </div>

                {{-- CTA buttons --}}
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('produk.index', ['section' => 'jfx']) }}"
                        class="inline-flex items-center gap-2 rounded-xl bg-gold px-5 py-2.5 text-sm font-semibold text-obsidian shadow-[0_4px_20px_rgba(199,161,90,0.35)] transition-all duration-200 hover:bg-gold-soft hover:shadow-[0_6px_28px_rgba(199,161,90,0.5)]">
                        <i class="fa-solid fa-layer-group text-xs"></i>
                        Kelola Produk
                    </a>
                    <a href="{{ route('user-management.index') }}"
                        class="inline-flex items-center gap-2 rounded-xl border border-white/12 bg-white/6 px-5 py-2.5 text-sm font-medium text-white backdrop-blur-sm transition-all duration-200 hover:border-white/20 hover:bg-white/10">
                        <i class="fa-solid fa-users-gear text-xs"></i>
                        Kelola User
                    </a>
                </div>

                {{-- Mini stats --}}
                <div class="flex flex-wrap items-center gap-x-6 gap-y-3 border-t border-white/6 pt-5">
                    @foreach ($miniStats as $miniStat)
                        <div class="flex items-baseline gap-2">
                            <span class="text-2xl font-semibold text-white">{{ $miniStat['value'] }}</span>
                            <span class="text-xs text-smoke">{{ $miniStat['label'] }}</span>
                        </div>
                        @if (!$loop->last)
                            <span class="h-4 w-px bg-white/10"></span>
                        @endif
                    @endforeach
                    <span class="h-4 w-px bg-white/10"></span>
                    <div class="flex items-baseline gap-2">
                        <span class="text-2xl font-semibold text-gold-soft">{{ $userCount }}</span>
                        <span class="text-xs text-smoke">Admin User</span>
                    </div>
                </div>
            </div>

            {{-- Right panel: Total counter --}}
            <div
                class="flex flex-col items-center justify-center gap-6 border-t border-white/6 p-7 lg:border-l lg:border-t-0 lg:p-10">
                <div
                    class="relative flex h-52 w-52 flex-col items-center justify-center rounded-full border border-gold/20 bg-gradient-to-br from-gold/14 via-gold/6 to-transparent shadow-[inset_0_1px_1px_rgba(255,255,255,0.06),0_20px_60px_rgba(0,0,0,0.3)]">
                    {{-- Animated dashed ring — pure gold --}}
                    <svg class="absolute inset-0 h-full w-full -rotate-90 animate-[spin_30s_linear_infinite]"
                        viewBox="0 0 208 208">
                        <circle cx="104" cy="104" r="100" fill="none" stroke="rgba(199,161,90,0.5)"
                            stroke-width="1.5" stroke-dasharray="8 5" />
                    </svg>

                    <div class="z-10 space-y-1 text-center">
                        <p class="text-[10px] font-semibold uppercase tracking-[0.22em] text-gold-soft/70">Total Konten
                        </p>
                        <p class="text-5xl font-semibold text-white">{{ $totalContentCount }}</p>
                        <p class="text-xs text-smoke">item dikelola</p>
                    </div>

                    <div
                        class="absolute -bottom-5 left-1/2 -translate-x-1/2 whitespace-nowrap rounded-full border border-white/10 bg-noir/90 px-4 py-1.5 text-[11px] font-medium text-champagne/80 shadow-lg backdrop-blur-sm">
                        <i class="fa-solid fa-clock-rotate-left mr-1.5 text-[9px] text-gold-soft/70"></i>
                        {{ $recentProducts->count() }} produk terbaru
                    </div>
                </div>

                {{-- User count indicator --}}
                <div
                    class="flex items-center gap-2 rounded-2xl border border-gold/20 bg-gold/6 px-4 py-2.5">
                    <div
                        class="flex h-7 w-7 items-center justify-center rounded-lg bg-gold/15 text-gold-soft">
                        <i class="fa-solid fa-users text-xs"></i>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-white">{{ $userCount }} <span
                                class="text-gold-soft/80">Users</span></p>
                        <p class="text-[10px] text-smoke">Terdaftar di sistem</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- =========================================================
         USER BREAKDOWN — Unik untuk Superadmin
         ========================================================= --}}
    <div
        class="overflow-hidden rounded-2xl border border-gold/20 bg-[radial-gradient(ellipse_80%_80%_at_0%_0%,rgba(199,161,90,0.08),transparent)]">
        <div class="flex flex-col gap-6 px-6 py-5 sm:flex-row sm:items-center sm:gap-8">
            <div class="space-y-1 shrink-0">
                <p class="text-[10px] font-semibold uppercase tracking-[0.22em] text-gold-soft/60">Manajemen
                    Akses</p>
                <p class="text-lg font-semibold text-white">Distribusi User Admin</p>
                <p class="text-sm text-smoke">Total <span
                        class="font-medium text-gold-soft">{{ $userCount }}</span> akun terdaftar.</p>
            </div>

            <div class="flex-1 space-y-3">
                {{-- Stacked bar --}}
                <div class="flex h-2.5 w-full overflow-hidden rounded-full bg-white/6">
                    @foreach ($userBreakdown as $segment)
                        @if ($segment['value'] > 0)
                            <div class="{{ $segment['color'] }} h-full transition-all duration-500"
                                style="width: {{ $segment['pct'] }}%"
                                title="{{ $segment['label'] }}: {{ $segment['value'] }}"></div>
                        @endif
                    @endforeach
                </div>

                <div class="flex flex-wrap gap-x-5 gap-y-2">
                    @foreach ($userBreakdown as $seg)
                        <div class="flex items-center gap-2">
                            <span class="{{ $seg['color'] }} h-2 w-2 rounded-full ring-1 ring-white/10"></span>
                            <span class="text-xs text-smoke">{{ $seg['label'] }}</span>
                            <span
                                class="rounded-md border border-white/8 bg-white/5 px-1.5 py-0.5 text-[10px] font-semibold text-champagne">{{ $seg['value'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="flex shrink-0 gap-3">
                <a href="{{ route('user-management.index') }}"
                    class="inline-flex items-center gap-1.5 rounded-lg border border-gold/25 bg-gold/8 px-4 py-2 text-xs font-medium text-gold-soft transition-all duration-200 hover:border-gold/40 hover:bg-gold/14">
                    <i class="fa-solid fa-users-gear text-[10px]"></i>
                    Kelola User
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
                @php $isGold = $link['accent'] === 'gold'; @endphp
                <a href="{{ $link['href'] }}"
                    class="{{ $isGold
                        ? 'group relative flex flex-col gap-3 overflow-hidden rounded-2xl border border-gold/25 bg-gold/10 p-4 transition-all duration-300 hover:-translate-y-0.5 hover:border-gold/40 hover:bg-gold/16'
                        : 'group relative flex flex-col gap-3 overflow-hidden rounded-2xl border border-white/8 bg-white/4 p-4 transition-all duration-300 hover:-translate-y-0.5 hover:border-white/14 hover:bg-white/7' }}">
                    <div class="{{ $isGold
                        ? 'flex h-9 w-9 items-center justify-center rounded-xl border border-gold/30 bg-gold/18 text-sm text-gold-soft'
                        : 'flex h-9 w-9 items-center justify-center rounded-xl border border-white/10 bg-white/6 text-sm text-smoke group-hover:text-champagne' }}">
                        <i class="{{ $link['icon'] }}"></i>
                    </div>
                    <div>
                        <p class="{{ $isGold ? 'text-sm font-medium leading-tight text-champagne' : 'text-sm font-medium leading-tight text-white/80 group-hover:text-white' }}">
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
        <div
            class="flex flex-col gap-4 overflow-hidden rounded-2xl border border-white/8 bg-white/3 px-6 py-5 sm:flex-row sm:items-center sm:justify-between">
            <div class="space-y-1">
                <p class="text-[10px] font-semibold uppercase tracking-[0.22em] text-smoke/60">Ringkasan konten</p>
                <p class="text-lg font-semibold text-white">Statistik Pengelolaan</p>
                <p class="text-sm text-smoke">
                    Total <span class="font-medium text-champagne">{{ $totalContentCount }}</span> item dikelola.
                </p>
            </div>

            <div class="w-full sm:w-72">
                <div class="flex h-2 w-full overflow-hidden rounded-full bg-white/6">
                    @foreach ($barSegments as $segment)
                        @if ($segment['value'] > 0)
                            <div class="{{ $segment['color'] }} h-full" style="width: {{ $segment['pct'] }}%"
                                title="{{ $segment['label'] }}: {{ $segment['value'] }}"></div>
                        @endif
                    @endforeach
                </div>
                <div class="mt-3 flex flex-wrap gap-x-4 gap-y-1.5">
                    @foreach ($barSegments as $segment)
                        <div class="flex items-center gap-1.5">
                            <span
                                class="{{ $segment['color'] }} h-1.5 w-1.5 rounded-full ring-1 ring-white/10"></span>
                            <span class="text-[10px] text-smoke">{{ $segment['label'] }}</span>
                            <span class="text-[10px] font-medium text-champagne/70">{{ $segment['value'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
            @foreach ($stats as $stat)
                @php $isGoldTone = $stat['tone'] === 'gold'; @endphp
                <article class="{{ $isGoldTone
                    ? 'rounded-2xl border border-gold/25 bg-gold/10 p-5'
                    : 'rounded-2xl border border-white/8 bg-white/3 p-5' }}">
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <p class="text-[10px] font-semibold uppercase tracking-[0.18em] {{ $isGoldTone ? 'text-gold-soft/80' : 'text-smoke/70' }}">
                                {{ $stat['title'] }}
                            </p>
                            <p class="mt-1 text-3xl font-semibold text-white">{{ $stat['value'] }}</p>
                        </div>
                        <div class="{{ $isGoldTone
                            ? 'flex h-12 w-12 items-center justify-center rounded-2xl border border-gold/30 bg-gold/18 text-gold-soft'
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
        <div
            class="flex flex-col gap-4 border-b border-white/6 px-6 py-5 sm:flex-row sm:items-center sm:justify-between">
            <div class="space-y-1">
                <p class="text-[10px] font-semibold uppercase tracking-[0.22em] text-smoke/60">Produk terbaru</p>
                <h2 class="text-xl font-semibold tracking-[-0.03em] text-white">Aktivitas Katalog Terbaru</h2>
                <p class="text-sm text-smoke">{{ $recentProducts->count() }} produk paling baru yang baru masuk ke
                    sistem.</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('produk.index', ['section' => 'jfx']) }}"
                    class="inline-flex items-center gap-1.5 rounded-lg border border-white/10 bg-white/5 px-3.5 py-2 text-xs font-medium text-smoke transition-all duration-200 hover:border-white/18 hover:bg-white/8 hover:text-champagne">
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
                    <tr
                        class="border-b border-white/5 bg-noir/50 text-left text-[10px] font-semibold uppercase tracking-[0.18em] text-smoke/70">
                        <th class="w-12 px-6 py-3.5">#</th>
                        <th class="px-4 py-3.5">Nama Produk</th>
                        <th class="px-4 py-3.5">Kategori</th>
                        <th class="hidden px-4 py-3.5 lg:table-cell">Deskripsi</th>
                        <th class="px-4 py-3.5 text-right">Dibuat</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse ($recentProducts as $index => $product)
                        <tr class="group transition-colors duration-150 hover:bg-white/3">
                            <td class="px-6 py-4">
                                <span
                                    class="flex h-6 w-6 items-center justify-center rounded-md bg-white/5 text-xs font-medium text-smoke group-hover:bg-white/8">
                                    {{ $index + 1 }}
                                </span>
                            </td>
                            <td class="max-w-[220px] px-4 py-4">
                                <p class="truncate font-medium text-white">{{ $product->nama_produk }}</p>
                            </td>
                            <td class="px-4 py-4">
                                <span
                                    class="inline-flex items-center rounded-md border border-gold/25 bg-gold/12 px-2.5 py-1 text-[10px] font-medium text-gold-soft">
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
                                    <p class="text-xs font-medium text-champagne/80">
                                        {{ $product->created_at?->format('d M Y') ?? '-' }}</p>
                                    <p class="text-[10px] text-smoke/60">
                                        {{ $product->created_at?->format('H:i') ?? '' }}</p>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center">
                                <div class="mx-auto flex max-w-xs flex-col items-center gap-3">
                                    <div
                                        class="flex h-12 w-12 items-center justify-center rounded-2xl border border-white/8 bg-white/4 text-smoke">
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
                class="inline-flex items-center gap-1.5 rounded-lg border border-white/10 bg-white/4 px-3.5 py-2 text-xs font-medium text-champagne/80 transition-all duration-200 hover:border-white/18 hover:bg-white/7 hover:text-white">
                Lihat Semua Produk
                <i class="fa-solid fa-arrow-right text-[9px]"></i>
            </a>
        </div>
    </div>
</section>
