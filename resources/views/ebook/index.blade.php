@extends('layouts.app')

@section('title', 'Ebook - ' . $ebookCategory->name)

@section('content')
    <section class="space-y-6">

        {{-- ══════════════════════════════════════════════
             HERO HEADER
        ══════════════════════════════════════════════ --}}
        <div class="relative overflow-hidden rounded-[28px] border border-white/8 bg-[radial-gradient(ellipse_70%_80%_at_0%_0%,rgba(199,161,90,0.15),transparent),linear-gradient(160deg,rgba(255,255,255,0.05)_0%,rgba(255,255,255,0.01)_100%)] px-7 py-6 shadow-[0_24px_60px_rgba(0,0,0,0.3)] motion-safe:motion-preset-slide-down-sm lg:px-9 lg:py-8">
            <div class="pointer-events-none absolute -right-16 -top-16 h-48 w-48 rounded-full bg-gold/8 blur-[64px]"></div>
            <div class="pointer-events-none absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-gold/35 to-transparent"></div>

            <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                <div class="space-y-3 motion-safe:motion-preset-slide-right-sm motion-safe:motion-delay-[60ms]">
                    {{-- Breadcrumb --}}
                    <div class="flex items-center gap-2 text-xs text-smoke/60">
                        <a href="{{ route('ebook-categories.index') }}" class="transition-colors hover:text-smoke">
                            Kategori Ebook
                        </a>
                        <i class="fa-solid fa-chevron-right text-[8px]"></i>
                        <span class="text-smoke/40">{{ $ebookCategory->name }}</span>
                    </div>

                    <div>
                        <h1 class="text-2xl font-semibold tracking-[-0.04em] text-white lg:text-3xl">
                            Katalog Ebook:
                            <span class="bg-gradient-to-r from-gold-soft to-champagne bg-clip-text text-transparent">{{ $ebookCategory->name }}</span>
                        </h1>
                        <p class="mt-2 max-w-xl text-sm leading-6 text-smoke">
                            Kelola seluruh publikasi ebook dalam kategori {{ $ebookCategory->name }}.
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-3 motion-safe:motion-preset-slide-left-sm motion-safe:motion-delay-[100ms]">
                    @if (!$ebooks->isEmpty())
                        <span class="rounded-xl border border-white/8 bg-white/5 px-4 py-2.5 text-sm text-smoke">
                            {{ $ebooks->total() }} ebook
                        </span>
                    @endif
                    <a href="{{ route('ebook.create', $ebookCategory) }}"
                        class="inline-flex items-center gap-2 rounded-xl bg-gold px-5 py-2.5 text-sm font-semibold text-obsidian shadow-[0_4px_18px_rgba(199,161,90,0.28)] transition-all duration-200 hover:bg-gold-soft hover:shadow-[0_6px_24px_rgba(199,161,90,0.4)]">
                        <i class="fa-solid fa-plus text-xs"></i>
                        Tambah Ebook
                    </a>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════
             SEARCH & FILTER
        ══════════════════════════════════════════════ --}}
        <div class="rounded-2xl border border-white/8 bg-white/3 px-5 py-4 motion-safe:motion-preset-fade-lg motion-safe:motion-delay-[80ms]">
            <form action="{{ route('ebook.index', $ebookCategory) }}" method="GET"
                class="flex flex-col gap-3 sm:flex-row sm:items-center">
                {{-- Search input with icon --}}
                <div class="relative flex-1">
                    <div class="pointer-events-none absolute inset-y-0 left-3.5 flex items-center">
                        <i class="fa-solid fa-magnifying-glass text-xs text-smoke/60"></i>
                    </div>
                    <input type="text" name="search" id="search" value="{{ request('search') }}"
                        placeholder="Cari judul, deskripsi, atau slug ebook..."
                        class="w-full rounded-xl border border-white/8 bg-onyx py-2.5 pl-9 pr-4 text-sm text-champagne placeholder:text-smoke/50 focus:border-gold/35 focus:outline-none focus:ring-2 focus:ring-gold/12 transition-colors">
                </div>

                <div class="flex gap-2">
                    <button type="submit"
                        class="inline-flex items-center gap-1.5 rounded-xl border border-gold/25 bg-gold/10 px-4 py-2.5 text-sm font-medium text-gold-soft transition-all duration-200 hover:border-gold/40 hover:bg-gold/18">
                        <i class="fa-solid fa-filter text-[10px]"></i>
                        Filter
                    </button>
                    <a href="{{ route('ebook.index', $ebookCategory) }}"
                        class="inline-flex items-center gap-1.5 rounded-xl border border-white/8 bg-transparent px-4 py-2.5 text-sm font-medium text-smoke transition-all duration-200 hover:border-white/15 hover:text-white">
                        <i class="fa-solid fa-xmark text-[10px]"></i>
                        Reset
                    </a>
                </div>
            </form>
        </div>

        {{-- ══════════════════════════════════════════════
             TABLE / EMPTY STATE
        ══════════════════════════════════════════════ --}}
        <div class="overflow-hidden rounded-2xl border border-white/8 bg-white/3 motion-safe:motion-preset-slide-up-sm motion-safe:motion-delay-[120ms]">
            @if ($ebooks->isEmpty())
                {{-- Empty state --}}
                <div class="flex flex-col items-center px-6 py-20 text-center">
                    <div class="relative">
                        <div class="absolute inset-0 rounded-3xl bg-gold/8 blur-xl"></div>
                        <div class="relative flex h-20 w-20 items-center justify-center rounded-3xl border border-gold/20 bg-gold/10 text-gold-soft">
                            <i class="fa-solid fa-book-open text-2xl"></i>
                        </div>
                    </div>
                    <h3 class="mt-6 text-xl font-semibold text-white">Belum ada ebook</h3>
                    <p class="mt-2 max-w-sm text-sm leading-6 text-smoke">
                        @if (request('search'))
                            Tidak ditemukan ebook untuk pencarian "<span class="text-champagne/80">{{ request('search') }}</span>".
                        @else
                            Mulai dengan menambahkan ebook pertama untuk kategori {{ $ebookCategory->name }}.
                        @endif
                    </p>
                    @if (!request('search'))
                        <a href="{{ route('ebook.create', $ebookCategory) }}"
                            class="mt-6 inline-flex items-center gap-2 rounded-xl bg-gold px-5 py-2.5 text-sm font-semibold text-obsidian shadow-[0_4px_18px_rgba(199,161,90,0.28)] transition-all duration-200 hover:bg-gold-soft">
                            <i class="fa-solid fa-plus text-xs"></i>
                            Tambah Ebook
                        </a>
                    @endif
                </div>
            @else
                {{-- Table --}}
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="border-b border-white/6 bg-noir/50 text-left text-[10px] font-semibold uppercase tracking-[0.18em] text-smoke/70">
                                <th class="px-6 py-3.5">Judul & Deskripsi</th>
                                <th class="px-4 py-3.5">Kategori</th>
                                <th class="px-4 py-3.5">Slug</th>
                                <th class="px-4 py-3.5">Berkas PDF</th>
                                <th class="px-4 py-3.5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @foreach ($ebooks as $ebook)
                                <tr class="group align-top transition-colors duration-150 hover:bg-white/3">
                                    {{-- Title & excerpt --}}
                                    <td class="px-6 py-4">
                                        <p class="font-semibold text-white transition-colors group-hover:text-gold-soft">{{ $ebook->title }}</p>
                                        <p class="mt-1 line-clamp-2 max-w-xl text-xs leading-5 text-smoke">
                                            {{ \Illuminate\Support\Str::limit(strip_tags($ebook->description), 130) }}
                                        </p>
                                    </td>

                                    {{-- Category badge --}}
                                    <td class="px-4 py-4">
                                        <span class="inline-flex items-center gap-1.5 rounded-md border border-gold/25 bg-gold/12 px-2.5 py-1 text-[10px] font-semibold uppercase tracking-[0.1em] text-gold-soft">
                                            <span class="h-1 w-1 rounded-full bg-gold"></span>
                                            {{ $ebook->kategori }}
                                        </span>
                                    </td>

                                    {{-- Slug pill --}}
                                    <td class="px-4 py-4">
                                        <span class="inline-flex items-center gap-1.5 rounded-lg border border-white/8 bg-onyx px-2.5 py-1 font-mono text-[11px] text-champagne/80">
                                            <i class="fa-solid fa-link text-[9px] text-smoke/50"></i>
                                            {{ $ebook->slug }}
                                        </span>
                                    </td>

                                    {{-- File link --}}
                                    <td class="px-4 py-4">
                                        @if ($ebook->file_url)
                                            <a href="{{ $ebook->file_url }}" target="_blank" rel="noreferrer"
                                                class="inline-flex items-center gap-1.5 rounded-lg border border-white/10 bg-white/5 px-2.5 py-1.5 text-xs font-medium text-champagne/80 transition-all duration-150 hover:border-white/18 hover:bg-white/10 hover:text-white"
                                                title="Unduh PDF">
                                                <i class="fa-solid fa-file-pdf text-red-400 text-[11px]"></i>
                                                PDF
                                                <i class="fa-solid fa-download text-[9px] text-smoke/60"></i>
                                            </a>
                                        @else
                                            <span class="text-xs text-smoke/40">-</span>
                                        @endif
                                    </td>

                                    {{-- Actions --}}
                                    <td class="px-4 py-4">
                                        <div class="flex items-center justify-end gap-1.5">
                                            <a href="{{ route('ebook.show', ['ebookCategory' => $ebookCategory->slug, 'ebook' => $ebook->slug]) }}"
                                                class="inline-flex items-center gap-1.5 rounded-lg border border-white/10 bg-white/5 px-3 py-1.5 text-xs font-medium text-smoke transition-all duration-150 hover:border-white/18 hover:bg-white/8 hover:text-white"
                                                title="Lihat detail">
                                                <i class="fa-solid fa-eye text-[10px]"></i>
                                                Detail
                                            </a>
                                            <a href="{{ route('ebook.edit', ['ebookCategory' => $ebookCategory->slug, 'ebook' => $ebook->slug]) }}"
                                                class="inline-flex items-center gap-1.5 rounded-lg border border-gold/20 bg-gold/8 px-3 py-1.5 text-xs font-medium text-gold-soft transition-all duration-150 hover:border-gold/35 hover:bg-gold/15"
                                                title="Edit ebook">
                                                <i class="fa-solid fa-pen text-[10px]"></i>
                                                Edit
                                            </a>
                                            <form action="{{ route('ebook.destroy', ['ebookCategory' => $ebookCategory->slug, 'ebook' => $ebook->slug]) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    data-confirm-submit
                                                    data-confirm-intent="delete"
                                                    data-confirm-title="Hapus ebook ini?"
                                                    data-confirm-message="Ebook {{ $ebook->title }} akan dihapus permanen. Tindakan ini tidak bisa dibatalkan."
                                                    data-confirm-action-label="Ya, hapus"
                                                    class="inline-flex items-center gap-1.5 rounded-lg border border-red-400/25 bg-red-500/8 px-3 py-1.5 text-xs font-medium text-red-300/80 transition-all duration-150 hover:border-red-400/40 hover:bg-red-500/16 hover:text-red-200"
                                                    title="Hapus ebook">
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
                <div class="flex flex-col items-start justify-between gap-3 border-t border-white/6 bg-noir/30 px-6 py-4 sm:flex-row sm:items-center">
                    <p class="text-xs text-smoke">
                        Menampilkan
                        <span class="font-medium text-champagne/80">{{ $ebooks->firstItem() }}–{{ $ebooks->lastItem() }}</span>
                        dari <span class="font-medium text-champagne/80">{{ $ebooks->total() }}</span> ebook
                    </p>
                    <div class="text-sm">
                        {{ $ebooks->appends(request()->query())->links() }}
                    </div>
                </div>
            @endif
        </div>

    </section>
@endsection
