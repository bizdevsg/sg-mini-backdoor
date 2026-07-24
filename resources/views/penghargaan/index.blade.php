@extends('layouts.app')

@section('title', 'Penghargaan')

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
                        Recognition Management
                    </span>
                    <div>
                        <h1 class="text-2xl font-semibold tracking-[-0.04em] text-white lg:text-3xl">
                            Penghargaan
                            <span class="bg-gradient-to-r {{ $theme['gradient_text'] }} bg-clip-text text-transparent">Perusahaan</span>
                        </h1>
                        <p class="mt-2 max-w-xl text-sm leading-6 text-smoke">
                            Kelola dokumentasi prestasi, piala, dan penghargaan resmi perusahaan.
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-3 motion-safe:motion-preset-slide-left-sm motion-safe:motion-delay-[100ms]">
                    @if (!$penghargaans->isEmpty())
                        <span class="rounded-xl border border-white/8 bg-white/5 px-4 py-2.5 text-sm text-smoke">
                            {{ $penghargaans->total() }} penghargaan
                        </span>
                    @endif
                    <a href="{{ route('penghargaan.create') }}"
                        class="inline-flex items-center gap-2 rounded-xl {{ $theme['btn_primary'] }} px-5 py-2.5 text-sm font-semibold transition-all duration-200">
                        <i class="fa-solid fa-plus text-xs"></i>
                        Tambah Penghargaan
                    </a>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════
             SEARCH & FILTER
        ══════════════════════════════════════════════ --}}
        <div class="rounded-2xl border border-white/8 bg-white/3 px-5 py-4 motion-safe:motion-preset-fade-lg motion-safe:motion-delay-[80ms]">
            <form action="{{ route('penghargaan.index') }}" method="GET"
                class="flex flex-col gap-3 sm:flex-row sm:items-center">
                {{-- Search input with icon --}}
                <div class="relative flex-1">
                    <div class="pointer-events-none absolute inset-y-0 left-3.5 flex items-center">
                        <i class="fa-solid fa-magnifying-glass text-xs text-smoke/60"></i>
                    </div>
                    <input type="text" name="search" id="search" value="{{ request('search') }}"
                        placeholder="Cari judul atau subtitle penghargaan..."
                        class="w-full rounded-xl border border-white/8 bg-onyx py-2.5 pl-9 pr-4 text-sm text-champagne placeholder:text-smoke/50 focus:border-gold/35 focus:outline-none focus:ring-2 focus:ring-gold/12 transition-colors">
                </div>

                <div class="flex gap-2">
                    <button type="submit"
                        class="inline-flex items-center gap-1.5 rounded-xl border border-gold/25 bg-gold/10 px-4 py-2.5 text-sm font-medium text-gold-soft transition-all duration-200 hover:border-gold/40 hover:bg-gold/18">
                        <i class="fa-solid fa-filter text-[10px]"></i>
                        Filter
                    </button>
                    <a href="{{ route('penghargaan.index') }}"
                        class="inline-flex items-center gap-1.5 rounded-xl border border-white/8 bg-transparent px-4 py-2.5 text-sm font-medium text-smoke transition-all duration-200 hover:border-white/15 hover:text-white">
                        <i class="fa-solid fa-xmark text-[10px]"></i>
                        Reset
                    </a>
                </div>
            </form>
        </div>

        {{-- ══════════════════════════════════════════════
             PENGHARGAAN CARDS GRID / EMPTY STATE
        ══════════════════════════════════════════════ --}}
        @if ($penghargaans->isEmpty())
            <div class="overflow-hidden rounded-2xl border border-white/8 bg-white/3 motion-safe:motion-preset-slide-up-sm motion-safe:motion-delay-[120ms]">
                <div class="flex flex-col items-center px-6 py-20 text-center">
                    <div class="relative">
                        <div class="absolute inset-0 rounded-3xl bg-gold/8 blur-xl"></div>
                        <div class="relative flex h-20 w-20 items-center justify-center rounded-3xl border border-gold/20 bg-gold/10 text-gold-soft">
                            <i class="fa-solid fa-trophy text-2xl"></i>
                        </div>
                    </div>
                    <h3 class="mt-6 text-xl font-semibold text-white">Belum ada penghargaan</h3>
                    <p class="mt-2 max-w-sm text-sm leading-6 text-smoke">
                        @if (request('search'))
                            Tidak ditemukan data untuk pencarian "<span class="text-champagne/80">{{ request('search') }}</span>".
                        @else
                            Mulai dengan menambahkan dokumentasi penghargaan pertama.
                        @endif
                    </p>
                    @if (!request('search'))
                        <a href="{{ route('penghargaan.create') }}"
                            class="mt-6 inline-flex items-center gap-2 rounded-xl bg-gold px-5 py-2.5 text-sm font-semibold text-obsidian shadow-[0_4px_18px_rgba(199,161,90,0.28)] transition-all duration-200 hover:bg-gold-soft">
                            <i class="fa-solid fa-plus text-xs"></i>
                            Tambah Penghargaan
                        </a>
                    @endif
                </div>
            </div>
        @else
            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3 motion-safe:motion-preset-slide-up-sm motion-safe:motion-delay-[120ms]">
                @foreach ($penghargaans as $penghargaan)
                    <article class="group relative flex flex-col justify-between overflow-hidden rounded-2xl border border-white/8 bg-white/3 p-5 transition-all duration-300 hover:border-gold/25 hover:bg-white/5 hover:shadow-[0_16px_40px_rgba(0,0,0,0.35)]">
                        <div>
                            {{-- Image Container --}}
                            <div class="relative overflow-hidden rounded-xl border border-white/8 bg-onyx">
                                @if ($penghargaan->image_url)
                                    <img src="{{ $penghargaan->image_url }}" alt="{{ $penghargaan->title }}"
                                        class="h-48 w-full object-cover transition-transform duration-500 group-hover:scale-[1.03]">
                                @else
                                    <div class="flex h-48 items-center justify-center bg-onyx text-sm text-smoke/50">
                                        <i class="fa-solid fa-trophy mr-2 text-base text-gold-soft/50"></i> Gambar tidak tersedia
                                    </div>
                                @endif

                                {{-- Year badge overlay --}}
                                <div class="absolute right-3 top-3">
                                    <span class="inline-flex items-center gap-1 rounded-full border border-gold/30 bg-black/75 px-3 py-1 text-[11px] font-semibold text-gold-soft shadow-md backdrop-blur-md">
                                        <i class="fa-solid fa-award text-[10px]"></i>
                                        {{ $penghargaan->created_at?->format('Y') ?? '-' }}
                                    </span>
                                </div>
                            </div>

                            {{-- Title & Subtitle --}}
                            <div class="mt-4 space-y-2">
                                <span class="text-[10px] font-semibold uppercase tracking-[0.2em] text-gold-soft/80">Penghargaan Resmi</span>
                                <h3 class="text-lg font-semibold leading-snug text-white transition-colors group-hover:text-gold-soft">
                                    {{ $penghargaan->title }}
                                </h3>
                                <p class="line-clamp-3 text-xs leading-relaxed text-smoke/80">
                                    {{ \Illuminate\Support\Str::limit(strip_tags($penghargaan->subtitle), 140) }}
                                </p>
                            </div>
                        </div>

                        {{-- Footer Actions --}}
                        <div class="mt-5 flex items-center justify-end gap-2 border-t border-white/6 pt-4">
                            <a href="{{ route('penghargaan.edit', $penghargaan) }}"
                                class="inline-flex items-center gap-1.5 rounded-lg border border-gold/20 bg-gold/8 px-3.5 py-1.5 text-xs font-medium text-gold-soft transition-all duration-150 hover:border-gold/35 hover:bg-gold/15">
                                <i class="fa-solid fa-pen text-[10px]"></i>
                                Edit
                            </a>
                            <form action="{{ route('penghargaan.destroy', $penghargaan) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    data-confirm-submit
                                    data-confirm-intent="delete"
                                    data-confirm-title="Hapus penghargaan ini?"
                                    data-confirm-message="Penghargaan {{ $penghargaan->title }} akan dihapus permanen. Tindakan ini tidak bisa dibatalkan."
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

            {{-- Footer: count + pagination --}}
            <div class="flex flex-col items-start justify-between gap-3 rounded-2xl border border-white/8 bg-white/3 px-6 py-4 sm:flex-row sm:items-center">
                <p class="text-xs text-smoke">
                    Menampilkan <span class="font-medium text-champagne/80">{{ $penghargaans->firstItem() }}–{{ $penghargaans->lastItem() }}</span> dari
                    <span class="font-medium text-champagne/80">{{ $penghargaans->total() }}</span> penghargaan
                </p>
                <div class="text-sm">
                    {{ $penghargaans->appends(request()->query())->links() }}
                </div>
            </div>
        @endif
    </section>
@endsection
