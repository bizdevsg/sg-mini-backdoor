@php
    $quickLinks = [
        [
            'href' => route('berita-categories.index'),
            'icon' => 'fa-solid fa-newspaper',
            'label' => 'Berita',
            'description' => "{$beritaCount} berita di {$beritaCategoryCount} kategori",
            'accent' => 'blue',
        ],
        [
            'href' => route('signal-categories.index'),
            'icon' => 'fa-solid fa-image',
            'label' => 'Signal',
            'description' => "{$signalCount} signal di {$signalCategoryCount} kategori",
            'accent' => 'blue',
        ],
        [
            'href' => route('ebook-categories.index'),
            'icon' => 'fa-solid fa-book',
            'label' => 'Ebook',
            'description' => "{$ebookCount} ebook di {$ebookCategoryCount} kategori",
            'accent' => 'white',
        ],
    ];

    $stats = [
        [
            'title' => 'Total Signal',
            'value' => $signalCount,
            'icon' => 'fa-solid fa-signal',
            'description' => 'Seluruh konten signal yang dikelola.',
            'tone' => 'blue',
        ],
        [
            'title' => 'Total Berita',
            'value' => $beritaCount,
            'icon' => 'fa-solid fa-newspaper',
            'description' => 'Seluruh konten berita yang tersedia.',
            'tone' => 'white',
        ],
        [
            'title' => 'Kategori Ebook',
            'value' => $ebookCategoryCount,
            'icon' => 'fa-solid fa-folder-open',
            'description' => 'Kelompok dokumen ebook aktif.',
            'tone' => 'white',
        ],
        [
            'title' => 'Total Ebook',
            'value' => $ebookCount,
            'icon' => 'fa-solid fa-book',
            'description' => 'Dokumen ebook yang dikelola.',
            'tone' => 'white',
        ],
    ];

    $miniStats = [
        ['label' => 'Berita', 'value' => $beritaCount],
        ['label' => 'Signal', 'value' => $signalCount],
        ['label' => 'Kategori Ebook', 'value' => $ebookCategoryCount],
        ['label' => 'Ebook', 'value' => $ebookCount],
    ];

    $totalContentCount = $signalCount + $beritaCount + $ebookCategoryCount + $ebookCount;
    $safeTotal = max($totalContentCount, 1);
    $barSegments = [
        ['label' => 'Berita', 'value' => $beritaCount, 'color' => 'bg-blue-500'],
        ['label' => 'Signal', 'value' => $signalCount, 'color' => 'bg-blue-400/70'],
        ['label' => 'Kategori Ebook', 'value' => $ebookCategoryCount, 'color' => 'bg-white/30'],
        ['label' => 'Ebook', 'value' => $ebookCount, 'color' => 'bg-white/15'],
    ];
    $barSegments = array_map(
        static fn(array $segment): array => [...$segment, 'pct' => round(($segment['value'] / $safeTotal) * 100, 1)],
        $barSegments,
    );
@endphp

