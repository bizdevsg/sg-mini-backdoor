@extends('layouts.app')

@section('title', 'Kategori Signal')

@section('content')
    @php
        $theme = auth()->user()?->roleTheme() ?? [
            'hero_bg' => 'bg-[radial-gradient(ellipse_70%_80%_at_0%_0%,rgba(59,130,246,0.18),transparent),linear-gradient(160deg,rgba(255,255,255,0.05)_0%,rgba(255,255,255,0.01)_100%)]',
            'badge_border' => 'border-blue-500/20',
            'badge_bg' => 'bg-blue-500/10',
            'badge_text' => 'text-blue-300/90',
            'dot' => 'bg-blue-500',
            'btn_primary' => 'bg-blue-500 text-white hover:bg-blue-600',
        ];
    @endphp

    <section class="space-y-6">
        <div class="relative overflow-hidden rounded-[28px] border border-white/8 {{ $theme['hero_bg'] }} px-7 py-6 shadow-[0_24px_60px_rgba(0,0,0,0.3)]">
            <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                <div class="space-y-3">
                    <span class="inline-flex items-center gap-2 rounded-full border {{ $theme['badge_border'] }} {{ $theme['badge_bg'] }} px-3 py-1 text-[10px] font-semibold uppercase tracking-[0.24em] {{ $theme['badge_text'] }}">
                        <span class="h-1.5 w-1.5 animate-pulse rounded-full {{ $theme['dot'] }}"></span>
                        Signal Category Management
                    </span>
                    <div>
                        <h1 class="text-2xl font-semibold tracking-[-0.04em] text-white lg:text-3xl">Kategori Signal</h1>
                        <p class="mt-2 max-w-xl text-sm leading-6 text-smoke">Kelola kategori utama untuk modul signal.</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    @if (! $categories->isEmpty())
                        <span class="rounded-xl border border-white/8 bg-white/5 px-4 py-2.5 text-sm text-smoke">
                            {{ $categories->total() }} kategori
                        </span>
                    @endif
                    <a href="{{ route('signal-categories.create') }}"
                        class="inline-flex items-center gap-2 rounded-xl {{ $theme['btn_primary'] }} px-5 py-2.5 text-sm font-semibold transition-all duration-200">
                        <i class="fa-solid fa-plus text-xs"></i>
                        Tambah Kategori
                    </a>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-white/8 bg-white/3 px-5 py-4">
            <form action="{{ route('signal-categories.index') }}" method="GET" class="flex flex-col gap-3 sm:flex-row sm:items-center">
                <div class="relative flex-1">
                    <div class="pointer-events-none absolute inset-y-0 left-3.5 flex items-center">
                        <i class="fa-solid fa-magnifying-glass text-xs text-smoke/60"></i>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari nama atau slug kategori signal..."
                        class="w-full rounded-xl border border-white/8 bg-onyx py-2.5 pl-9 pr-4 text-sm text-champagne placeholder:text-smoke/50 focus:border-gold/35 focus:outline-none focus:ring-2 focus:ring-gold/12">
                </div>
                <div class="flex gap-2">
                    <button type="submit"
                        class="inline-flex items-center gap-1.5 rounded-xl border border-blue-500/25 bg-blue-500/10 px-4 py-2.5 text-sm font-medium text-blue-300 transition-all duration-200 hover:border-blue-500/40 hover:bg-blue-500/18">
                        <i class="fa-solid fa-filter text-[10px]"></i>
                        Filter
                    </button>
                    <a href="{{ route('signal-categories.index') }}"
                        class="inline-flex items-center gap-1.5 rounded-xl border border-white/8 px-4 py-2.5 text-sm font-medium text-smoke transition-all duration-200 hover:border-white/15 hover:text-white">
                        <i class="fa-solid fa-xmark text-[10px]"></i>
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <div class="overflow-hidden rounded-2xl border border-white/8 bg-white/3">
            @if ($categories->isEmpty())
                <div class="flex flex-col items-center px-6 py-20 text-center">
                    <div class="flex h-20 w-20 items-center justify-center rounded-3xl border border-blue-500/20 bg-blue-500/10 text-blue-300">
                        <i class="fa-solid fa-tags text-2xl"></i>
                    </div>
                    <h3 class="mt-6 text-xl font-semibold text-white">Belum ada kategori signal</h3>
                    <p class="mt-2 max-w-sm text-sm leading-6 text-smoke">
                        @if (request('search'))
                            Tidak ditemukan kategori untuk pencarian "{{ request('search') }}".
                        @else
                            Tambahkan kategori terlebih dahulu agar signal dapat dikelompokkan.
                        @endif
                    </p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="border-b border-white/6 bg-noir/50 text-left text-[10px] font-semibold uppercase tracking-[0.18em] text-smoke/70">
                                <th class="px-6 py-3.5">Nama Kategori</th>
                                <th class="px-4 py-3.5">Slug</th>
                                <th class="px-4 py-3.5">Jumlah Signal</th>
                                <th class="px-4 py-3.5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @foreach ($categories as $category)
                                <tr class="group align-top transition-colors duration-150 hover:bg-white/3">
                                    <td class="px-6 py-4">
                                        <p class="font-semibold text-white group-hover:text-blue-300">{{ $category->name }}</p>
                                    </td>
                                    <td class="px-4 py-4">
                                        <span class="inline-flex items-center gap-1.5 rounded-lg border border-white/8 bg-onyx px-2.5 py-1 font-mono text-[11px] text-champagne/80">
                                            <i class="fa-solid fa-link text-[9px] text-smoke/50"></i>
                                            {{ $category->slug }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4">
                                        <span class="inline-flex items-center gap-1.5 rounded-md border border-blue-500/20 bg-blue-500/10 px-2.5 py-1 text-xs font-medium text-blue-300">
                                            <i class="fa-solid fa-image text-[10px]"></i>
                                            {{ $category->signals_count }} Signal
                                        </span>
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="flex items-center justify-end gap-1.5">
                                            <a href="{{ route('signal.index', $category) }}"
                                                class="inline-flex items-center gap-1.5 rounded-lg border border-white/10 bg-white/5 px-3 py-1.5 text-xs font-medium text-smoke transition-all duration-150 hover:border-white/18 hover:bg-white/8 hover:text-white">
                                                <i class="fa-solid fa-layer-group text-[10px]"></i>
                                                Lihat Signal
                                            </a>
                                            <a href="{{ route('signal-categories.edit', $category) }}"
                                                class="inline-flex items-center gap-1.5 rounded-lg border border-blue-500/20 bg-blue-500/8 px-3 py-1.5 text-xs font-medium text-blue-300 transition-all duration-150 hover:border-blue-500/35 hover:bg-blue-500/15">
                                                <i class="fa-solid fa-pen text-[10px]"></i>
                                                Edit
                                            </a>
                                            <form action="{{ route('signal-categories.destroy', $category) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" data-confirm-submit data-confirm-intent="delete"
                                                    data-confirm-title="Hapus kategori signal ini?"
                                                    data-confirm-message="Kategori {{ $category->name }} akan dihapus permanen jika sudah tidak dipakai signal."
                                                    data-confirm-action-label="Ya, hapus"
                                                    class="inline-flex items-center gap-1.5 rounded-lg border border-red-400/25 bg-red-500/8 px-3 py-1.5 text-xs font-medium text-red-300/80 transition-all duration-150 hover:border-red-400/40 hover:bg-red-500/16 hover:text-red-200">
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

                <div class="flex flex-col items-start justify-between gap-3 border-t border-white/6 bg-noir/30 px-6 py-4 sm:flex-row sm:items-center">
                    <p class="text-xs text-smoke">
                        Menampilkan <span class="font-medium text-champagne/80">{{ $categories->firstItem() }}-{{ $categories->lastItem() }}</span>
                        dari <span class="font-medium text-champagne/80">{{ $categories->total() }}</span> kategori
                    </p>
                    <div class="text-sm">{{ $categories->appends(request()->query())->links() }}</div>
                </div>
            @endif
        </div>
    </section>
@endsection
