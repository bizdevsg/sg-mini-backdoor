@extends('layouts.app')

@section('title', 'Signal - ' . $signalCategory->name)

@section('content')
    @php
        $theme = auth()->user()?->roleTheme() ?? [
            'text' => 'text-blue-700',
            'btn_primary' => 'bg-blue-500 text-white hover:bg-blue-600',
            'badge_border' => 'border-blue-500/25',
            'badge_bg' => 'bg-blue-500/10',
            'badge_text' => 'text-blue-700',
        ];
    @endphp

    <section class="space-y-6">
        <div class="rounded-[28px] border border-black/8 bg-black/3 px-7 py-6">
            <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                <div class="space-y-2">
                    <div class="flex items-center gap-2 text-xs text-smoke/60">
                        <a href="{{ route('signal-categories.index') }}" class="hover:text-smoke">Kategori Signal</a>
                        <i class="fa-solid fa-chevron-right text-[8px]"></i>
                        <span class="text-smoke/40">{{ $signalCategory->name }}</span>
                    </div>
                    <h1 class="text-2xl font-semibold text-ivory lg:text-3xl">
                        Signal: <span class="{{ $theme['text'] }}">{{ $signalCategory->name }}</span>
                    </h1>
                    <p class="text-sm text-smoke">Kelola seluruh signal di kategori {{ $signalCategory->name }}.</p>
                </div>
                <div class="flex items-center gap-3">
                    <span class="rounded-xl border border-black/8 bg-black/5 px-4 py-2.5 text-sm text-smoke">
                        {{ $signals->total() }} signal
                    </span>
                    <a href="{{ route('signal.create', $signalCategory) }}"
                        class="inline-flex items-center gap-2 rounded-xl {{ $theme['btn_primary'] }} px-5 py-2.5 text-sm font-semibold">
                        <i class="fa-solid fa-plus text-xs"></i>
                        Tambah Signal
                    </a>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-black/8 bg-black/3 px-5 py-4">
            <form action="{{ route('signal.index', $signalCategory) }}" method="GET"
                class="flex flex-col gap-3 sm:flex-row sm:items-center">
                <div class="relative flex-1">
                    <div class="pointer-events-none absolute inset-y-0 left-3.5 flex items-center">
                        <i class="fa-solid fa-magnifying-glass text-xs text-smoke/60"></i>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari potensi, timeframe, TP, SL, atau sumber signal..."
                        class="w-full rounded-xl border border-black/8 bg-onyx py-2.5 pl-9 pr-4 text-sm text-champagne placeholder:text-smoke/50 focus:border-black/20 focus:outline-none">
                </div>
                <div class="flex gap-2">
                    <button type="submit"
                        class="inline-flex items-center gap-1.5 rounded-xl border {{ $theme['badge_border'] }} {{ $theme['badge_bg'] }} px-4 py-2.5 text-sm font-medium {{ $theme['badge_text'] }} hover:opacity-80">
                        <i class="fa-solid fa-filter text-[10px]"></i>
                        Filter
                    </button>
                    <a href="{{ route('signal.index', $signalCategory) }}"
                        class="inline-flex items-center gap-1.5 rounded-xl border border-white/10 bg-white/5 px-4 py-2.5 text-sm font-medium text-smoke hover:border-white/20 hover:text-ivory">
                        <i class="fa-solid fa-xmark text-[10px]"></i>
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            @forelse ($signals as $signal)
                @php($signalLabel = strtoupper($signal->potensi) . ' ' . $signal->timeframe)
                <div
                    class="group relative flex flex-col justify-between overflow-hidden rounded-2xl border border-black/8 bg-black/3 p-5 shadow-lg transition-all duration-300 hover:-translate-y-1 hover:border-black/16 hover:bg-black/5">
                    @if ($signal->image_url)
                        <div class="relative mb-4 -mx-5 -mt-5 h-44 overflow-hidden border-b border-black/6 bg-noir">
                            <img src="{{ $signal->image_url }}" alt="{{ $signalLabel }}"
                                class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105">
                            <div class="absolute left-3 top-3 flex flex-wrap gap-1.5">
                                <span
                                    class="inline-flex items-center gap-1.5 rounded-lg border {{ $theme['badge_border'] }} {{ $theme['badge_bg'] }} px-2.5 py-1 text-[11px] font-semibold {{ $theme['badge_text'] }} backdrop-blur-md">
                                    <i class="fa-solid fa-folder-open text-[10px]"></i>
                                    {{ $signalCategory->name }}
                                </span>
                            </div>
                        </div>
                    @else
                        <div class="mb-3 flex items-center justify-between gap-2">
                            <span
                                class="inline-flex items-center gap-1.5 rounded-lg border {{ $theme['badge_border'] }} {{ $theme['badge_bg'] }} px-2.5 py-1 text-[11px] font-semibold {{ $theme['badge_text'] }}">
                                <i class="fa-solid fa-folder-open text-[10px]"></i>
                                {{ $signalCategory->name }}
                            </span>
                            <span
                                class="inline-flex items-center gap-1 rounded-md border border-white/10 bg-white/5 px-2 py-0.5 text-[10px] font-semibold uppercase text-smoke/80">
                                {{ $signal->timeframe }}
                            </span>
                        </div>
                    @endif

                    <div class="flex-1 space-y-3">
                        <div>
                            <h3 class="text-base font-semibold leading-snug text-ivory transition-colors line-clamp-2">
                                {{ $signalLabel }}
                            </h3>
                        </div>

                        <div class="flex flex-wrap gap-2">
                            <span class="inline-flex items-center gap-1 rounded-lg border border-blue-500/20 bg-blue-500/10 px-2.5 py-1 text-[11px] font-semibold uppercase text-blue-700">
                                {{ $signal->potensi }}
                            </span>
                            <span class="inline-flex items-center gap-1 rounded-lg border border-white/10 bg-white/5 px-2.5 py-1 text-[11px] font-semibold uppercase text-champagne">
                                {{ $signal->timeframe }}
                            </span>
                        </div>

                        <div class="grid gap-2 text-xs text-smoke">
                            <div class="rounded-xl border border-emerald-500/20 bg-emerald-500/8 px-3 py-2">
                                <p class="text-[10px] font-semibold uppercase tracking-[0.16em] text-emerald-700/70">Taking Profit</p>
                                <p class="mt-1 font-medium text-emerald-700">{{ $signal->taking_profit }}</p>
                            </div>
                            <div class="rounded-xl border border-rose-500/20 bg-rose-500/8 px-3 py-2">
                                <p class="text-[10px] font-semibold uppercase tracking-[0.16em] text-rose-700/70">Stop Loss</p>
                                <p class="mt-1 font-medium text-rose-700">{{ $signal->stop_loss }}</p>
                            </div>
                            <div class="rounded-xl border border-white/10 bg-white/5 px-3 py-2">
                                <p class="text-[10px] font-semibold uppercase tracking-[0.16em] text-smoke/60">Sumber</p>
                                <p class="mt-1 line-clamp-1 font-medium text-ivory">{{ $signal->sumber }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 flex items-center justify-between border-t border-black/6 pt-3.5">
                        <a href="{{ route('signal.show', ['signalCategory' => $signalCategory, 'signal' => $signal]) }}"
                            class="inline-flex items-center gap-1.5 rounded-lg border border-white/10 bg-white/5 px-2.5 py-1.5 text-xs font-medium text-smoke hover:border-white/15 hover:text-ivory">
                            <i class="fa-solid fa-eye text-[10px]"></i>
                            Detail
                        </a>

                        <div class="flex items-center gap-1.5">
                            <a href="{{ route('signal.edit', ['signalCategory' => $signalCategory, 'signal' => $signal]) }}"
                                class="inline-flex items-center gap-1.5 rounded-lg border {{ $theme['badge_border'] }} {{ $theme['badge_bg'] }} px-2.5 py-1.5 text-xs font-medium {{ $theme['badge_text'] }} hover:opacity-80">
                                <i class="fa-solid fa-pen text-[10px]"></i>
                                Edit
                            </a>
                            <form
                                action="{{ route('signal.destroy', ['signalCategory' => $signalCategory, 'signal' => $signal]) }}"
                                method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" data-confirm-submit data-confirm-intent="delete"
                                    data-confirm-title="Hapus signal ini?"
                                    data-confirm-message="Signal {{ $signalLabel }} akan dihapus permanen."
                                    data-confirm-action-label="Ya, hapus"
                                    class="inline-flex items-center gap-1.5 rounded-lg border border-red-400/25 bg-red-500/8 px-2.5 py-1.5 text-xs font-medium text-red-700/80 hover:border-red-400/40 hover:bg-red-500/16 hover:text-red-800">
                                    <i class="fa-solid fa-trash text-[10px]"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full rounded-2xl border border-black/8 bg-black/3 p-12 text-center text-sm text-smoke">
                    <div class="mx-auto flex max-w-xs flex-col items-center gap-3">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl border border-black/8 bg-black/4 text-smoke">
                            <i class="fa-solid fa-image text-lg"></i>
                        </div>
                        <p class="text-sm text-smoke">Belum ada signal pada kategori ini.</p>
                    </div>
                </div>
            @endforelse
        </div>

        <div
            class="flex flex-col items-start justify-between gap-3 rounded-2xl border border-black/8 bg-black/3 px-6 py-4 sm:flex-row sm:items-center">
            <p class="text-xs text-smoke">
                Menampilkan <span
                    class="font-medium text-champagne/80">{{ $signals->firstItem() ?? 0 }}-{{ $signals->lastItem() ?? 0 }}</span>
                dari <span class="font-medium text-champagne/80">{{ $signals->total() }}</span> signal
            </p>
            <div class="text-sm">{{ $signals->appends(request()->query())->links() }}</div>
        </div>
    </section>
@endsection
