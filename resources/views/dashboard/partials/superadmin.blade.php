@php
    $firstName = trim(explode(' ', $user->name ?? 'Superadmin')[0]);

    $stats = [
        ['label' => 'Produk Multilateral', 'value' => $produkJFXcount, 'icon' => 'fa-solid fa-globe'],
        ['label' => 'Produk Bilateral', 'value' => $produkSPAcount, 'icon' => 'fa-solid fa-handshake'],
        ['label' => 'Banner Aktif', 'value' => $bannerCount, 'icon' => 'fa-solid fa-image'],
        ['label' => 'Pengumuman', 'value' => $informasiCount, 'icon' => 'fa-solid fa-bullhorn'],
        ['label' => 'Penghargaan', 'value' => $penghargaanCount, 'icon' => 'fa-solid fa-award'],
        ['label' => 'User Admin', 'value' => $userCount, 'icon' => 'fa-solid fa-users-gear'],
    ];

    $contentSegments = [
        ['label' => 'Multilateral', 'value' => $produkJFXcount, 'color' => '#c7a15a'],
        ['label' => 'Bilateral', 'value' => $produkSPAcount, 'color' => '#8a6a2e'],
        ['label' => 'Banner', 'value' => $bannerCount, 'color' => '#e0c68a'],
        ['label' => 'Pengumuman', 'value' => $informasiCount, 'color' => '#6b4a22'],
        ['label' => 'Penghargaan', 'value' => $penghargaanCount, 'color' => '#3d2f1a'],
    ];
    $totalContentCount = collect($contentSegments)->sum('value');

    $userSegments = [
        ['label' => 'Superadmin', 'value' => $superadminCount, 'color' => '#c7a15a'],
        ['label' => 'Admin', 'value' => $adminCount, 'color' => '#7c3aed'],
        ['label' => 'Admin Host', 'value' => $adminHostCount, 'color' => '#2563eb'],
    ];

    $quickLinks = [
        [
            'href' => route('produk.index', ['section' => 'jfx']),
            'icon' => 'fa-solid fa-layer-group',
            'label' => 'Produk Multilateral',
        ],
        [
            'href' => route('produk.index', ['section' => 'spa']),
            'icon' => 'fa-solid fa-handshake',
            'label' => 'Produk Bilateral',
        ],
        ['href' => route('user-management.index'), 'icon' => 'fa-solid fa-users-gear', 'label' => 'User Admin'],
        [
            'href' => route('api-documentation.section', ['section' => 'getting-started']),
            'icon' => 'fa-solid fa-book-open-reader',
            'label' => 'Dokumentasi API',
        ],
        ['href' => route('banner.index'), 'icon' => 'fa-solid fa-image', 'label' => 'Banner'],
        ['href' => route('pengumuman.index'), 'icon' => 'fa-solid fa-bullhorn', 'label' => 'Pengumuman'],
        ['href' => route('penghargaan.index'), 'icon' => 'fa-solid fa-award', 'label' => 'Penghargaan'],
        ['href' => route('terms-and-conditions.show'), 'icon' => 'fa-solid fa-scroll', 'label' => 'Syarat & Ketentuan'],
        ['href' => route('privacy-policy.show'), 'icon' => 'fa-solid fa-shield-halved', 'label' => 'Kebijakan Privasi'],
    ];
@endphp

