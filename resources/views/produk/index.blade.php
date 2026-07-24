@extends('layouts.app')

@section('title', 'Produk ' . $sectionName)

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
        <div
            class="relative overflow-hidden rounded-[28px] border border-white/8 {{ $theme['hero_bg'] }} px-7 py-6 shadow-[0_24px_60px_rgba(0,0,0,0.3)] motion-safe:motion-preset-slide-down-sm lg:px-9 lg:py-8">
            <div class="pointer-events-none absolute -right-16 -top-16 h-48 w-48 rounded-full {{ $theme['hero_glow'] }} blur-[64px]"></div>
            <div
                class="pointer-events-none absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent {{ $theme['hero_shimmer'] }} to-transparent">
            </div>

            <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                <div class="space-y-3 motion-safe:motion-preset-slide-right-sm motion-safe:motion-delay-[60ms]">
                    <span
                        class="inline-flex items-center gap-2 rounded-full border {{ $theme['badge_border'] }} {{ $theme['badge_bg'] }} px-3 py-1 text-[10px] font-semibold uppercase tracking-[0.24em] {{ $theme['badge_text'] }}">
                        <span class="h-1.5 w-1.5 animate-pulse rounded-full {{ $theme['dot'] }}"></span>
                        Catalog Management
                    </span>
                    <div>
                        <h1 class="text-2xl font-semibold tracking-[-0.04em] text-white lg:text-3xl">
                            Produk
                            <span
                                class="bg-gradient-to-r {{ $theme['gradient_text'] }} bg-clip-text text-transparent">{{ $sectionName }}</span>
                        </h1>
                        <p class="mt-2 max-w-xl text-sm leading-6 text-smoke">
                            Kelola seluruh katalog produk {{ strtolower($sectionName) }} — tambah, ubah, atau hapus item
                            dengan mudah.
                        </p>
                    </div>
                </div>

                <div
                    class="flex items-center gap-3 motion-safe:motion-preset-slide-left-sm motion-safe:motion-delay-[100ms]">
                    @if (!$produks->isEmpty())
                        <span class="rounded-xl border border-white/8 bg-white/5 px-4 py-2.5 text-sm text-smoke">
                            {{ $produks->total() }} produk
                        </span>
                    @endif
                    <a href="{{ route('produk.create', ['section' => $section]) }}"
                        class="inline-flex items-center gap-2 rounded-xl {{ $theme['btn_primary'] }} px-5 py-2.5 text-sm font-semibold transition-all duration-200">
                        <i class="fa-solid fa-plus text-xs"></i>
                        Tambah Produk
                    </a>
                </div>
            </div>
        </div>

        <div
            class="rounded-2xl border border-white/8 bg-white/3 px-5 py-4 motion-safe:motion-preset-fade-lg motion-safe:motion-delay-[80ms]">
            <form action="{{ route('produk.index', ['section' => $section]) }}" method="GET"
                class="flex flex-col gap-3 sm:flex-row sm:items-center">
                {{-- Search input with icon --}}
                <div class="relative flex-1">
                    <div class="pointer-events-none absolute inset-y-0 left-3.5 flex items-center">
                        <i class="fa-solid fa-magnifying-glass text-xs text-smoke/60"></i>
                    </div>
                    <input type="text" name="search" id="search" value="{{ request('search') }}"
                        placeholder="Cari nama produk..."
                        class="w-full rounded-xl border border-white/8 bg-onyx py-2.5 pl-9 pr-4 text-sm text-champagne placeholder:text-smoke/50 focus:border-gold/35 focus:outline-none focus:ring-2 focus:ring-gold/12 transition-colors">
                </div>

                <div class="flex gap-2">
                    <button type="submit"
                        class="inline-flex items-center gap-1.5 rounded-xl border border-gold/25 bg-gold/10 px-4 py-2.5 text-sm font-medium text-gold-soft transition-all duration-200 hover:border-gold/40 hover:bg-gold/18">
                        <i class="fa-solid fa-filter text-[10px]"></i>
                        Filter
                    </button>
                    <a href="{{ route('produk.index', ['section' => $section]) }}"
                        class="inline-flex items-center gap-1.5 rounded-xl border border-white/8 bg-transparent px-4 py-2.5 text-sm font-medium text-smoke transition-all duration-200 hover:border-white/15 hover:text-white">
                        <i class="fa-solid fa-xmark text-[10px]"></i>
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <div
            class="overflow-hidden rounded-2xl border border-white/8 bg-white/3 motion-safe:motion-preset-slide-up-sm motion-safe:motion-delay-[120ms]">
            @if ($produks->isEmpty())
                {{-- Empty state --}}
                <div class="flex flex-col items-center px-6 py-20 text-center">
                    <div class="relative">
                        <div class="absolute inset-0 rounded-3xl bg-gold/8 blur-xl"></div>
                        <div
                            class="relative flex h-20 w-20 items-center justify-center rounded-3xl border border-gold/20 bg-gold/10 text-gold-soft">
                            <i class="fa-solid fa-box-open text-2xl"></i>
                        </div>
                    </div>
                    <h3 class="mt-6 text-xl font-semibold text-white">
                        Belum ada produk {{ strtolower($sectionName) }}
                    </h3>
                    <p class="mt-2 max-w-sm text-sm leading-6 text-smoke">
                        @if (request('search'))
                            Tidak ditemukan produk untuk pencarian "<span
                                class="text-champagne/80">{{ request('search') }}</span>". Coba kata kunci lain.
                        @else
                            Mulai dengan menambahkan produk pertama ke katalog {{ strtolower($sectionName) }}.
                        @endif
                    </p>
                    @if (!request('search'))
                        <a href="{{ route('produk.create', ['section' => $section]) }}"
                            class="mt-6 inline-flex items-center gap-2 rounded-xl bg-gold px-5 py-2.5 text-sm font-semibold text-obsidian shadow-[0_4px_18px_rgba(199,161,90,0.28)] transition-all duration-200 hover:bg-gold-soft">
                            <i class="fa-solid fa-plus text-xs"></i>
                            Tambah Produk
                        </a>
                    @endif
                </div>
            @else
                {{-- Table --}}
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr
                                class="border-b border-white/6 bg-noir/50 text-left text-[10px] font-semibold uppercase tracking-[0.18em] text-smoke/70">
                                <th class="w-full px-6 py-3.5">Produk</th>
                                <th class="px-4 py-3.5">Kategori</th>
                                <th class="hidden px-4 py-3.5 lg:table-cell">Dibuat</th>
                                <th class="hidden px-4 py-3.5 xl:table-cell">Diupdate</th>
                                <th class="px-4 py-3.5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @foreach ($produks as $produk)
                                <tr class="group align-top transition-colors duration-150 hover:bg-white/3">
                                    {{-- Product name + excerpt --}}
                                    <td class="w-full px-6 py-4">
                                        <p class="font-medium text-white">{{ $produk->nama_produk }}</p>
                                    </td>

                                    {{-- Category badge --}}
                                    <td class="px-4 py-4">
                                        @if ($produk->kategori === 'JFX')
                                            <span
                                                class="inline-flex items-center gap-1.5 rounded-md border border-gold/25 bg-gold/12 px-2.5 py-1 text-[10px] font-semibold uppercase tracking-[0.1em] text-gold-soft">
                                                <span class="h-1 w-1 rounded-full bg-gold"></span>
                                                Multilateral
                                            </span>
                                        @elseif ($produk->kategori === 'SPA')
                                            <span
                                                class="inline-flex items-center gap-1.5 rounded-md border border-blue-400/20 bg-blue-400/8 px-2.5 py-1 text-[10px] font-semibold uppercase tracking-[0.1em] text-blue-300/80">
                                                <span class="h-1 w-1 rounded-full bg-blue-400/70"></span>
                                                Bilateral
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center rounded-md border border-white/10 bg-white/5 px-2.5 py-1 text-[10px] font-medium text-smoke">
                                                {{ $produk->kategori }}
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Created at --}}
                                    <td class="hidden px-4 py-4 lg:table-cell text-nowrap">
                                        <p class="text-xs font-medium text-champagne/80">
                                            {{ $produk->created_at?->format('d M Y') ?? '-' }}</p>
                                        <p class="mt-0.5 text-[10px] text-smoke/60">
                                            {{ $produk->created_at?->format('H:i') ?? '' }}</p>
                                    </td>

                                    {{-- Updated at --}}
                                    <td class="hidden px-4 py-4 xl:table-cell text-nowrap">
                                        <p class="text-xs font-medium text-champagne/80">
                                            {{ $produk->updated_at?->format('d M Y') ?? '-' }}</p>
                                        <p class="mt-0.5 text-[10px] text-smoke/60">
                                            {{ $produk->updated_at?->format('H:i') ?? '' }}</p>
                                    </td>

                                    {{-- Actions --}}
                                    <td class="px-4 py-4">
                                        <div class="flex items-center justify-end gap-1.5">
                                            <a href="{{ route('produk.show', ['produk' => $produk, 'section' => $section]) }}"
                                                class="inline-flex items-center gap-1.5 rounded-lg border border-white/10 bg-white/5 px-3 py-1.5 text-xs font-medium text-smoke transition-all duration-150 hover:border-white/18 hover:bg-white/8 hover:text-white"
                                                title="Lihat detail">
                                                <i class="fa-solid fa-eye text-[10px]"></i>
                                                Detail
                                            </a>
                                            <a href="{{ route('produk.edit', ['produk' => $produk, 'section' => $section]) }}"
                                                class="inline-flex items-center gap-1.5 rounded-lg border border-gold/20 bg-gold/8 px-3 py-1.5 text-xs font-medium text-gold-soft transition-all duration-150 hover:border-gold/35 hover:bg-gold/15"
                                                title="Edit produk">
                                                <i class="fa-solid fa-pen text-[10px]"></i>
                                                Edit
                                            </a>
                                            <form
                                                action="{{ route('produk.destroy', ['produk' => $produk, 'section' => $section]) }}"
                                                method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" data-confirm-submit data-confirm-intent="delete"
                                                    data-confirm-title="Hapus produk ini?"
                                                    data-confirm-message="Produk {{ $produk->nama_produk }} akan dihapus permanen. Tindakan ini tidak bisa dibatalkan."
                                                    data-confirm-action-label="Ya, hapus"
                                                    class="inline-flex items-center gap-1.5 rounded-lg border border-red-400/25 bg-red-500/8 px-3 py-1.5 text-xs font-medium text-red-300/80 transition-all duration-150 hover:border-red-400/40 hover:bg-red-500/16 hover:text-red-200"
                                                    title="Hapus produk">
                                                    <i class="fa-solid fa-trash text-[10px]"></i>
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Footer: count + pagination --}}
                <div
                    class="flex flex-col items-start justify-between gap-3 border-t border-white/6 bg-noir/30 px-6 py-4 sm:flex-row sm:items-center">
                    <p class="text-xs text-smoke">
                        Menampilkan
                        <span
                            class="font-medium text-champagne/80">{{ $produks->firstItem() }}–{{ $produks->lastItem() }}</span>
                        dari <span class="font-medium text-champagne/80">{{ $produks->total() }}</span> produk
                    </p>
                    <div class="text-sm">
                        {{ $produks->appends(request()->query())->links() }}
                    </div>
                </div>
            @endif
        </div>

    </section>
@endsection
