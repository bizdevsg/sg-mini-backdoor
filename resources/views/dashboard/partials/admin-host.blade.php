@php
    $firstName = trim(explode(' ', $user->name ?? 'Admin Host')[0]);

    $stats = [
        ['label' => 'Total Signal', 'value' => $signalCount, 'icon' => 'fa-solid fa-signal'],
        ['label' => 'Total Berita', 'value' => $beritaCount, 'icon' => 'fa-solid fa-newspaper'],
        ['label' => 'Kategori Ebook', 'value' => $ebookCategoryCount, 'icon' => 'fa-solid fa-folder-open'],
        ['label' => 'Total Ebook', 'value' => $ebookCount, 'icon' => 'fa-solid fa-book'],
    ];

    $contentSegments = [
        ['label' => 'Berita', 'value' => $beritaCount, 'color' => '#2563eb'],
        ['label' => 'Signal', 'value' => $signalCount, 'color' => '#60a5fa'],
        ['label' => 'Kategori Ebook', 'value' => $ebookCategoryCount, 'color' => '#93c5fd'],
        ['label' => 'Ebook', 'value' => $ebookCount, 'color' => '#1e3a8a'],
    ];
    $totalContentCount = collect($contentSegments)->sum('value');

    $quickLinks = [
        ['href' => route('berita-categories.index'), 'icon' => 'fa-solid fa-newspaper', 'label' => 'Berita'],
        ['href' => route('signal-categories.index'), 'icon' => 'fa-solid fa-image', 'label' => 'Signal'],
        ['href' => route('ebook-categories.index'), 'icon' => 'fa-solid fa-book', 'label' => 'Ebook'],
    ];
@endphp

