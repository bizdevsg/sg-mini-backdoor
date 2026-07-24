@extends('layouts.app')

@section('title', 'Berita - ' . $beritaCategory->name)

@section('content')
    @php
        $theme = auth()->user()?->roleTheme() ?? [
            'text' => 'text-gold-soft',
            'btn_primary' => 'bg-gold text-obsidian hover:bg-gold-soft',
            'badge_border' => 'border-gold/25',
            'badge_bg' => 'bg-gold/10',
            'badge_text' => 'text-gold-soft',
        ];
    @endphp

    <section class="space-y-6">
        <div class="rounded-[28px] border border-white/8 bg-white/3 px-7 py-6 shadow-[0_24px_60px_rgba(0,0,0,0.3)]">
            <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                <div class="space-y-2">
                    <div class="flex items-center gap-2 text-xs text-smoke/60">
                        <a href="{{ route('berita-categories.index') }}" class="hover:text-smoke">Kategori Berita</a>
                        <i class="fa-solid fa-chevron-right text-[8px]"></i>
                        <span class="text-smoke/40">{{ $beritaCategory->name }}</span>
                    </div>
                    <h1 class="text-2xl font-semibold text-white lg:text-3xl">Berita: <span class="{{ $theme['text'] }}">{{ $beritaCategory->name }}</span></h1>
                    <p class="text-sm text-smoke">Kelola seluruh berita di kategori {{ $beritaCategory->name }}.</p>
                </div>
                <div class="flex items-center gap-3">
                    <span class="rounded-xl border border-white/8 bg-white/5 px-4 py-2.5 text-sm text-smoke">
                        {{ $beritas->total() }} berita
                    </span>
                    <a href="{{ route('berita.create', $beritaCategory) }}"
                        class="inline-flex items-center gap-2 rounded-xl {{ $theme['btn_primary'] }} px-5 py-2.5 text-sm font-semibold">
                        <i class="fa-solid fa-plus text-xs"></i>
                        Tambah Berita
                    </a>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-white/8 bg-white/3 px-5 py-4">
            <form action="{{ route('berita.index', $beritaCategory) }}" method="GET" class="flex flex-col gap-3 sm:flex-row sm:items-center">
                <div class="relative flex-1">
                    <div class="pointer-events-none absolute inset-y-0 left-3.5 flex items-center">
                        <i class="fa-solid fa-magnifying-glass text-xs text-smoke/60"></i>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari judul ID/EN, author, source, isi, atau slug berita..."
                        class="w-full rounded-xl border border-white/8 bg-onyx py-2.5 pl-9 pr-4 text-sm text-champagne placeholder:text-smoke/50 focus:border-white/20 focus:outline-none">
                </div>
                <div class="flex gap-2">
                    <button type="submit"
                        class="inline-flex items-center gap-1.5 rounded-xl border {{ $theme['badge_border'] }} {{ $theme['badge_bg'] }} px-4 py-2.5 text-sm font-medium {{ $theme['badge_text'] }} hover:opacity-80">
                        <i class="fa-solid fa-filter text-[10px]"></i>
                        Filter
                    </button>
                    <a href="{{ route('berita.index', $beritaCategory) }}"
                        class="inline-flex items-center gap-1.5 rounded-xl border border-white/8 px-4 py-2.5 text-sm font-medium text-smoke hover:border-white/15 hover:text-white">
                        <i class="fa-solid fa-xmark text-[10px]"></i>
                        Reset
                    </a>
                </div>
            </form>
        </div>

        {{-- Grid Cards List --}}
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @forelse ($beritas as $berita)
                <div class="group relative flex flex-col justify-between overflow-hidden rounded-2xl border border-white/8 bg-white/3 p-5 transition-all duration-300 hover:-translate-y-1 hover:border-white/16 hover:bg-white/5 shadow-lg">
                    @if ($berita->image_url ?? false)
                        <div class="relative mb-4 -mx-5 -mt-5 h-44 overflow-hidden border-b border-white/6 bg-noir">
                            <img src="{{ $berita->image_url }}" alt="{{ $berita->title_id }}"
                                class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105">
                            <div class="absolute left-3 top-3 flex flex-wrap gap-1.5">
                                <span class="inline-flex items-center gap-1.5 rounded-lg border {{ $theme['badge_border'] }} {{ $theme['badge_bg'] }} px-2.5 py-1 text-[11px] font-semibold {{ $theme['badge_text'] }} backdrop-blur-md">
                                    <i class="fa-solid fa-folder-open text-[10px]"></i>
                                    {{ $beritaCategory->name }}
                                </span>
                            </div>
                        </div>
                    @else
                        <div class="mb-3 flex items-center justify-between gap-2">
                            <span class="inline-flex items-center gap-1.5 rounded-lg border {{ $theme['badge_border'] }} {{ $theme['badge_bg'] }} px-2.5 py-1 text-[11px] font-semibold {{ $theme['badge_text'] }}">
                                <i class="fa-solid fa-folder-open text-[10px]"></i>
                                {{ $beritaCategory->name }}
                            </span>
                            <span class="inline-flex items-center gap-1 rounded-md border border-white/8 bg-onyx px-2 py-0.5 font-mono text-[10px] text-smoke/70 truncate max-w-[140px]">
                                <i class="fa-solid fa-link text-[8px]"></i>
                                {{ $berita->slug }}
                            </span>
                        </div>
                    @endif

                    <div class="space-y-2.5 flex-1">
                        <div>
                            <h3 class="font-semibold text-white transition-colors line-clamp-2 text-base leading-snug">
                                {{ $berita->title_id }}
                            </h3>
                            <p class="mt-1 text-xs font-medium {{ $theme['text'] }}/80 line-clamp-1">
                                {{ $berita->title_en }}
                            </p>
                        </div>

                        <p class="line-clamp-3 text-xs leading-relaxed text-smoke">
                            {{ \Illuminate\Support\Str::limit(strip_tags($berita->content_id), 130) }}
                        </p>

                        <div class="flex flex-wrap items-center gap-2 pt-1 text-[11px] text-smoke/80">
                            @if ($berita->author)
                                <span class="rounded-md border border-white/8 bg-onyx px-2 py-0.5">Author: {{ $berita->author }}</span>
                            @endif
                            @if ($berita->source)
                                <span class="rounded-md border border-white/8 bg-onyx px-2 py-0.5">Source: {{ $berita->source }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="mt-4 flex items-center justify-between border-t border-white/6 pt-3.5">
                        <span class="inline-flex items-center gap-1 font-mono text-[10px] text-smoke/50 truncate max-w-[120px]" title="{{ $berita->slug }}">
                            <i class="fa-solid fa-link text-[8px]"></i>
                            {{ $berita->slug }}
                        </span>

                        <div class="flex items-center gap-1.5">
                            <a href="{{ route('berita.show', ['beritaCategory' => $beritaCategory, 'berita' => $berita]) }}"
                                class="inline-flex items-center gap-1.5 rounded-lg border border-white/10 bg-white/5 px-2.5 py-1.5 text-xs font-medium text-smoke hover:border-white/18 hover:bg-white/8 hover:text-white">
                                <i class="fa-solid fa-eye text-[10px]"></i>
                                Detail
                            </a>
                            <a href="{{ route('berita.edit', ['beritaCategory' => $beritaCategory, 'berita' => $berita]) }}"
                                class="inline-flex items-center gap-1.5 rounded-lg border {{ $theme['badge_border'] }} {{ $theme['badge_bg'] }} px-2.5 py-1.5 text-xs font-medium {{ $theme['badge_text'] }} hover:opacity-80">
                                <i class="fa-solid fa-pen text-[10px]"></i>
                                Edit
                            </a>
                            <form action="{{ route('berita.destroy', ['beritaCategory' => $beritaCategory, 'berita' => $berita]) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" data-confirm-submit data-confirm-intent="delete"
                                    data-confirm-title="Hapus berita ini?"
                                    data-confirm-message="Berita {{ $berita->title_id }} akan dihapus permanen."
                                    data-confirm-action-label="Ya, hapus"
                                    class="inline-flex items-center gap-1.5 rounded-lg border border-red-400/25 bg-red-500/8 px-2.5 py-1.5 text-xs font-medium text-red-300/80 hover:border-red-400/40 hover:bg-red-500/16 hover:text-red-200">
                                    <i class="fa-solid fa-trash text-[10px]"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full rounded-2xl border border-white/8 bg-white/3 p-12 text-center text-sm text-smoke">
                    <div class="mx-auto flex max-w-xs flex-col items-center gap-3">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl border border-white/8 bg-white/4 text-smoke">
                            <i class="fa-solid fa-newspaper text-lg"></i>
                        </div>
                        <p class="text-sm text-smoke">Belum ada berita pada kategori ini.</p>
                    </div>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        <div class="flex flex-col items-start justify-between gap-3 rounded-2xl border border-white/8 bg-white/3 px-6 py-4 sm:flex-row sm:items-center">
            <p class="text-xs text-smoke">
                Menampilkan <span class="font-medium text-champagne/80">{{ $beritas->firstItem() ?? 0 }}-{{ $beritas->lastItem() ?? 0 }}</span>
                dari <span class="font-medium text-champagne/80">{{ $beritas->total() }}</span> berita
            </p>
            <div class="text-sm">{{ $beritas->appends(request()->query())->links() }}</div>
        </div>
    </section>
@endsection