<section class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col gap-5 border-b border-black/8 pb-6 sm:flex-row sm:items-end sm:justify-between">
        <div class="space-y-2">
            <span
                class="inline-flex items-center gap-1.5 text-[11px] font-semibold uppercase tracking-[0.2em] text-gold-soft">
                <span class="h-1.5 w-1.5 rounded-full bg-gold"></span>
                Dashboard Superadmin
            </span>
            <h1 class="text-2xl font-semibold tracking-tight text-ivory sm:text-[1.75rem]">
                Halo, {{ $firstName }}
            </h1>
            <p class="max-w-xl text-sm leading-6 text-smoke">
                Ringkasan akses penuh atas katalog produk, konten publikasi, dan manajemen akun admin.
            </p>
        </div>
        <div class="flex shrink-0 flex-wrap gap-2.5">
            <a href="{{ route('produk.index', ['section' => 'jfx']) }}"
                class="inline-flex items-center gap-2 rounded-xl bg-gold px-4 py-2.5 text-sm font-semibold text-obsidian transition-colors hover:bg-gold-soft hover:text-white">
                <i class="fa-solid fa-layer-group text-xs"></i>
                Kelola Produk
            </a>
            <a href="{{ route('user-management.index') }}"
                class="inline-flex items-center gap-2 rounded-xl border border-black/10 bg-black/4 px-4 py-2.5 text-sm font-medium text-champagne transition-colors hover:border-black/18 hover:bg-black/7">
                <i class="fa-solid fa-users-gear text-xs"></i>
                Kelola User
            </a>
        </div>
    </div>

    {{-- KPI row --}}
    <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-6">
        @foreach ($stats as $stat)
            <div class="rounded-2xl border border-black/8 p-4 transition-colors hover:border-gold/30">
                <div
                    class="flex h-9 w-9 items-center justify-center rounded-xl border border-gold/25 bg-gold/10 text-gold-soft">
                    <i class="{{ $stat['icon'] }} text-sm"></i>
                </div>
                <p class="mt-3 text-2xl font-semibold text-ivory">{{ $stat['value'] }}</p>
                <p class="mt-0.5 text-[11px] leading-tight text-smoke">{{ $stat['label'] }}</p>
            </div>
        @endforeach
    </div>

    {{-- Analytics: content donut + user distribution --}}
    <div class="grid gap-4 lg:grid-cols-[minmax(0,340px)_1fr]">
        <div class="rounded-2xl border border-black/8 p-6">
            <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-smoke/70">Distribusi Konten</p>
            <p class="mt-1 text-sm text-smoke">Total <span
                    class="font-medium text-champagne">{{ $totalContentCount }}</span> item terkelola.</p>

            <div class="mt-5 flex items-center gap-6">
                @include('components.organisms.donut-chart', [
                    'segments' => $contentSegments,
                    'centerValue' => $totalContentCount,
                    'centerLabel' => 'Total',
                ])

                <ul class="min-w-0 flex-1 space-y-2.5">
                    @foreach ($contentSegments as $segment)
                        <li class="flex items-center justify-between gap-3 text-xs">
                            <span class="flex min-w-0 items-center gap-2 text-champagne">
                                <span class="h-2 w-2 shrink-0 rounded-full"
                                    style="background-color: {{ $segment['color'] }}"></span>
                                <span class="truncate">{{ $segment['label'] }}</span>
                            </span>
                            <span class="shrink-0 font-semibold text-ivory">{{ $segment['value'] }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <div class="rounded-2xl border border-black/8 p-6">
            <div class="flex items-center justify-between gap-3">
                <div>
                    <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-smoke/70">Manajemen Akses</p>
                    <p class="mt-1 text-sm text-smoke">Total <span
                            class="font-medium text-champagne">{{ $userCount }}</span> akun terdaftar.</p>
                </div>
                <a href="{{ route('user-management.index') }}"
                    class="inline-flex shrink-0 items-center gap-1.5 rounded-lg border border-black/10 bg-black/4 px-3 py-1.5 text-xs font-medium text-champagne transition-colors hover:border-black/18 hover:bg-black/7">
                    <i class="fa-solid fa-users-gear text-[10px]"></i>
                    Kelola User
                </a>
            </div>

            <div class="mt-5">
                @include('components.organisms.bar-chart', ['items' => $userSegments])
            </div>
        </div>
    </div>

    {{-- Quick links --}}
    <div>
        <p class="mb-3 text-[10px] font-semibold uppercase tracking-[0.2em] text-smoke/70">Akses Cepat</p>
        <div class="grid grid-cols-2 gap-2.5 sm:grid-cols-3">
            @foreach ($quickLinks as $link)
                <a href="{{ $link['href'] }}"
                    class="group flex items-center justify-between gap-3 rounded-xl border border-black/8 p-3.5 transition-colors hover:border-gold/30 hover:bg-gold/5">
                    <div class="flex items-center gap-4">
                        <div
                            class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg border border-black/8 bg-black/4 text-sm text-smoke group-hover:border-gold/25 group-hover:bg-gold/10 group-hover:text-gold">
                            <i class="{{ $link['icon'] }}"></i>
                        </div>
                        <p class="truncate text-sm font-medium text-champagne group-hover:text-ivory">
                            {{ $link['label'] }}
                        </p>
                    </div>

                    <i class="fa-solid fa-arrow-up-right-from-square text-champagne text-xs"></i>
                </a>
            @endforeach
        </div>
    </div>

    {{-- Recent products table --}}
    <div class="overflow-hidden rounded-2xl border border-black/8">
        <div
            class="flex flex-col gap-4 border-b border-black/6 px-6 py-5 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-base font-semibold text-ivory">Aktivitas Katalog Terbaru</h2>
                <p class="text-sm text-smoke">{{ $recentProducts->count() }} produk paling baru yang masuk ke sistem.
                </p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('produk.index', ['section' => 'jfx']) }}"
                    class="inline-flex items-center gap-1.5 rounded-lg border border-black/10 bg-black/4 px-3.5 py-2 text-xs font-medium text-champagne transition-colors hover:border-black/18 hover:bg-black/7">
                    <i class="fa-solid fa-layer-group text-[10px]"></i>
                    Multilateral
                </a>
                <a href="{{ route('produk.index', ['section' => 'spa']) }}"
                    class="inline-flex items-center gap-1.5 rounded-lg border border-black/10 bg-black/4 px-3.5 py-2 text-xs font-medium text-champagne transition-colors hover:border-black/18 hover:bg-black/7">
                    <i class="fa-solid fa-handshake text-[10px]"></i>
                    Bilateral
                </a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr
                        class="border-b border-black/6 bg-noir/50 text-left text-[10px] font-semibold uppercase tracking-[0.18em] text-smoke/70">
                        <th class="w-12 px-6 py-3.5">#</th>
                        <th class="px-4 py-3.5">Nama Produk</th>
                        <th class="px-4 py-3.5">Kategori</th>
                        <th class="hidden px-4 py-3.5 lg:table-cell">Deskripsi</th>
                        <th class="px-4 py-3.5 text-right">Dibuat</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-black/5">
                    @forelse ($recentProducts as $index => $product)
                        <tr class="transition-colors hover:bg-black/3">
                            <td class="px-6 py-4">
                                <span
                                    class="flex h-6 w-6 items-center justify-center rounded-md bg-black/5 text-xs font-medium text-smoke">
                                    {{ $index + 1 }}
                                </span>
                            </td>
                            <td class="max-w-[220px] px-4 py-4">
                                <p class="truncate font-medium text-ivory">{{ $product->nama_produk }}</p>
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
                                <p class="text-xs font-medium text-champagne">
                                    {{ $product->created_at?->format('d M Y') ?? '-' }}</p>
                                <p class="text-[10px] text-smoke/70">{{ $product->created_at?->format('H:i') ?? '' }}
                                </p>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center">
                                <div class="mx-auto flex max-w-xs flex-col items-center gap-3">
                                    <div
                                        class="flex h-12 w-12 items-center justify-center rounded-2xl border border-black/8 bg-black/4 text-smoke">
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

        <div class="flex items-center justify-between border-t border-black/6 bg-noir/30 px-6 py-4">
            <p class="text-xs text-smoke">
                Menampilkan {{ $recentProducts->count() }} dari {{ $produkJFXcount + $produkSPAcount }} total produk
            </p>
            <a href="{{ route('produk.index', ['section' => 'jfx']) }}"
                class="inline-flex items-center gap-1.5 rounded-lg border border-black/10 bg-black/4 px-3.5 py-2 text-xs font-medium text-champagne transition-colors hover:border-black/18 hover:bg-black/7">
                Lihat Semua Produk
                <i class="fa-solid fa-arrow-right text-[9px]"></i>
            </a>
        </div>
    </div>
</section>
