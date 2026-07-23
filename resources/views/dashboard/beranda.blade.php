@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
    @php
        $canManageUserManagement = auth()->user()?->can('manage-user-management') ?? false;

        $statCards = [
            [
                'icon' => 'fa-solid fa-box-archive',
                'title' => 'Total Produk Multilateral',
                'value' => $produkJFXcount,
                'description' => 'Seluruh katalog produk multilateral.',
            ],
            [
                'icon' => 'fa-solid fa-box-archive',
                'title' => 'Total Produk Bilateral',
                'value' => $produkSPAcount,
                'description' => 'Seluruh katalog produk Bilateral.',
            ],
            [
                'icon' => 'fa-solid fa-globe',
                'title' => 'Total Banner',
                'value' => $bannerCount,
                'description' => 'Banner yang ditampilkan.',
            ],
            [
                'icon' => 'fa-solid fa-bullhorn',
                'title' => 'Pengumuman',
                'value' => $informasiCount,
                'description' => 'Materi informasi yang tersedia.',
            ],
            [
                'icon' => 'fa-solid fa-award',
                'title' => 'Penghargaan',
                'value' => $penghargaanCount,
                'description' => 'Dokumentasi penghargaan aktif.',
            ],
            [
                'icon' => 'fa-solid fa-users-gear',
                'title' => 'User Admin',
                'value' => $userCount,
                'description' => "{$superadminCount} superadmin dengan akses penuh.",
            ],
        ];

        $productActions = [
            [
                'href' => route('produk.index', ['section' => 'jfx']),
                'label' => 'Lihat Multilateral',
            ],
            [
                'href' => route('produk.index', ['section' => 'spa']),
                'label' => 'Lihat Bilateral',
            ],
        ];

        $totalContentCount = $produkJFXcount + $produkSPAcount + $bannerCount + $informasiCount + $penghargaanCount;

        $quickLinks = [
            [
                'href'        => route('produk.index', ['section' => 'jfx']),
                'icon'        => 'fa-solid fa-layer-group',
                'label'       => 'Produk Multilateral',
                'description' => "{$produkJFXcount} produk aktif",
                'accent'      => 'gold',
            ],
            [
                'href'        => route('produk.index', ['section' => 'spa']),
                'icon'        => 'fa-solid fa-handshake',
                'label'       => 'Produk Bilateral',
                'description' => "{$produkSPAcount} produk aktif",
                'accent'      => 'gold',
            ],
            [
                'href'        => route('banner.index'),
                'icon'        => 'fa-solid fa-image',
                'label'       => 'Banner',
                'description' => "{$bannerCount} banner aktif",
                'accent'      => 'white',
            ],
            [
                'href'        => route('pengumuman.index'),
                'icon'        => 'fa-solid fa-bullhorn',
                'label'       => 'Pengumuman',
                'description' => "{$informasiCount} tayang",
                'accent'      => 'white',
            ],
            [
                'href'        => route('penghargaan.index'),
                'icon'        => 'fa-solid fa-award',
                'label'       => 'Penghargaan',
                'description' => "{$penghargaanCount} terdokumentasi",
                'accent'      => 'white',
            ],
            [
                'href'        => route('terms-and-conditions.show'),
                'icon'        => 'fa-solid fa-scroll',
                'label'       => 'Syarat dan Ketentuan',
                'description' => 'Dokumen kebijakan utama',
                'accent'      => 'white',
            ],
            [
                'href'        => route('privacy-policy.show'),
                'icon'        => 'fa-solid fa-shield-halved',
                'label'       => 'Kebijakan Privasi',
                'description' => 'Dokumen privasi perusahaan',
                'accent'      => 'white',
            ],
        ];

        if ($canManageUserManagement) {
            $quickLinks[] = [
                'href'        => route('user-management.index'),
                'icon'        => 'fa-solid fa-users-gear',
                'label'       => 'User Admin',
                'description' => "{$userCount} pengguna",
                'accent'      => 'white',
            ];
        }
    @endphp

    <section class="space-y-6">

        {{-- ══════════════════════════════════════════════
             HERO BANNER
        ══════════════════════════════════════════════ --}}
        <div class="relative overflow-hidden rounded-[32px] border border-white/8 bg-[radial-gradient(ellipse_80%_60%_at_0%_0%,rgba(199,161,90,0.18),transparent),linear-gradient(160deg,rgba(255,255,255,0.05)_0%,rgba(255,255,255,0.01)_100%)] shadow-[0_32px_80px_rgba(0,0,0,0.35)] motion-safe:motion-preset-slide-down-sm">

            {{-- Decorative orbs --}}
            <div class="pointer-events-none absolute -right-20 -top-20 h-64 w-64 rounded-full bg-gold/10 blur-[80px] motion-safe:motion-preset-fade-lg motion-safe:motion-delay-[100ms]"></div>
            <div class="pointer-events-none absolute -bottom-10 left-1/3 h-48 w-48 rounded-full bg-gold/6 blur-[60px] motion-safe:motion-preset-fade-lg motion-safe:motion-delay-[250ms]"></div>
            <div class="pointer-events-none absolute right-1/4 top-1/2 h-32 w-32 rounded-full bg-white/4 blur-[40px] motion-safe:motion-preset-fade-lg motion-safe:motion-delay-[400ms]"></div>

            {{-- Gold shimmer top edge --}}
            <div class="pointer-events-none absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-gold/40 to-transparent"></div>

            <div class="relative grid gap-0 lg:grid-cols-[1fr_auto]">

                {{-- Left: headline + actions --}}
                <div class="space-y-6 p-7 lg:p-10">

                    {{-- Eyebrow badge --}}
                    <div class="motion-safe:motion-preset-slide-right-sm motion-safe:motion-delay-[60ms]">
                        <span class="inline-flex items-center gap-2 rounded-full border border-gold/20 bg-gold/8 px-3.5 py-1.5 text-[10px] font-semibold uppercase tracking-[0.24em] text-gold-soft/90">
                            <span class="h-1.5 w-1.5 animate-pulse rounded-full bg-gold"></span>
                            Dashboard Admin · SGB
                        </span>
                    </div>

                    {{-- Title --}}
                    <div class="space-y-4 motion-safe:motion-preset-slide-right-sm motion-safe:motion-delay-[120ms]">
                        <h1 class="max-w-2xl text-3xl font-semibold leading-[1.15] tracking-[-0.04em] text-white lg:text-[2.75rem]">
                            Pantau semua konten<br>
                            <span class="bg-gradient-to-r from-gold-soft via-champagne to-gold-soft bg-clip-text text-transparent">dari satu panel terpadu.</span>
                        </h1>
                        <p class="max-w-xl text-sm leading-7 text-smoke lg:text-base">
                            Kelola katalog produk, banner aktif, pengumuman, penghargaan, dan akses user admin — semuanya dengan visibilitas real-time.
                        </p>
                    </div>

                    {{-- CTA buttons --}}
                    <div class="flex flex-wrap gap-3 motion-safe:motion-preset-slide-up-sm motion-safe:motion-delay-[200ms]">
                        <a href="{{ route('produk.index', ['section' => 'jfx']) }}"
                            class="inline-flex items-center gap-2 rounded-xl bg-gold px-5 py-2.5 text-sm font-semibold text-obsidian shadow-[0_4px_20px_rgba(199,161,90,0.3)] transition-all duration-200 hover:bg-gold-soft hover:shadow-[0_6px_28px_rgba(199,161,90,0.45)]">
                            <i class="fa-solid fa-layer-group text-xs"></i>
                            Kelola Produk
                        </a>
                        @if ($canManageUserManagement)
                            <a href="{{ route('user-management.index') }}"
                                class="inline-flex items-center gap-2 rounded-xl border border-white/12 bg-white/6 px-5 py-2.5 text-sm font-medium text-white backdrop-blur-sm transition-all duration-200 hover:border-white/20 hover:bg-white/10">
                                <i class="fa-solid fa-users-gear text-xs text-smoke"></i>
                                Kelola User
                            </a>
                        @endif
                    </div>

                    {{-- Inline mini-stats strip --}}
                    @php
                        $miniStats = [
                            ['label' => 'Produk', 'value' => $produkJFXcount + $produkSPAcount],
                            ['label' => 'Banner Aktif', 'value' => $bannerCount],
                            ['label' => 'Pengumuman', 'value' => $informasiCount],
                            ['label' => 'Penghargaan', 'value' => $penghargaanCount],
                        ];
                    @endphp
                    <div class="flex flex-wrap items-center gap-x-6 gap-y-3 border-t border-white/6 pt-5 motion-safe:motion-preset-slide-up-sm motion-safe:motion-delay-[280ms]">
                        @foreach ($miniStats as $ms)
                            <div class="flex items-baseline gap-2">
                                <span class="text-2xl font-semibold text-white">{{ $ms['value'] }}</span>
                                <span class="text-xs text-smoke">{{ $ms['label'] }}</span>
                            </div>
                            @if (!$loop->last)
                                <span class="h-4 w-px bg-white/10"></span>
                            @endif
                        @endforeach
                    </div>
                </div>

                {{-- Right: total-content ring card --}}
                <div class="flex items-center justify-center border-t border-white/6 p-7 lg:border-l lg:border-t-0 lg:p-10 motion-safe:motion-preset-slide-left-sm motion-safe:motion-delay-[180ms]">
                    <div class="relative flex h-52 w-52 flex-col items-center justify-center rounded-full border border-gold/20 bg-gradient-to-br from-gold/12 via-gold/6 to-transparent shadow-[inset_0_1px_1px_rgba(255,255,255,0.06),0_20px_60px_rgba(0,0,0,0.3)]">

                        {{-- Rotating dashed ring --}}
                        <svg class="absolute inset-0 h-full w-full -rotate-90 animate-[spin_30s_linear_infinite]" viewBox="0 0 208 208">
                            <circle cx="104" cy="104" r="100" fill="none" stroke="rgba(199,161,90,0.2)" stroke-width="1" stroke-dasharray="6 6" />
                        </svg>

                        {{-- Accent dots --}}
                        <div class="absolute -right-1 top-1/4 h-2 w-2 rounded-full bg-gold/60 shadow-[0_0_8px_rgba(199,161,90,0.8)]"></div>
                        <div class="absolute -left-1 bottom-1/3 h-1.5 w-1.5 rounded-full bg-gold/40"></div>

                        <div class="z-10 space-y-1 text-center">
                            <p class="text-[10px] font-semibold uppercase tracking-[0.22em] text-gold-soft/70">Total Konten</p>
                            <p class="text-5xl font-semibold text-white">{{ $totalContentCount }}</p>
                            <p class="text-xs text-smoke">item dikelola</p>
                        </div>

                        {{-- Aktivitas terbaru badge --}}
                        <div class="absolute -bottom-5 left-1/2 -translate-x-1/2 whitespace-nowrap rounded-full border border-white/10 bg-noir/90 px-4 py-1.5 text-[11px] font-medium text-champagne/80 shadow-lg backdrop-blur-sm">
                            <i class="fa-solid fa-clock-rotate-left mr-1.5 text-[9px] text-gold-soft/70"></i>
                            {{ $recentProducts->count() }} produk terbaru
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{-- ══════════════════════════════════════════════
             QUICK-ACCESS NAVIGATION CARDS
        ══════════════════════════════════════════════ --}}
        <div>
            <p class="mb-3 text-[10px] font-semibold uppercase tracking-[0.22em] text-smoke/60 motion-safe:motion-preset-fade-lg">Akses cepat</p>
            <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 xl:grid-cols-6">
                @php
                    $quickDelays = [
                        'motion-safe:motion-delay-0',
                        'motion-safe:motion-delay-[60ms]',
                        'motion-safe:motion-delay-[120ms]',
                        'motion-safe:motion-delay-[180ms]',
                        'motion-safe:motion-delay-[240ms]',
                        'motion-safe:motion-delay-[300ms]',
                    ];
                @endphp
                @foreach ($quickLinks as $i => $link)
                    @php $isGold = $link['accent'] === 'gold'; @endphp
                    <a href="{{ $link['href'] }}"
                        class="{{ cn(
                            'group relative flex flex-col gap-3 overflow-hidden rounded-2xl border p-4 transition-all duration-300 hover:-translate-y-0.5 motion-safe:motion-preset-slide-up-sm',
                            $quickDelays[$i] ?? 'motion-safe:motion-delay-[360ms]',
                            $isGold
                                ? 'border-gold/25 bg-gold/10 hover:border-gold/40 hover:bg-gold/16 hover:shadow-[0_12px_30px_rgba(199,161,90,0.14)]'
                                : 'border-white/8 bg-white/4 hover:border-white/14 hover:bg-white/7 hover:shadow-[0_8px_24px_rgba(0,0,0,0.2)]',
                        ) }}">
                        <div class="{{ cn(
                            'flex h-9 w-9 items-center justify-center rounded-xl text-sm',
                            $isGold
                                ? 'border border-gold/30 bg-gold/18 text-gold-soft'
                                : 'border border-white/10 bg-white/6 text-smoke group-hover:text-champagne',
                        ) }}">
                            <i class="{{ $link['icon'] }}"></i>
                        </div>
                        <div>
                            <p class="{{ cn('text-sm font-medium leading-tight', $isGold ? 'text-champagne' : 'text-white/80 group-hover:text-white') }}">
                                {{ $link['label'] }}
                            </p>
                            <p class="mt-0.5 text-xs text-smoke">{{ $link['description'] }}</p>
                        </div>
                        {{-- Hover shimmer line --}}
                        <div class="{{ cn(
                            'pointer-events-none absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent to-transparent opacity-0 transition-opacity duration-300 group-hover:opacity-100',
                            $isGold ? 'via-gold/40' : 'via-white/20',
                        ) }}"></div>
                    </a>
                @endforeach
            </div>
        </div>

        {{-- ══════════════════════════════════════════════
             STATS GRID — with section header card
        ══════════════════════════════════════════════ --}}
        <div class="space-y-3 motion-safe:motion-preset-fade-lg">

            {{-- Section header card --}}
            <div class="flex flex-col gap-4 overflow-hidden rounded-2xl border border-white/8 bg-white/3 px-6 py-5 sm:flex-row sm:items-center sm:justify-between">
                <div class="space-y-1">
                    <p class="text-[10px] font-semibold uppercase tracking-[0.22em] text-smoke/60">Ringkasan konten</p>
                    <p class="text-lg font-semibold text-white">Statistik Pengelolaan</p>
                    <p class="text-sm text-smoke">
                        Total <span class="font-medium text-champagne">{{ $totalContentCount }}</span> item dikelola — terdiri dari produk, banner, pengumuman, dan penghargaan.
                    </p>
                </div>

                {{-- Composition bar --}}
                @php
                    $produkTotal  = $produkJFXcount + $produkSPAcount;
                    $safeTotal    = max($totalContentCount, 1);
                    $barSegments  = [
                        ['label' => 'Multilateral', 'value' => $produkJFXcount,  'color' => 'bg-gold',       'pct' => round($produkJFXcount  / $safeTotal * 100, 1)],
                        ['label' => 'Bilateral',    'value' => $produkSPAcount,  'color' => 'bg-gold-soft',  'pct' => round($produkSPAcount  / $safeTotal * 100, 1)],
                        ['label' => 'Banner',       'value' => $bannerCount,     'color' => 'bg-smoke/50',   'pct' => round($bannerCount     / $safeTotal * 100, 1)],
                        ['label' => 'Pengumuman',   'value' => $informasiCount,  'color' => 'bg-white/30',   'pct' => round($informasiCount  / $safeTotal * 100, 1)],
                        ['label' => 'Penghargaan',  'value' => $penghargaanCount,'color' => 'bg-white/15',   'pct' => round($penghargaanCount/ $safeTotal * 100, 1)],
                    ];
                @endphp

                <div class="w-full sm:w-72">
                    {{-- Segmented bar --}}
                    <div class="flex h-2 w-full overflow-hidden rounded-full bg-white/6">
                        @foreach ($barSegments as $seg)
                            @if ($seg['value'] > 0)
                                <div class="{{ $seg['color'] }} h-full transition-all duration-500"
                                    style="width: {{ $seg['pct'] }}%"
                                    title="{{ $seg['label'] }}: {{ $seg['value'] }}"></div>
                            @endif
                        @endforeach
                    </div>
                    {{-- Legend --}}
                    <div class="mt-3 flex flex-wrap gap-x-4 gap-y-1.5">
                        @foreach ($barSegments as $seg)
                            <div class="flex items-center gap-1.5">
                                <span class="{{ $seg['color'] }} h-1.5 w-1.5 rounded-full ring-1 ring-white/10"></span>
                                <span class="text-[10px] text-smoke">{{ $seg['label'] }}</span>
                                <span class="text-[10px] font-medium text-champagne/70">{{ $seg['value'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            @include('components.organisms.stats-grid', ['stats' => $statCards])
        </div>

        {{-- ══════════════════════════════════════════════
             RECENT PRODUCTS PANEL — redesigned inline
        ══════════════════════════════════════════════ --}}
        <div class="overflow-hidden rounded-2xl border border-white/8 bg-white/3 motion-safe:motion-preset-slide-up-sm motion-safe:motion-delay-[220ms]">

            {{-- Panel header --}}
            <div class="flex flex-col gap-4 border-b border-white/6 px-6 py-5 sm:flex-row sm:items-center sm:justify-between">
                <div class="space-y-1">
                    <p class="text-[10px] font-semibold uppercase tracking-[0.22em] text-smoke/60">Produk terbaru</p>
                    <h2 class="text-xl font-semibold tracking-[-0.03em] text-white">Aktivitas Katalog Terbaru</h2>
                    <p class="text-sm text-smoke">{{ $recentProducts->count() }} produk paling baru — ditampilkan secara real-time.</p>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('produk.index', ['section' => 'jfx']) }}"
                        class="inline-flex items-center gap-1.5 rounded-lg border border-gold/25 bg-gold/10 px-3.5 py-2 text-xs font-medium text-gold-soft transition-all duration-200 hover:border-gold/40 hover:bg-gold/18">
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

            {{-- Table --}}
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
                            <tr class="group transition-colors duration-150 hover:bg-white/3">
                                {{-- Row number --}}
                                <td class="px-6 py-4">
                                    <span class="flex h-6 w-6 items-center justify-center rounded-md bg-white/5 text-xs font-medium text-smoke group-hover:bg-white/8">
                                        {{ $index + 1 }}
                                    </span>
                                </td>

                                {{-- Product name --}}
                                <td class="max-w-[220px] px-4 py-4">
                                    <p class="truncate font-medium text-white">{{ $product->nama_produk }}</p>
                                </td>

                                {{-- Category badge --}}
                                <td class="px-4 py-4">
                                    @if ($product->kategori === 'JFX')
                                        <span class="inline-flex items-center gap-1.5 rounded-md border border-gold/25 bg-gold/12 px-2.5 py-1 text-[10px] font-semibold uppercase tracking-[0.12em] text-gold-soft">
                                            <span class="h-1 w-1 rounded-full bg-gold"></span>
                                            Multilateral
                                        </span>
                                    @elseif ($product->kategori === 'SPA')
                                        <span class="inline-flex items-center gap-1.5 rounded-md border border-blue-400/20 bg-blue-400/8 px-2.5 py-1 text-[10px] font-semibold uppercase tracking-[0.12em] text-blue-300/80">
                                            <span class="h-1 w-1 rounded-full bg-blue-400/70"></span>
                                            Bilateral
                                        </span>
                                    @else
                                        <span class="inline-flex items-center rounded-md border border-white/10 bg-white/5 px-2.5 py-1 text-[10px] font-medium text-smoke">
                                            {{ $product->kategori }}
                                        </span>
                                    @endif
                                </td>

                                {{-- Description (hidden on mobile) --}}
                                <td class="hidden max-w-xs px-4 py-4 lg:table-cell">
                                    <p class="truncate text-sm text-smoke">
                                        {{ \Illuminate\Support\Str::limit(strip_tags($product->deskripsi_produk), 80) }}
                                    </p>
                                </td>

                                {{-- Created at --}}
                                <td class="px-4 py-4 text-right">
                                    <div class="space-y-0.5">
                                        <p class="text-xs font-medium text-champagne/80">
                                            {{ $product->created_at?->format('d M Y') ?? '-' }}
                                        </p>
                                        <p class="text-[10px] text-smoke/60">
                                            {{ $product->created_at?->format('H:i') ?? '' }}
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-16 text-center">
                                    <div class="mx-auto flex max-w-xs flex-col items-center gap-3">
                                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl border border-white/8 bg-white/4 text-smoke">
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

            {{-- Panel footer --}}
            <div class="flex items-center justify-between border-t border-white/6 bg-noir/30 px-6 py-4">
                <p class="text-xs text-smoke">
                    Menampilkan <span class="font-medium text-champagne/80">{{ $recentProducts->count() }}</span> dari
                    <span class="font-medium text-champagne/80">{{ $produkJFXcount + $produkSPAcount }}</span> total produk
                </p>
                <a href="{{ route('produk.index', ['section' => 'jfx']) }}"
                    class="inline-flex items-center gap-1.5 rounded-lg border border-white/10 bg-white/4 px-3.5 py-2 text-xs font-medium text-champagne/80 transition-all duration-200 hover:border-white/18 hover:bg-white/7 hover:text-white">
                    Lihat Semua Produk
                    <i class="fa-solid fa-arrow-right text-[9px]"></i>
                </a>
            </div>
        </div>

    </section>
@endsection
