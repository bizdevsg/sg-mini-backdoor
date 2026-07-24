@extends('layouts.app')

@section('title', 'Banner')

@section('content')
    @php
        $theme = auth()->user()?->roleTheme() ?? [
            'hero_bg' => 'bg-[radial-gradient(ellipse_70%_80%_at_0%_0%,rgba(199,161,90,0.15),transparent),linear-gradient(160deg,rgba(255,255,255,0.05)_0%,rgba(255,255,255,0.01)_100%)]',
            'hero_glow' => 'bg-gold/8',
            'hero_shimmer' => 'via-gold/35',
            'badge_border' => 'border-gold/20',
            'badge_bg' => 'bg-gold/8',
            'badge_text' => 'text-gold-soft/90',
            'dot' => 'bg-gold',
            'gradient_text' => 'from-gold-soft to-champagne',
            'btn_primary' => 'bg-gold text-obsidian hover:bg-gold-soft shadow-[0_4px_18px_rgba(199,161,90,0.28)]',
        ];
    @endphp

    <section class="space-y-6">

        {{-- ══════════════════════════════════════════════
             HERO HEADER
        ══════════════════════════════════════════════ --}}
        <div class="relative overflow-hidden rounded-[28px] border border-white/8 {{ $theme['hero_bg'] }} px-7 py-6 shadow-[0_24px_60px_rgba(0,0,0,0.3)] motion-safe:motion-preset-slide-down-sm lg:px-9 lg:py-8">
            <div class="pointer-events-none absolute -right-16 -top-16 h-48 w-48 rounded-full {{ $theme['hero_glow'] }} blur-[64px]"></div>
            <div class="pointer-events-none absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent {{ $theme['hero_shimmer'] }} to-transparent"></div>

            <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                <div class="space-y-3 motion-safe:motion-preset-slide-right-sm motion-safe:motion-delay-[60ms]">
                    <span class="inline-flex items-center gap-2 rounded-full border {{ $theme['badge_border'] }} {{ $theme['badge_bg'] }} px-3 py-1 text-[10px] font-semibold uppercase tracking-[0.24em] {{ $theme['badge_text'] }}">
                        <span class="h-1.5 w-1.5 animate-pulse rounded-full {{ $theme['dot'] }}"></span>
                        Banner Management
                    </span>
                    <div>
                        <h1 class="text-2xl font-semibold tracking-[-0.04em] text-white lg:text-3xl">
                            Kelola Banner
                            <span class="bg-gradient-to-r {{ $theme['gradient_text'] }} bg-clip-text text-transparent">Website</span>
                        </h1>
                        <p class="mt-2 max-w-xl text-sm leading-6 text-smoke">
                            Atur gambar carousel, urutan slide, status publikasi, serta syarat dan ketentuan promosi.
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-3 motion-safe:motion-preset-slide-left-sm motion-safe:motion-delay-[100ms]">
                    <a href="{{ route('banner.create') }}"
                        class="inline-flex items-center gap-2 rounded-xl {{ $theme['btn_primary'] }} px-5 py-2.5 text-sm font-semibold transition-all duration-200">
                        <i class="fa-solid fa-plus text-xs"></i>
                        Tambah Banner
                    </a>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════
             STATS SUMMARY CARDS
        ══════════════════════════════════════════════ --}}
        <div class="grid gap-4 sm:grid-cols-3 motion-safe:motion-preset-fade-lg motion-safe:motion-delay-[80ms]">
            <div class="flex items-center gap-4 rounded-2xl border border-white/8 bg-white/3 p-5 transition-all hover:border-white/14 hover:bg-white/5">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl border border-white/10 bg-white/5 text-smoke">
                    <i class="fa-solid fa-images text-lg"></i>
                </div>
                <div>
                    <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-smoke/70">Total Banner</p>
                    <p class="mt-0.5 text-2xl font-semibold text-white">{{ $banners->total() }}</p>
                </div>
            </div>

            <div class="flex items-center gap-4 rounded-2xl border border-emerald-500/20 bg-emerald-500/5 p-5 transition-all hover:border-emerald-500/30 hover:bg-emerald-500/8">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl border border-emerald-500/25 bg-emerald-500/12 text-emerald-400">
                    <i class="fa-solid fa-circle-check text-lg"></i>
                </div>
                <div>
                    <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-emerald-400/80">Aktif Tayang</p>
                    <p class="mt-0.5 text-2xl font-semibold text-white">{{ $activeCount }}</p>
                </div>
            </div>

            <div class="flex items-center gap-4 rounded-2xl border border-white/8 bg-white/3 p-5 transition-all hover:border-white/14 hover:bg-white/5">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl border border-white/10 bg-white/5 text-smoke/60">
                    <i class="fa-solid fa-eye-slash text-lg"></i>
                </div>
                <div>
                    <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-smoke/70">Nonaktif</p>
                    <p class="mt-0.5 text-2xl font-semibold text-white">{{ $inactiveCount }}</p>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════
             BANNER CARDS / EMPTY STATE
        ══════════════════════════════════════════════ --}}
        @if ($banners->isEmpty())
            <div class="overflow-hidden rounded-2xl border border-white/8 bg-white/3 motion-safe:motion-preset-slide-up-sm motion-safe:motion-delay-[120ms]">
                <div class="flex flex-col items-center px-6 py-20 text-center">
                    <div class="relative">
                        <div class="absolute inset-0 rounded-3xl bg-gold/8 blur-xl"></div>
                        <div class="relative flex h-20 w-20 items-center justify-center rounded-3xl border border-gold/20 bg-gold/10 text-gold-soft">
                            <i class="fa-solid fa-image text-2xl"></i>
                        </div>
                    </div>
                    <h3 class="mt-6 text-xl font-semibold text-white">Belum ada banner</h3>
                    <p class="mt-2 max-w-sm text-sm leading-6 text-smoke">
                        Mulai dengan menambahkan image pertama untuk carousel website.
                    </p>
                    <a href="{{ route('banner.create') }}"
                        class="mt-6 inline-flex items-center gap-2 rounded-xl bg-gold px-5 py-2.5 text-sm font-semibold text-obsidian shadow-[0_4px_18px_rgba(199,161,90,0.28)] transition-all duration-200 hover:bg-gold-soft">
                        <i class="fa-solid fa-plus text-xs"></i>
                        Tambah Banner
                    </a>
                </div>
            </div>
        @else
            <div class="grid gap-6 lg:grid-cols-2 motion-safe:motion-preset-slide-up-sm motion-safe:motion-delay-[120ms]">
                @foreach ($banners as $banner)
                    <article class="group relative flex flex-col justify-between overflow-hidden rounded-2xl border border-white/8 bg-white/3 p-5 transition-all duration-300 hover:border-gold/25 hover:bg-white/5 hover:shadow-[0_16px_40px_rgba(0,0,0,0.35)]">
                        <div>
                            {{-- Image Container --}}
                            <div class="relative overflow-hidden rounded-xl border border-white/8 bg-onyx">
                                @if ($banner->image_url)
                                    <img src="{{ $banner->image_url }}" alt="{{ $banner->title }}" class="h-52 w-full object-cover transition-transform duration-500 group-hover:scale-[1.02]">
                                @else
                                    <div class="flex h-52 items-center justify-center bg-onyx text-sm text-smoke/60">
                                        <i class="fa-solid fa-image mr-2 text-base"></i> Image tidak tersedia
                                    </div>
                                @endif

                                {{-- Status & Sort Badge Overlay --}}
                                <div class="absolute left-3 top-3 flex flex-wrap items-center gap-2">
                                    @if ($banner->is_active)
                                        <span class="inline-flex items-center gap-1.5 rounded-full border border-emerald-500/30 bg-emerald-950/80 px-3 py-1 text-[10px] font-semibold text-emerald-300 shadow-md backdrop-blur-md">
                                            <span class="h-1.5 w-1.5 animate-pulse rounded-full bg-emerald-400"></span>
                                            Aktif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 rounded-full border border-white/15 bg-black/70 px-3 py-1 text-[10px] font-semibold text-smoke shadow-md backdrop-blur-md">
                                            <span class="h-1.5 w-1.5 rounded-full bg-smoke/40"></span>
                                            Nonaktif
                                        </span>
                                    @endif

                                    <span class="inline-flex items-center gap-1 rounded-full border border-gold/30 bg-black/70 px-2.5 py-1 text-[10px] font-medium text-gold-soft shadow-md backdrop-blur-md">
                                        <i class="fa-solid fa-arrow-down-short-wide text-[9px]"></i>
                                        Urutan #{{ $banner->sort_order }}
                                    </span>
                                </div>
                            </div>

                            {{-- Content details --}}
                            <div class="mt-4 space-y-3">
                                <div class="flex items-start justify-between gap-3">
                                    <h3 class="text-lg font-semibold leading-snug text-white transition-colors group-hover:text-gold-soft">
                                        {{ $banner->title }}
                                    </h3>
                                    <span class="shrink-0 rounded-md border border-white/8 bg-black/20 px-2 py-0.5 font-mono text-[10px] text-smoke/70">
                                        ID: {{ $banner->id }}
                                    </span>
                                </div>

                                <div class="flex flex-wrap items-center gap-2 text-xs">
                                    <span class="inline-flex items-center gap-1.5 rounded-lg border border-white/8 bg-onyx px-2.5 py-1 font-mono text-[11px] text-champagne/80">
                                        <i class="fa-solid fa-link text-[9px] text-smoke/50"></i>
                                        {{ $banner->slug }}
                                    </span>
                                </div>

                                {{-- File name pill --}}
                                <div class="flex items-center gap-2 rounded-xl border border-white/6 bg-black/20 px-3 py-2 text-xs text-smoke/70">
                                    <i class="fa-solid fa-file-image shrink-0 text-smoke/50"></i>
                                    <span class="truncate font-mono text-[11px] text-smoke/80" title="{{ $banner->image }}">{{ $banner->image }}</span>
                                </div>

                                {{-- Terms snippet --}}
                                <div class="rounded-xl border border-white/6 bg-black/20 p-3.5">
                                    <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-smoke/60">Syarat & Ketentuan</p>
                                    @if (filled($banner->terms_and_conditions))
                                        <p class="mt-1.5 text-xs leading-relaxed text-champagne/80">
                                            {{ \Illuminate\Support\Str::limit(trim(strip_tags($banner->terms_and_conditions)), 160) }}
                                        </p>
                                    @else
                                        <p class="mt-1.5 text-xs italic text-smoke/40">Belum ada syarat & ketentuan khusus.</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Card Actions Footer --}}
                        <div class="mt-5 flex items-center justify-end gap-2 border-t border-white/6 pt-4">
                            <a href="{{ route('banner.edit', $banner) }}"
                                class="inline-flex items-center gap-1.5 rounded-lg border border-gold/20 bg-gold/8 px-3.5 py-1.5 text-xs font-medium text-gold-soft transition-all duration-150 hover:border-gold/35 hover:bg-gold/15">
                                <i class="fa-solid fa-pen text-[10px]"></i>
                                Edit
                            </a>
                            <form action="{{ route('banner.destroy', $banner) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    data-confirm-submit
                                    data-confirm-intent="delete"
                                    data-confirm-title="Hapus banner ini?"
                                    data-confirm-message="Banner {{ $banner->title }} akan dihapus permanen. Tindakan ini tidak bisa dibatalkan."
                                    data-confirm-action-label="Ya, hapus"
                                    class="inline-flex items-center gap-1.5 rounded-lg border border-red-400/25 bg-red-500/8 px-3.5 py-1.5 text-xs font-medium text-red-300/80 transition-all duration-150 hover:border-red-400/40 hover:bg-red-500/16 hover:text-red-200">
                                    <i class="fa-solid fa-trash text-[10px]"></i>
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="flex flex-col items-start justify-between gap-3 rounded-2xl border border-white/8 bg-white/3 px-6 py-4 sm:flex-row sm:items-center">
                <p class="text-xs text-smoke">
                    Menampilkan <span class="font-medium text-champagne/80">{{ $banners->firstItem() }}–{{ $banners->lastItem() }}</span> dari
                    <span class="font-medium text-champagne/80">{{ $banners->total() }}</span> banner
                </p>
                <div class="text-sm">
                    {{ $banners->links() }}
                </div>
            </div>
        @endif
    </section>
@endsection

