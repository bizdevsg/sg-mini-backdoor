@extends('layouts.app')

@section('title', 'Tambah Wakil Pialang Berjangka')

@section('content')
    <section class="space-y-6">
        <div
            class="relative overflow-hidden rounded-[28px] border border-black/8 bg-[radial-gradient(ellipse_70%_80%_at_0%_0%,rgba(199,161,90,0.14),transparent),linear-gradient(160deg,rgba(21,17,13,0.05)_0%,rgba(21,17,13,0.01)_100%)] px-7 py-6 lg:px-9 lg:py-8">
            <div class="pointer-events-none absolute -right-12 -top-12 h-40 w-40 rounded-full bg-gold/8 blur-[56px]"></div>
            <div
                class="pointer-events-none absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-gold/35 to-transparent">
            </div>

            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div class="space-y-2">
                    <span
                        class="inline-flex items-center gap-2 rounded-full border border-gold/20 bg-gold/8 px-3 py-1 text-[10px] font-semibold uppercase tracking-[0.24em] text-gold-soft/90">
                        <span class="h-1.5 w-1.5 animate-pulse rounded-full bg-gold"></span>
                        Wakil Pialang Baru
                    </span>
                    <h1 class="text-2xl font-semibold tracking-[-0.04em] text-ivory lg:text-3xl">
                        Tambah Wakil Pialang
                        <span
                            class="bg-gradient-to-r from-gold-soft to-champagne bg-clip-text text-transparent">{{ $wakilPialangCategory->nama_kategori }}</span>
                    </h1>
                    <p class="max-w-xl text-sm leading-6 text-smoke">
                        Isi nama, no. identitas, dan status wakil pialang berjangka.
                    </p>
                </div>
                <a href="{{ route('wakil-pialang.index', $wakilPialangCategory) }}"
                    class="inline-flex w-fit items-center gap-1.5 rounded-xl border border-black/10 bg-black/5 px-4 py-2.5 text-sm font-medium text-smoke transition-all duration-200 hover:border-black/18 hover:bg-black/8 hover:text-ivory">
                    <i class="fa-solid fa-arrow-left text-[10px]"></i>
                    Kembali
                </a>
            </div>
        </div>

        <form action="{{ route('wakil-pialang.store', $wakilPialangCategory) }}" method="POST" class="space-y-6">
            @csrf
            @include('wakil-pialang.partials.form', [
                'confirmTitle' => 'Simpan wakil pialang baru?',
                'confirmMessage' => 'Pastikan nama dan no. identitas sudah benar sebelum disimpan.',
                'confirmActionLabel' => 'Ya, simpan',
                'submitLabel' => 'Simpan Wakil Pialang',
                'cancelUrl' => route('wakil-pialang.index', $wakilPialangCategory),
            ])
        </form>
    </section>
@endsection