<section class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col gap-5 border-b border-black/8 pb-6 sm:flex-row sm:items-end sm:justify-between">
        <div class="space-y-2">
            <span
                class="inline-flex items-center gap-1.5 text-[11px] font-semibold uppercase tracking-[0.2em] text-blue-700">
                <span class="h-1.5 w-1.5 rounded-full bg-blue-500"></span>
                Dashboard Admin Host
            </span>
            <h1 class="text-2xl font-semibold tracking-tight text-ivory sm:text-[1.75rem]">
                Halo, {{ $firstName }}
            </h1>
            <p class="max-w-xl text-sm leading-6 text-smoke">
                Pantau berita, signal, dan ebook dari satu beranda yang ringkas.
            </p>
        </div>
        <div class="flex shrink-0 flex-wrap gap-2.5">
            <a href="{{ route('berita-categories.index') }}"
                class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white transition-colors hover:bg-blue-700">
                <i class="fa-solid fa-newspaper text-xs"></i>
                Kelola Berita
            </a>
            <a href="{{ route('signal-categories.index') }}"
                class="inline-flex items-center gap-2 rounded-xl border border-black/10 bg-black/4 px-4 py-2.5 text-sm font-medium text-champagne transition-colors hover:border-black/18 hover:bg-black/7">
                <i class="fa-solid fa-image text-xs"></i>
                Kelola Signal
            </a>
        </div>
    </div>

    {{-- KPI row --}}
    <div class="grid grid-cols-2 gap-3 sm:grid-cols-4">
        @foreach ($stats as $stat)
            <div class="rounded-2xl border border-black/8 p-4 transition-colors hover:border-blue-500/30">
                <div
                    class="flex h-9 w-9 items-center justify-center rounded-xl border border-blue-500/25 bg-blue-500/10 text-blue-700">
                    <i class="{{ $stat['icon'] }} text-sm"></i>
                </div>
                <p class="mt-3 text-2xl font-semibold text-ivory">{{ $stat['value'] }}</p>
                <p class="mt-0.5 text-[11px] leading-tight text-smoke">{{ $stat['label'] }}</p>
            </div>
        @endforeach
    </div>

    {{-- Analytics: content donut + ranked comparison --}}
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
            <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-smoke/70">Perbandingan Kategori</p>
            <p class="mt-1 text-sm text-smoke">Volume setiap kategori konten yang dikelola.</p>

            <div class="mt-5">
                @include('components.organisms.bar-chart', ['items' => $contentSegments])
            </div>
        </div>
    </div>

    {{-- Quick links --}}
    <div>
        <p class="mb-3 text-[10px] font-semibold uppercase tracking-[0.2em] text-smoke/70">Akses Cepat</p>
        <div class="grid grid-cols-2 gap-2.5 sm:grid-cols-3">
            @foreach ($quickLinks as $link)
                <a href="{{ $link['href'] }}"
                    class="group flex items-center justify-between gap-3 rounded-xl border border-black/8 p-3.5 transition-colors hover:border-blue-500/30 hover:bg-blue-500/5">
                    <div class="flex items-center gap-4">
                        <div
                            class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg border border-black/8 bg-black/4 text-sm text-smoke group-hover:border-blue-500/25 group-hover:bg-blue-500/10 group-hover:text-blue-500">
                            <i class="{{ $link['icon'] }}"></i>
                        </div>
                        <p class="truncate text-xs font-medium text-champagne group-hover:text-ivory">
                            {{ $link['label'] }}
                        </p>
                    </div>

                    <i class="fa-solid fa-arrow-up-right-from-square text-champagne"></i>
                </a>
            @endforeach
        </div>
    </div>

    {{-- Recent ebooks table --}}
    <div class="overflow-hidden rounded-2xl border border-black/8">
        <div
            class="flex flex-col gap-4 border-b border-black/6 px-6 py-5 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-base font-semibold text-ivory">Aktivitas Ebook Terbaru</h2>
                <p class="text-sm text-smoke">{{ $recentEbooks->count() }} ebook paling baru yang masuk ke sistem.</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('ebook-categories.index') }}"
                    class="inline-flex items-center gap-1.5 rounded-lg border border-black/10 bg-black/4 px-3.5 py-2 text-xs font-medium text-champagne transition-colors hover:border-black/18 hover:bg-black/7">
                    <i class="fa-solid fa-book text-[10px]"></i>
                    Kategori Ebook
                </a>
                <a href="{{ route('berita-categories.index') }}"
                    class="inline-flex items-center gap-1.5 rounded-lg border border-black/10 bg-black/4 px-3.5 py-2 text-xs font-medium text-champagne transition-colors hover:border-black/18 hover:bg-black/7">
                    <i class="fa-solid fa-newspaper text-[10px]"></i>
                    Berita
                </a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr
                        class="border-b border-black/6 bg-noir/50 text-left text-[10px] font-semibold uppercase tracking-[0.18em] text-smoke/70">
                        <th class="w-12 px-6 py-3.5">#</th>
                        <th class="px-4 py-3.5">Judul Ebook</th>
                        <th class="px-4 py-3.5">Kategori</th>
                        <th class="hidden px-4 py-3.5 lg:table-cell">Slug</th>
                        <th class="px-4 py-3.5 text-right">Dibuat</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-black/5">
                    @forelse ($recentEbooks as $index => $ebook)
                        <tr class="transition-colors hover:bg-black/3">
                            <td class="px-6 py-4">
                                <span
                                    class="flex h-6 w-6 items-center justify-center rounded-md bg-black/5 text-xs font-medium text-smoke">
                                    {{ $index + 1 }}
                                </span>
                            </td>
                            <td class="max-w-[220px] px-4 py-4">
                                <p class="truncate font-medium text-ivory">{{ $ebook->title }}</p>
                            </td>
                            <td class="px-4 py-4">
                                <span
                                    class="inline-flex items-center rounded-md border border-blue-500/30 bg-blue-500/12 px-2.5 py-1 text-[10px] font-medium text-blue-700">
                                    {{ $ebook->category?->name ?? '-' }}
                                </span>
                            </td>
                            <td class="hidden max-w-xs px-4 py-4 lg:table-cell">
                                <p class="truncate text-sm text-smoke">{{ $ebook->slug }}</p>
                            </td>
                            <td class="px-4 py-4 text-right">
                                <p class="text-xs font-medium text-champagne">
                                    {{ $ebook->created_at?->format('d M Y') ?? '-' }}</p>
                                <p class="text-[10px] text-smoke/70">{{ $ebook->created_at?->format('H:i') ?? '' }}</p>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center">
                                <div class="mx-auto flex max-w-xs flex-col items-center gap-3">
                                    <div
                                        class="flex h-12 w-12 items-center justify-center rounded-2xl border border-blue-500/20 bg-blue-500/7 text-blue-700">
                                        <i class="fa-solid fa-book-open text-lg"></i>
                                    </div>
                                    <p class="text-sm text-smoke">Belum ada ebook terbaru untuk ditampilkan.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="flex items-center justify-between border-t border-black/6 bg-noir/30 px-6 py-4">
            <p class="text-xs text-smoke">
                Menampilkan {{ $recentEbooks->count() }} dari {{ $ebookCount }} total ebook
            </p>
            <a href="{{ route('ebook-categories.index') }}"
                class="inline-flex items-center gap-1.5 rounded-lg border border-black/10 bg-black/4 px-3.5 py-2 text-xs font-medium text-champagne transition-colors hover:border-black/18 hover:bg-black/7">
                Lihat Semua Ebook
                <i class="fa-solid fa-arrow-right text-[9px]"></i>
            </a>
        </div>
    </div>
</section>
