@extends('layouts.app')

@section('title', 'Wakil Pialang - ' . $wakilPialangCategory->nama_kategori)

@section('content')
    @php
        $theme = auth()->user()?->roleTheme() ?? [
            'hero_bg' =>
                'bg-[radial-gradient(ellipse_70%_80%_at_0%_0%,rgba(199,161,90,0.15),transparent),linear-gradient(160deg,rgba(21,17,13,0.05)_0%,rgba(21,17,13,0.01)_100%)]',
            'hero_glow' => 'bg-gold/8',
            'hero_shimmer' => 'via-gold/35',
            'gradient_text' => 'from-gold-soft to-champagne',
            'btn_primary' => 'bg-gold text-obsidian hover:bg-gold-soft shadow-[0_4px_18px_rgba(199,161,90,0.28)]',
        ];
    @endphp

    <section class="space-y-6">
        <div
            class="relative overflow-hidden rounded-[28px] border border-black/8 {{ $theme['hero_bg'] }} px-7 py-6 lg:px-9 lg:py-8">
            <div
                class="pointer-events-none absolute -right-16 -top-16 h-48 w-48 rounded-full {{ $theme['hero_glow'] }} blur-[64px]">
            </div>
            <div
                class="pointer-events-none absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent {{ $theme['hero_shimmer'] }} to-transparent">
            </div>

            <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                <div class="space-y-3">
                    <div class="flex items-center gap-2 text-xs text-smoke/60">
                        <a href="{{ route('wakil-pialang-categories.index') }}" class="transition-colors hover:text-smoke">
                            Kategori Wakil Pialang
                        </a>
                        <i class="fa-solid fa-chevron-right text-[8px]"></i>
                        <span class="text-smoke/40">{{ $wakilPialangCategory->nama_kategori }}</span>
                    </div>

                    <div>
                        <h1 class="text-2xl font-semibold tracking-[-0.04em] text-ivory lg:text-3xl">
                            Wakil Pialang Berjangka:
                            <span
                                class="bg-gradient-to-r {{ $theme['gradient_text'] }} bg-clip-text text-transparent">{{ $wakilPialangCategory->nama_kategori }}</span>
                        </h1>
                        <p class="mt-2 max-w-xl text-sm leading-6 text-smoke">
                            Kelola wakil pialang berjangka dalam kategori {{ $wakilPialangCategory->nama_kategori }}.
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    @if (!$items->isEmpty())
                        <span class="rounded-xl border border-black/8 bg-black/5 px-4 py-2.5 text-sm text-smoke">
                            {{ $items->total() }} wakil
                        </span>
                    @endif
                    <a href="{{ route('wakil-pialang.create', $wakilPialangCategory) }}"
                        class="inline-flex items-center gap-2 rounded-xl {{ $theme['btn_primary'] }} px-5 py-2.5 text-sm font-semibold transition-all duration-200">
                        <i class="fa-solid fa-plus text-xs"></i>
                        Tambah Wakil Pialang
                    </a>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-black/8 bg-black/3 px-5 py-4">
            <form action="{{ route('wakil-pialang.index', $wakilPialangCategory) }}" method="GET"
                class="flex flex-col gap-3 sm:flex-row sm:items-center">
                <div class="relative flex-1">
                    <div class="pointer-events-none absolute inset-y-0 left-3.5 flex items-center">
                        <i class="fa-solid fa-magnifying-glass text-xs text-smoke/60"></i>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari nama atau no. identitas..."
                        class="w-full rounded-xl border border-black/8 bg-onyx py-2.5 pl-9 pr-4 text-sm text-champagne placeholder:text-smoke/50 focus:border-gold/35 focus:outline-none focus:ring-2 focus:ring-gold/12">
                </div>
                <select name="status"
                    class="rounded-xl border border-black/8 bg-onyx px-4 py-2.5 text-sm text-champagne focus:border-gold/35 focus:outline-none focus:ring-2 focus:ring-gold/12">
                    <option value="">Semua status</option>
                    @foreach (\App\Models\WakilPialang::STATUS_OPTIONS as $value => $label)
                        <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                <div class="flex gap-2">
                    <button type="submit"
                        class="inline-flex items-center gap-1.5 rounded-xl border border-gold/25 bg-gold/10 px-4 py-2.5 text-sm font-medium text-gold-soft transition-all duration-200 hover:border-gold/40 hover:bg-gold/18">
                        <i class="fa-solid fa-filter text-[10px]"></i>
                        Filter
                    </button>
                    <a href="{{ route('wakil-pialang.index', $wakilPialangCategory) }}"
                        class="inline-flex items-center gap-1.5 rounded-xl border border-black/8 px-4 py-2.5 text-sm font-medium text-smoke transition-all duration-200 hover:border-black/15 hover:text-ivory">
                        <i class="fa-solid fa-xmark text-[10px]"></i>
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <div class="overflow-hidden rounded-2xl border border-black/8 bg-black/3">
            @if ($items->isEmpty())
                <div class="flex flex-col items-center px-6 py-20 text-center">
                    <div
                        class="flex h-20 w-20 items-center justify-center rounded-3xl border border-gold/20 bg-gold/10 text-gold-soft">
                        <i class="fa-solid fa-user-tie text-2xl"></i>
                    </div>
                    <h3 class="mt-6 text-xl font-semibold text-ivory">Belum ada wakil pialang</h3>
                    <p class="mt-2 max-w-sm text-sm leading-6 text-smoke">
                        @if (request()->hasAny(['search', 'status']))
                            Tidak ada data yang cocok dengan filter yang dipilih.
                        @else
                            Tambahkan wakil pialang berjangka pertama untuk kategori ini.
                        @endif
                    </p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr
                                class="border-b border-black/6 bg-noir/50 text-left text-[10px] font-semibold uppercase tracking-[0.18em] text-smoke/70">
                                <th class="px-6 py-3.5">Nama</th>
                                <th class="px-4 py-3.5">No. Identitas</th>
                                <th class="px-4 py-3.5">Status</th>
                                <th class="px-4 py-3.5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-black/5">
                            @foreach ($items as $item)
                                <tr class="group align-top transition-colors duration-150 hover:bg-black/3">
                                    <td class="px-6 py-4">
                                        <p class="font-semibold text-ivory group-hover:text-gold-soft">{{ $item->nama }}</p>
                                    </td>
                                    <td class="px-4 py-4 font-mono text-xs text-champagne/80">{{ $item->no_identitas }}</td>
                                    <td class="px-4 py-4">
                                        @if ($item->status === 'aktif')
                                            <span class="inline-flex items-center gap-1.5 rounded-md border border-emerald-500/25 bg-emerald-500/10 px-2.5 py-1 text-xs font-medium text-emerald-700">
                                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                                Aktif
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 rounded-md border border-black/10 bg-black/5 px-2.5 py-1 text-xs font-medium text-smoke">
                                                <span class="h-1.5 w-1.5 rounded-full bg-smoke/60"></span>
                                                Tidak Aktif
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="flex items-center justify-end gap-1.5">
                                            <a href="{{ route('wakil-pialang.edit', [$wakilPialangCategory, $item]) }}"
                                                class="inline-flex items-center gap-1.5 rounded-lg border border-gold/20 bg-gold/8 px-3 py-1.5 text-xs font-medium text-gold-soft transition-all duration-150 hover:border-gold/35 hover:bg-gold/15">
                                                <i class="fa-solid fa-pen text-[10px]"></i>
                                                Edit
                                            </a>
                                            <form action="{{ route('wakil-pialang.destroy', [$wakilPialangCategory, $item]) }}"
                                                method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" data-confirm-submit data-confirm-intent="delete"
                                                    data-confirm-title="Hapus wakil pialang ini?"
                                                    data-confirm-message="Data {{ $item->nama }} akan dihapus permanen."
                                                    data-confirm-action-label="Ya, hapus"
                                                    class="inline-flex items-center gap-1.5 rounded-lg border border-red-400/25 bg-red-500/8 px-3 py-1.5 text-xs font-medium text-red-700/80 transition-all duration-150 hover:border-red-400/40 hover:bg-red-500/16 hover:text-red-800">
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

                <div
                    class="flex flex-col items-start justify-between gap-3 border-t border-black/6 bg-noir/30 px-6 py-4 sm:flex-row sm:items-center">
                    <p class="text-xs text-smoke">
                        Menampilkan <span
                            class="font-medium text-champagne/80">{{ $items->firstItem() }}-{{ $items->lastItem() }}</span>
                        dari <span class="font-medium text-champagne/80">{{ $items->total() }}</span> wakil
                    </p>
                    <div class="text-sm">{{ $items->appends(request()->query())->links() }}</div>
                </div>
            @endif
        </div>
    </section>
@endsection
