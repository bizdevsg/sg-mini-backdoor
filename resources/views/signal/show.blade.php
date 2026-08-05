@extends('layouts.app')

@php($signalLabel = strtoupper($signal->potensi) . ' ' . $signal->timeframe)

@section('title', $signalLabel)

@section('content')
    <section class="space-y-6">
        <div class="rounded-[28px] border border-black/8 bg-black/3 px-7 py-6">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                <div class="space-y-2">
                    <div class="flex items-center gap-2 text-xs text-smoke/60">
                        <a href="{{ route('signal-categories.index') }}" class="hover:text-smoke">Kategori Signal</a>
                        <i class="fa-solid fa-chevron-right text-[8px]"></i>
                        <a href="{{ route('signal.index', $signalCategory) }}"
                            class="hover:text-smoke">{{ $signalCategory->name }}</a>
                    </div>
                    <div class="flex flex-wrap items-center gap-2">
                        <span
                            class="inline-flex items-center gap-1.5 rounded-lg border border-blue-500/25 bg-blue-500/10 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.16em] text-blue-700">
                            {{ $signal->potensi }}
                        </span>
                        <span
                            class="inline-flex items-center gap-1.5 rounded-lg border border-white/10 bg-white/5 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.16em] text-champagne">
                            {{ $signal->timeframe }}
                        </span>
                    </div>
                    <h1 class="text-2xl font-semibold text-ivory lg:text-3xl">{{ $signalLabel }}</h1>
                    <p class="text-sm text-smoke">Detail setup signal untuk kategori {{ $signalCategory->name }}.</p>
                </div>
                <a href="{{ route('signal.edit', ['signalCategory' => $signalCategory, 'signal' => $signal]) }}"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-500 px-5 py-2.5 text-sm font-semibold text-white transition-all duration-200 hover:bg-blue-600">
                    <i class="fa-solid fa-pen text-xs"></i>
                    Edit Signal
                </a>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_320px]">
            <div class="rounded-2xl border border-black/8 bg-black/3 p-6">
                <div class="grid gap-4 md:grid-cols-2">
                    <div class="rounded-2xl border border-white/10 bg-white/5 p-5">
                        <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-smoke/70">Kategori</p>
                        <p class="mt-2 text-lg font-semibold text-ivory">{{ $signalCategory->name }}</p>
                    </div>
                    <div class="rounded-2xl border border-white/10 bg-white/5 p-5">
                        <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-smoke/70">Sumber</p>
                        <p class="mt-2 text-lg font-semibold text-ivory">{{ $signal->sumber }}</p>
                    </div>
                    <div class="rounded-2xl border border-emerald-500/20 bg-emerald-500/8 p-5">
                        <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-emerald-700/80">Taking Profit</p>
                        <p class="mt-2 text-lg font-semibold text-emerald-700">{{ $signal->taking_profit }}</p>
                    </div>
                    <div class="rounded-2xl border border-rose-500/20 bg-rose-500/8 p-5">
                        <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-rose-700/80">Stop Loss</p>
                        <p class="mt-2 text-lg font-semibold text-rose-700">{{ $signal->stop_loss }}</p>
                    </div>
                </div>
            </div>

            <aside class="space-y-6">
                <div class="rounded-2xl border border-black/8 bg-black/3 p-5">
                    <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-smoke/70">Informasi Signal</p>
                    <div class="mt-4 space-y-3 text-sm text-smoke">
                        <div>
                            <p class="text-xs uppercase tracking-[0.16em] text-smoke/60">Potensi</p>
                            <p class="mt-1 font-medium uppercase text-ivory">{{ $signal->potensi }}</p>
                        </div>
                        <div>
                            <p class="text-xs uppercase tracking-[0.16em] text-smoke/60">Timeframe</p>
                            <p class="mt-1 font-medium text-ivory">{{ $signal->timeframe }}</p>
                        </div>
                        <div>
                            <p class="text-xs uppercase tracking-[0.16em] text-smoke/60">Dibuat</p>
                            <p class="mt-1 font-medium text-ivory">{{ $signal->created_at?->format('d M Y H:i') ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                @if ($signal->image_url)
                    <div class="space-y-3 rounded-2xl border border-black/8 bg-black/3 p-5">
                        <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-smoke/70">Gambar</p>
                        <div class="overflow-hidden rounded-xl border border-black/8">
                            <img src="{{ $signal->image_url }}" alt="{{ $signalLabel }}" class="w-full object-cover">
                        </div>
                    </div>
                @endif
            </aside>
        </div>
    </section>
@endsection
