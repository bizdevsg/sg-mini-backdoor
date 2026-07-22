@extends('layouts.app')

@section('title', 'Detail Produk ' . $sectionName)

@section('content')
    @php($imageUrl = $produk->image_url)

    <section class="space-y-6">

        {{-- ══════════════════════════════════════════════
             HERO HEADER
        ══════════════════════════════════════════════ --}}
        <div class="relative overflow-hidden rounded-[28px] border border-white/8 bg-[radial-gradient(ellipse_70%_80%_at_0%_0%,rgba(199,161,90,0.14),transparent),linear-gradient(160deg,rgba(255,255,255,0.05)_0%,rgba(255,255,255,0.01)_100%)] px-7 py-6 shadow-[0_24px_60px_rgba(0,0,0,0.3)] motion-safe:motion-preset-slide-down-sm lg:px-9 lg:py-8">
            <div class="pointer-events-none absolute -right-12 -top-12 h-40 w-40 rounded-full bg-gold/8 blur-[56px]"></div>
            <div class="pointer-events-none absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-gold/35 to-transparent"></div>

            <div class="flex flex-col gap-5 lg:flex-row lg:items-start lg:justify-between">
                <div class="space-y-3 motion-safe:motion-preset-slide-right-sm motion-safe:motion-delay-[60ms]">
                    {{-- Breadcrumb --}}
                    <div class="flex items-center gap-2 text-xs text-smoke/60">
                        <a href="{{ route('produk.index', ['section' => $section]) }}" class="transition-colors hover:text-smoke">
                            Produk {{ $sectionName }}
                        </a>
                        <i class="fa-solid fa-chevron-right text-[8px]"></i>
                        <span class="text-smoke/40">Detail</span>
                    </div>

                    {{-- Title --}}
                    <h1 class="max-w-2xl text-2xl font-semibold tracking-[-0.04em] text-white lg:text-3xl">
                        {{ $produk->nama_produk }}
                    </h1>

                    {{-- Meta badges --}}
                    <div class="flex flex-wrap items-center gap-2">
                        @if ($produk->kategori === 'JFX')
                            <span class="inline-flex items-center gap-1.5 rounded-md border border-gold/25 bg-gold/12 px-2.5 py-1 text-[10px] font-semibold uppercase tracking-[0.1em] text-gold-soft">
                                <span class="h-1 w-1 rounded-full bg-gold"></span>
                                Multilateral
                            </span>
                        @elseif ($produk->kategori === 'SPA')
                            <span class="inline-flex items-center gap-1.5 rounded-md border border-blue-400/20 bg-blue-400/8 px-2.5 py-1 text-[10px] font-semibold uppercase tracking-[0.1em] text-blue-300/80">
                                <span class="h-1 w-1 rounded-full bg-blue-400/70"></span>
                                Bilateral
                            </span>
                        @else
                            <span class="inline-flex items-center rounded-md border border-white/10 bg-white/5 px-2.5 py-1 text-[10px] font-medium text-smoke">
                                {{ $produk->kategori }}
                            </span>
                        @endif
                        <span class="inline-flex items-center gap-1.5 rounded-md border border-white/8 bg-onyx px-2.5 py-1 text-[10px] font-mono text-champagne/70">
                            <i class="fa-solid fa-link text-[8px] text-smoke/50"></i>
                            {{ $produk->slug }}
                        </span>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex items-center gap-2 motion-safe:motion-preset-slide-left-sm motion-safe:motion-delay-[80ms]">
                    <a href="{{ route('produk.index', ['section' => $section]) }}"
                        class="inline-flex items-center gap-1.5 rounded-xl border border-white/10 bg-white/5 px-4 py-2.5 text-sm font-medium text-smoke transition-all duration-200 hover:border-white/18 hover:bg-white/8 hover:text-white">
                        <i class="fa-solid fa-arrow-left text-[10px]"></i>
                        Kembali
                    </a>
                    <a href="{{ route('produk.edit', ['produk' => $produk, 'section' => $section]) }}"
                        class="inline-flex items-center gap-1.5 rounded-xl bg-gold px-4 py-2.5 text-sm font-semibold text-obsidian shadow-[0_4px_16px_rgba(199,161,90,0.25)] transition-all duration-200 hover:bg-gold-soft hover:shadow-[0_6px_20px_rgba(199,161,90,0.38)]">
                        <i class="fa-solid fa-pen text-[10px]"></i>
                        Edit Produk
                    </a>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════
             MAIN LAYOUT: SIDEBAR + CONTENT
        ══════════════════════════════════════════════ --}}
        <div class="grid gap-6 lg:grid-cols-[300px_minmax(0,1fr)]">

            {{-- LEFT SIDEBAR --}}
            <div class="space-y-4 motion-safe:motion-preset-slide-right-sm motion-safe:motion-delay-[120ms]">

                {{-- Image card --}}
                <div class="overflow-hidden rounded-2xl border border-white/8 bg-white/3">
                    @if ($imageUrl)
                        <div class="relative">
                            <img src="{{ $imageUrl }}" alt="{{ $produk->nama_produk }}"
                                class="h-60 w-full object-cover">
                            <div class="absolute inset-x-0 bottom-0 h-20 bg-gradient-to-t from-black/60 to-transparent"></div>
                        </div>
                    @else
                        <div class="flex h-52 flex-col items-center justify-center gap-3 bg-onyx">
                            <div class="flex h-14 w-14 items-center justify-center rounded-2xl border border-white/8 bg-white/5 text-smoke">
                                <i class="fa-solid fa-image text-xl"></i>
                            </div>
                            <p class="text-xs text-smoke/60">Tidak ada gambar</p>
                        </div>
                    @endif
                </div>

                {{-- Info card --}}
                <div class="rounded-2xl border border-white/8 bg-white/3 p-5">
                    <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-smoke/60">Informasi Produk</p>
                    <dl class="mt-4 divide-y divide-white/5 text-sm">
                        <div class="flex items-start justify-between gap-4 py-3">
                            <dt class="shrink-0 text-smoke">Kategori</dt>
                            <dd class="text-right text-white">{{ $produk->kategori }}</dd>
                        </div>
                        <div class="flex items-start justify-between gap-4 py-3">
                            <dt class="shrink-0 text-smoke">Slug</dt>
                            <dd class="break-all text-right font-mono text-xs text-champagne/80">{{ $produk->slug }}</dd>
                        </div>
                        <div class="flex items-start justify-between gap-4 py-3">
                            <dt class="shrink-0 text-smoke">Dibuat</dt>
                            <dd class="text-right">
                                <span class="block text-xs font-medium text-white">{{ $produk->created_at?->format('d M Y') ?? '-' }}</span>
                                <span class="block text-[10px] text-smoke/60">{{ $produk->created_at?->format('H:i') ?? '' }}</span>
                            </dd>
                        </div>
                        <div class="flex items-start justify-between gap-4 py-3">
                            <dt class="shrink-0 text-smoke">Diupdate</dt>
                            <dd class="text-right">
                                <span class="block text-xs font-medium text-white">{{ $produk->updated_at?->format('d M Y') ?? '-' }}</span>
                                <span class="block text-[10px] text-smoke/60">{{ $produk->updated_at?->format('H:i') ?? '' }}</span>
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

            {{-- RIGHT CONTENT --}}
            <div class="space-y-4 motion-safe:motion-preset-slide-left-sm motion-safe:motion-delay-[140ms]">

                {{-- Deskripsi card --}}
                <div class="rounded-2xl border border-white/8 bg-white/3 p-6">
                    <div class="mb-4 flex items-center gap-3 border-b border-white/6 pb-4">
                        <div class="flex h-8 w-8 items-center justify-center rounded-xl border border-white/10 bg-white/5 text-smoke">
                            <i class="fa-solid fa-align-left text-xs"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-smoke/60">Konten</p>
                            <h3 class="text-sm font-semibold text-white">Deskripsi Produk</h3>
                        </div>
                    </div>
                    <div class="whitespace-pre-line text-sm leading-7 text-smoke">
                        {{ $produk->deskripsi_produk }}
                    </div>
                </div>

                {{-- Spesifikasi card --}}
                <div class="rounded-2xl border border-white/8 bg-white/3 p-6">
                    <div class="mb-4 flex items-center gap-3 border-b border-white/6 pb-4">
                        <div class="flex h-8 w-8 items-center justify-center rounded-xl border border-gold/20 bg-gold/10 text-gold-soft">
                            <i class="fa-solid fa-list-check text-xs"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-smoke/60">Detail teknis</p>
                            <h3 class="text-sm font-semibold text-white">Spesifikasi</h3>
                        </div>
                    </div>
                    <div
                        class="prose prose-invert max-w-none text-sm leading-6 prose-headings:mt-4 prose-headings:mb-2 prose-headings:font-semibold prose-headings:text-white prose-p:my-2 prose-p:text-smoke prose-strong:text-white prose-em:text-champagne prose-a:text-gold-soft prose-a:no-underline hover:prose-a:text-white prose-ul:my-2 prose-ul:list-disc prose-ul:pl-5 prose-ul:text-smoke prose-ol:my-2 prose-ol:list-decimal prose-ol:pl-5 prose-ol:text-smoke prose-li:my-0.5 prose-li:text-smoke prose-li:marker:text-gold-soft prose-blockquote:border-gold prose-blockquote:py-0 prose-blockquote:text-white prose-code:rounded prose-code:bg-white/6 prose-code:px-1.5 prose-code:py-0.5 prose-code:text-white prose-pre:overflow-x-auto prose-pre:rounded-2xl prose-pre:border prose-pre:border-white/8 prose-pre:bg-onyx prose-pre:px-5 prose-pre:py-4 prose-pre:text-white prose-pre:prose-code:bg-transparent prose-pre:prose-code:px-0 prose-pre:prose-code:py-0 prose-hr:my-4 prose-hr:border-white/10 prose-table:my-3 prose-table:w-full prose-table:border-collapse prose-img:rounded-2xl prose-th:border prose-th:border-white/12 prose-th:bg-white/4 prose-th:px-4 prose-th:py-2.5 prose-th:text-left prose-th:align-top prose-th:font-semibold prose-th:text-white prose-td:border prose-td:border-white/12 prose-td:px-4 prose-td:py-2.5 prose-td:text-left prose-td:align-top prose-td:text-smoke [&_temporary]:hidden [&_.ql-ui]:hidden [&_figure.image]:mx-auto [&_figure.image]:max-w-full [&_img]:h-auto [&_img]:max-w-full [&_img]:rounded-2xl [&_img.img-full]:w-full [&_img.img-narrow]:mx-auto [&_img.img-narrow]:w-full [&_img.img-narrow]:max-w-[28rem] [&>:first-child]:mt-0 [&>:last-child]:mb-0">
                        {!! $produk->specs_for_display !!}
                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection
