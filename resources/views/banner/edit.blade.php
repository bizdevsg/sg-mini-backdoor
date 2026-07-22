@extends('layouts.app')

@section('title', 'Edit Banner')

@section('content')
    <section class="space-y-6">

        {{-- ══════════════════════════════════════════════
             HERO HEADER
        ══════════════════════════════════════════════ --}}
        <div class="relative overflow-hidden rounded-[28px] border border-white/8 bg-[radial-gradient(ellipse_70%_80%_at_0%_0%,rgba(199,161,90,0.14),transparent),linear-gradient(160deg,rgba(255,255,255,0.05)_0%,rgba(255,255,255,0.01)_100%)] px-7 py-6 shadow-[0_24px_60px_rgba(0,0,0,0.3)] motion-safe:motion-preset-slide-down-sm lg:px-9 lg:py-8">
            <div class="pointer-events-none absolute -right-12 -top-12 h-40 w-40 rounded-full bg-gold/8 blur-[56px]"></div>
            <div class="pointer-events-none absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-gold/35 to-transparent"></div>

            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div class="space-y-2 motion-safe:motion-preset-slide-right-sm motion-safe:motion-delay-[60ms]">
                    <span class="inline-flex items-center gap-2 rounded-full border border-gold/20 bg-gold/8 px-3 py-1 text-[10px] font-semibold uppercase tracking-[0.24em] text-gold-soft/90">
                        <span class="h-1.5 w-1.5 animate-pulse rounded-full bg-gold"></span>
                        Update Image Banner
                    </span>
                    <h1 class="text-2xl font-semibold tracking-[-0.04em] text-white lg:text-3xl">
                        Edit:
                        <span class="bg-gradient-to-r from-gold-soft to-champagne bg-clip-text text-transparent">{{ $banner->title ?: ('Banner #' . $banner->id) }}</span>
                    </h1>
                    <p class="max-w-xl text-sm leading-6 text-smoke">
                        Perbarui informasi, gambar, urutan slide, atau status aktif banner ini.
                    </p>
                </div>
                <a href="{{ route('banner.index') }}"
                    class="inline-flex w-fit items-center gap-1.5 rounded-xl border border-white/10 bg-white/5 px-4 py-2.5 text-sm font-medium text-smoke transition-all duration-200 hover:border-white/18 hover:bg-white/8 hover:text-white motion-safe:motion-preset-slide-left-sm motion-safe:motion-delay-[80ms]">
                    <i class="fa-solid fa-arrow-left text-[10px]"></i>
                    Kembali
                </a>
            </div>
        </div>

        <form action="{{ route('banner.update', $banner) }}" method="POST" enctype="multipart/form-data"
            class="space-y-6">
            @csrf
            @method('PUT')
            @include('banner.partials.form', [
                'banner' => $banner,
                'submitLabel' => 'Update Banner',
                'cancelUrl' => route('banner.index'),
            ])
        </form>
    </section>
@endsection