<section class="space-y-6">

    {{-- =========================================================
         HERO CARD — Content / Media (Blue-500)
         ========================================================= --}}
    <div
        class="relative overflow-hidden rounded-[32px] border border-white/8 bg-[radial-gradient(ellipse_70%_70%_at_0%_0%,rgba(59,130,246,0.18),transparent),radial-gradient(ellipse_40%_50%_at_100%_100%,rgba(59,130,246,0.06),transparent),linear-gradient(160deg,rgba(255,255,255,0.04)_0%,rgba(255,255,255,0.01)_100%)] shadow-[0_32px_80px_rgba(0,0,0,0.4)]">

        {{-- Ambient glows --}}
        <div
            class="pointer-events-none absolute -left-10 -top-10 h-80 w-80 rounded-full bg-[rgba(59,130,246,0.1)] blur-[100px]">
        </div>
        <div
            class="pointer-events-none absolute -right-20 bottom-0 h-60 w-60 rounded-full bg-[rgba(59,130,246,0.06)] blur-[80px]">
        </div>
        <div
            class="pointer-events-none absolute bottom-1/3 left-1/2 h-48 w-48 rounded-full bg-[rgba(59,130,246,0.05)] blur-[60px]">
        </div>

        {{-- Top shimmer line — blue --}}
        <div
            class="pointer-events-none absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-blue-500/60 to-transparent">
        </div>

        <div class="relative grid gap-0 lg:grid-cols-[1fr_auto]">
            <div class="space-y-6 p-7 lg:p-10">

                {{-- Role badge --}}
                <div class="flex items-center gap-3">
                    <span
                        class="inline-flex items-center gap-2 rounded-full border border-blue-500/35 bg-blue-500/10 px-3.5 py-1.5 text-[10px] font-semibold uppercase tracking-[0.24em] text-blue-300/90">
                        <span class="h-1.5 w-1.5 animate-pulse rounded-full bg-blue-500/90"></span>
                        Dashboard Admin Host / SGB
                    </span>
                    <span
                        class="inline-flex items-center gap-1.5 rounded-full border border-white/10 bg-white/5 px-2.5 py-1 text-[9px] font-semibold uppercase tracking-[0.2em] text-smoke/80">
                        <i class="fa-solid fa-pen-nib text-[8px]"></i>
                        Konten & Media
                    </span>
                </div>

                {{-- Headline --}}
                <div class="space-y-3">
                    <h1
                        class="max-w-2xl text-3xl font-semibold leading-[1.15] tracking-[-0.04em] text-white lg:text-[2.75rem]">
                        Pantau berita, signal, dan ebook<br>
                        <span
                            class="bg-gradient-to-r from-blue-400 via-blue-300/95 to-blue-400/80 bg-clip-text text-transparent">dari
                            satu beranda yang ringkas.</span>
                    </h1>
                    <p class="max-w-xl text-sm leading-7 text-smoke lg:text-base">
                        Kelola berita, signal, kategori ebook, dan dokumen ebook dari satu beranda yang memang dibuka
                        untuk Admin Host.
                    </p>
                </div>

                {{-- CTA buttons --}}
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('berita-categories.index') }}"
                        class="inline-flex items-center gap-2 rounded-xl bg-blue-500 px-5 py-2.5 text-sm font-semibold text-white shadow-[0_4px_20px_rgba(59,130,246,0.4)] transition-all duration-200 hover:bg-blue-600 hover:shadow-[0_6px_28px_rgba(59,130,246,0.55)]">
                        <i class="fa-solid fa-newspaper text-xs"></i>
                        Kelola Berita
                    </a>
                    <a href="{{ route('signal-categories.index') }}"
                        class="inline-flex items-center gap-2 rounded-xl border border-white/12 bg-white/6 px-5 py-2.5 text-sm font-medium text-white backdrop-blur-sm transition-all duration-200 hover:border-white/20 hover:bg-white/10">
                        <i class="fa-solid fa-image text-xs"></i>
                        Kelola Signal
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
                </div>
            </div>

            {{-- Right panel: Total counter --}}
            <div
                class="flex items-center justify-center border-t border-white/6 p-7 lg:border-l lg:border-t-0 lg:p-10">
                <div
                    class="relative flex h-52 w-52 flex-col items-center justify-center rounded-full border border-blue-500/25 bg-gradient-to-br from-blue-500/14 via-blue-500/06 to-transparent shadow-[inset_0_1px_1px_rgba(255,255,255,0.06),0_20px_60px_rgba(0,0,0,0.3)]">
                    {{-- Animated dashed ring — blue --}}
                    <svg class="absolute inset-0 h-full w-full -rotate-90 animate-[spin_30s_linear_infinite]"
                        viewBox="0 0 208 208">
                        <circle cx="104" cy="104" r="100" fill="none" stroke="rgba(59,130,246,0.4)"
                            stroke-width="1.5" stroke-dasharray="8 5" />
                    </svg>

                    <div class="z-10 space-y-1 text-center">
                        <p class="text-[10px] font-semibold uppercase tracking-[0.22em] text-blue-300/70">Total Konten
                        </p>
                        <p class="text-5xl font-semibold text-white">{{ $totalContentCount }}</p>
                        <p class="text-xs text-smoke">item dikelola</p>
                    </div>

                    <div
                        class="absolute -bottom-5 left-1/2 -translate-x-1/2 whitespace-nowrap rounded-full border border-white/10 bg-noir/90 px-4 py-1.5 text-[11px] font-medium text-blue-300/80 shadow-lg backdrop-blur-sm">
                        <i class="fa-solid fa-clock-rotate-left mr-1.5 text-[9px] text-blue-500/70"></i>
                        {{ $recentEbooks->count() }} ebook terbaru
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- =========================================================
         CONTENT OVERVIEW — Unik untuk Admin Host
         ========================================================= --}}
    <div class="grid gap-3 sm:grid-cols-2">
        {{-- Ebook overview --}}
        <div
            class="overflow-hidden rounded-2xl border border-blue-500/20 bg-[radial-gradient(ellipse_80%_80%_at_0%_0%,rgba(59,130,246,0.1),transparent)] px-6 py-5">
            <div class="flex items-center gap-4">
                <div
                    class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl border border-blue-500/30 bg-blue-500/15 text-blue-300/90">
                    <i class="fa-solid fa-book text-lg"></i>
                </div>
                <div>
                    <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-blue-300/60">Koleksi Ebook</p>
                    <p class="text-3xl font-semibold text-white">{{ $ebookCount }}</p>
                    <p class="text-xs text-smoke">di {{ $ebookCategoryCount }} kategori</p>
                </div>
            </div>
            <div class="mt-4 border-t border-white/6 pt-3">
                <a href="{{ route('ebook-categories.index') }}"
                    class="inline-flex items-center gap-1.5 text-xs font-medium text-blue-300/80 transition-colors hover:text-blue-300">
                    Kelola ebook <i class="fa-solid fa-arrow-right text-[9px]"></i>
                </a>
            </div>
        </div>

        {{-- Signal & Berita overview --}}
        <div
            class="overflow-hidden rounded-2xl border border-blue-500/14 bg-[radial-gradient(ellipse_80%_80%_at_100%_0%,rgba(59,130,246,0.07),transparent)] px-6 py-5">
            <div class="flex items-center gap-4">
                <div
                    class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl border border-blue-500/20 bg-blue-500/10 text-blue-300/70">
                    <i class="fa-solid fa-newspaper text-lg"></i>
                </div>
                <div>
                    <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-blue-300/50">Berita &
                        Signal</p>
                    <p class="text-3xl font-semibold text-white">{{ $beritaCount + $signalCount }}</p>
                    <p class="text-xs text-smoke">{{ $beritaCount }} berita · {{ $signalCount }} signal</p>
                </div>
            </div>
            <div class="mt-4 border-t border-white/6 pt-3">
                <a href="{{ route('berita-categories.index') }}"
                    class="inline-flex items-center gap-1.5 text-xs font-medium text-blue-300/70 transition-colors hover:text-blue-300">
                    Kelola berita <i class="fa-solid fa-arrow-right text-[9px]"></i>
                </a>
            </div>
        </div>
    </div>

    {{-- =========================================================
         QUICK LINKS
         ========================================================= --}}
    <div>
        <p class="mb-3 text-[10px] font-semibold uppercase tracking-[0.22em] text-smoke/60">Akses cepat</p>
        <div class="grid grid-cols-2 gap-3 sm:grid-cols-3">
            @foreach ($quickLinks as $link)
                @php $isBlue = $link['accent'] === 'blue'; @endphp
                <a href="{{ $link['href'] }}"
                    class="{{ $isBlue
                        ? 'group relative flex flex-col gap-3 overflow-hidden rounded-2xl border border-blue-500/30 bg-blue-500/10 p-4 transition-all duration-300 hover:-translate-y-0.5 hover:border-blue-500/45 hover:bg-blue-500/16'
                        : 'group relative flex flex-col gap-3 overflow-hidden rounded-2xl border border-white/8 bg-white/4 p-4 transition-all duration-300 hover:-translate-y-0.5 hover:border-white/14 hover:bg-white/7' }}">
                    <div
                        class="{{ $isBlue
                            ? 'flex h-9 w-9 items-center justify-center rounded-xl border border-blue-500/35 bg-blue-500/18 text-sm text-blue-300/95'
                            : 'flex h-9 w-9 items-center justify-center rounded-xl border border-white/10 bg-white/6 text-sm text-smoke group-hover:text-champagne' }}">
                        <i class="{{ $link['icon'] }}"></i>
                    </div>
                    <div>
                        <p
                            class="{{ $isBlue ? 'text-sm font-medium leading-tight text-blue-200/95' : 'text-sm font-medium leading-tight text-white/80 group-hover:text-white' }}">
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
                    Total <span class="font-medium text-blue-300/90">{{ $totalContentCount }}</span> item dikelola.
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
                            <span class="text-[10px] font-medium text-blue-300/70">{{ $segment['value'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            @foreach ($stats as $stat)
                @php $isBlueTone = $stat['tone'] === 'blue'; @endphp
                <article
                    class="{{ $isBlueTone
                        ? 'rounded-2xl border border-blue-500/30 bg-blue-500/10 p-5'
                        : 'rounded-2xl border border-white/8 bg-white/3 p-5' }}">
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <p
                                class="text-[10px] font-semibold uppercase tracking-[0.18em] {{ $isBlueTone ? 'text-blue-300/70' : 'text-smoke/70' }}">
                                {{ $stat['title'] }}
                            </p>
                            <p class="mt-1 text-3xl font-semibold text-white">{{ $stat['value'] }}</p>
                        </div>
                        <div
                            class="{{ $isBlueTone
                                ? 'flex h-12 w-12 items-center justify-center rounded-2xl border border-blue-500/35 bg-blue-500/18 text-blue-300/95'
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
         RECENT EBOOKS TABLE
         ========================================================= --}}
    <div class="overflow-hidden rounded-2xl border border-white/8 bg-white/3">
        <div
            class="flex flex-col gap-4 border-b border-white/6 px-6 py-5 sm:flex-row sm:items-center sm:justify-between">
            <div class="space-y-1">
                <p class="text-[10px] font-semibold uppercase tracking-[0.22em] text-smoke/60">Ebook terbaru</p>
                <h2 class="text-xl font-semibold tracking-[-0.03em] text-white">Aktivitas Ebook Terbaru</h2>
                <p class="text-sm text-smoke">{{ $recentEbooks->count() }} ebook paling baru yang baru masuk ke
                    sistem.</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('ebook-categories.index') }}"
                    class="inline-flex items-center gap-1.5 rounded-lg border border-blue-500/20 bg-blue-500/07 px-3.5 py-2 text-xs font-medium text-blue-300/80 transition-all duration-200 hover:border-blue-500/35 hover:bg-blue-500/14 hover:text-blue-300">
                    <i class="fa-solid fa-book text-[10px]"></i>
                    Kategori Ebook
                </a>
                <a href="{{ route('berita-categories.index') }}"
                    class="inline-flex items-center gap-1.5 rounded-lg border border-white/10 bg-white/5 px-3.5 py-2 text-xs font-medium text-smoke transition-all duration-200 hover:border-white/18 hover:bg-white/8 hover:text-champagne">
                    <i class="fa-solid fa-newspaper text-[10px]"></i>
                    Berita
                </a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr
                        class="border-b border-white/5 bg-noir/50 text-left text-[10px] font-semibold uppercase tracking-[0.18em] text-smoke/70">
                        <th class="w-12 px-6 py-3.5">#</th>
                        <th class="px-4 py-3.5">Judul Ebook</th>
                        <th class="px-4 py-3.5">Kategori</th>
                        <th class="hidden px-4 py-3.5 lg:table-cell">Slug</th>
                        <th class="px-4 py-3.5 text-right">Dibuat</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse ($recentEbooks as $index => $ebook)
                        <tr class="group transition-colors duration-150 hover:bg-blue-500/04">
                            <td class="px-6 py-4">
                                <span
                                    class="flex h-6 w-6 items-center justify-center rounded-md bg-white/5 text-xs font-medium text-smoke group-hover:bg-blue-500/12 group-hover:text-blue-300/90">
                                    {{ $index + 1 }}
                                </span>
                            </td>
                            <td class="max-w-[220px] px-4 py-4">
                                <p class="truncate font-medium text-white">{{ $ebook->title }}</p>
                            </td>
                            <td class="px-4 py-4">
                                <span
                                    class="inline-flex items-center rounded-md border border-blue-500/30 bg-blue-500/12 px-2.5 py-1 text-[10px] font-medium text-blue-300/90">
                                    {{ $ebook->category?->name ?? '-' }}
                                </span>
                            </td>
                            <td class="hidden max-w-xs px-4 py-4 lg:table-cell">
                                <p class="truncate text-sm text-smoke">{{ $ebook->slug }}</p>
                            </td>
                            <td class="px-4 py-4 text-right">
                                <div class="space-y-0.5">
                                    <p class="text-xs font-medium text-blue-300/80">
                                        {{ $ebook->created_at?->format('d M Y') ?? '-' }}</p>
                                    <p class="text-[10px] text-smoke/60">
                                        {{ $ebook->created_at?->format('H:i') ?? '' }}</p>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center">
                                <div class="mx-auto flex max-w-xs flex-col items-center gap-3">
                                    <div
                                        class="flex h-12 w-12 items-center justify-center rounded-2xl border border-blue-500/20 bg-blue-500/07 text-blue-300/70">
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

        <div class="flex items-center justify-between border-t border-white/6 bg-noir/30 px-6 py-4">
            <p class="text-xs text-smoke">
                Menampilkan {{ $recentEbooks->count() }} dari {{ $ebookCount }} total ebook
            </p>
            <a href="{{ route('ebook-categories.index') }}"
                class="inline-flex items-center gap-1.5 rounded-lg border border-blue-500/20 bg-blue-500/07 px-3.5 py-2 text-xs font-medium text-blue-300/80 transition-all duration-200 hover:border-blue-500/35 hover:bg-blue-500/14 hover:text-blue-300">
                Lihat Semua Ebook
                <i class="fa-solid fa-arrow-right text-[9px]"></i>
            </a>
        </div>
    </div>
</section>
