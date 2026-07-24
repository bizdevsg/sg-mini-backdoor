@extends('layouts.app')

@section('title', 'Kategori Berita')

@section('content')
    @php
        $theme = auth()->user()?->roleTheme() ?? [
            'btn_primary' => 'bg-gold text-obsidian hover:bg-gold-soft',
            'badge_border' => 'border-gold/25',
            'badge_bg' => 'bg-gold/10',
            'badge_text' => 'text-gold-soft',
        ];
    @endphp

    <section class="space-y-6">
        <div class="rounded-[28px] border border-white/8 bg-white/3 px-7 py-6 shadow-[0_24px_60px_rgba(0,0,0,0.3)]">
            <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h1 class="text-2xl font-semibold text-white lg:text-3xl">Kategori Berita</h1>
                    <p class="mt-2 text-sm text-smoke">Kelola kategori utama untuk modul berita.</p>
                </div>
                <a href="{{ route('berita-categories.create') }}"
                    class="inline-flex items-center gap-2 rounded-xl {{ $theme['btn_primary'] }} px-5 py-2.5 text-sm font-semibold">
                    <i class="fa-solid fa-plus text-xs"></i>
                    Tambah Kategori
                </a>
            </div>
        </div>

        <div class="rounded-2xl border border-white/8 bg-white/3 px-5 py-4">
            <form action="{{ route('berita-categories.index') }}" method="GET" class="flex flex-col gap-3 sm:flex-row sm:items-center">
                <div class="relative flex-1">
                    <div class="pointer-events-none absolute inset-y-0 left-3.5 flex items-center">
                        <i class="fa-solid fa-magnifying-glass text-xs text-smoke/60"></i>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari nama atau slug kategori berita..."
                        class="w-full rounded-xl border border-white/8 bg-onyx py-2.5 pl-9 pr-4 text-sm text-champagne placeholder:text-smoke/50 focus:border-white/20 focus:outline-none">
                </div>
                <div class="flex gap-2">
                    <button type="submit"
                        class="inline-flex items-center gap-1.5 rounded-xl border {{ $theme['badge_border'] }} {{ $theme['badge_bg'] }} px-4 py-2.5 text-sm font-medium {{ $theme['badge_text'] }} hover:opacity-80">
                        <i class="fa-solid fa-filter text-[10px]"></i>
                        Filter
                    </button>
                    <a href="{{ route('berita-categories.index') }}"
                        class="inline-flex items-center gap-1.5 rounded-xl border border-white/8 px-4 py-2.5 text-sm font-medium text-smoke hover:border-white/15 hover:text-white">
                        <i class="fa-solid fa-xmark text-[10px]"></i>
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <div class="overflow-hidden rounded-2xl border border-white/8 bg-white/3">
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="border-b border-white/6 bg-noir/50 text-left text-[10px] font-semibold uppercase tracking-[0.18em] text-smoke/70">
                            <th class="px-6 py-3.5">Nama Kategori</th>
                            <th class="px-4 py-3.5">Slug</th>
                            <th class="px-4 py-3.5">Jumlah Berita</th>
                            <th class="px-4 py-3.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @forelse ($categories as $category)
                            <tr class="group align-top transition-colors duration-150 hover:bg-white/3">
                                <td class="px-6 py-4">
                                    <p class="font-semibold text-white group-hover:text-gold-soft">{{ $category->name }}</p>
                                </td>
                                <td class="px-4 py-4">
                                    <span class="inline-flex items-center gap-1.5 rounded-lg border border-white/8 bg-onyx px-2.5 py-1 font-mono text-[11px] text-champagne/80">
                                        <i class="fa-solid fa-link text-[9px] text-smoke/50"></i>
                                        {{ $category->slug }}
                                    </span>
                                </td>
                                <td class="px-4 py-4">
                                    <span class="inline-flex items-center gap-1.5 rounded-md border border-gold/20 bg-gold/10 px-2.5 py-1 text-xs font-medium text-gold-soft">
                                        <i class="fa-solid fa-newspaper text-[10px]"></i>
                                        {{ $category->beritas_count }} Berita
                                    </span>
                                </td>
                                <td class="px-4 py-4">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <a href="{{ route('berita.index', $category) }}"
                                            class="inline-flex items-center gap-1.5 rounded-lg border border-white/10 bg-white/5 px-3 py-1.5 text-xs font-medium text-smoke hover:border-white/18 hover:bg-white/8 hover:text-white">
                                            <i class="fa-solid fa-layer-group text-[10px]"></i>
                                            Lihat Berita
                                        </a>
                                        <a href="{{ route('berita-categories.edit', $category) }}"
                                            class="inline-flex items-center gap-1.5 rounded-lg border border-gold/20 bg-gold/8 px-3 py-1.5 text-xs font-medium text-gold-soft hover:border-gold/35 hover:bg-gold/15">
                                            <i class="fa-solid fa-pen text-[10px]"></i>
                                            Edit
                                        </a>
                                        <form action="{{ route('berita-categories.destroy', $category) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" data-confirm-submit data-confirm-intent="delete"
                                                data-confirm-title="Hapus kategori berita ini?"
                                                data-confirm-message="Kategori {{ $category->name }} akan dihapus permanen jika sudah tidak dipakai berita."
                                                data-confirm-action-label="Ya, hapus"
                                                class="inline-flex items-center gap-1.5 rounded-lg border border-red-400/25 bg-red-500/8 px-3 py-1.5 text-xs font-medium text-red-300/80 hover:border-red-400/40 hover:bg-red-500/16 hover:text-red-200">
                                                <i class="fa-solid fa-trash text-[10px]"></i>
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-16 text-center text-sm text-smoke">Belum ada kategori berita.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="flex flex-col items-start justify-between gap-3 border-t border-white/6 bg-noir/30 px-6 py-4 sm:flex-row sm:items-center">
                <p class="text-xs text-smoke">
                    Menampilkan <span class="font-medium text-champagne/80">{{ $categories->firstItem() }}-{{ $categories->lastItem() }}</span>
                    dari <span class="font-medium text-champagne/80">{{ $categories->total() }}</span> kategori
                </p>
                <div class="text-sm">{{ $categories->appends(request()->query())->links() }}</div>
            </div>
        </div>
    </section>
@endsection
