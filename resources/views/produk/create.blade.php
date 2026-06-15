@extends('layouts.app')

@section('title', 'Tambah Produk ' . $sectionLabel)

@section('content')
    <section class="space-y-6">
        <div class="flex flex-col gap-3 rounded-2xl border border-white/8 bg-white/4 p-6">
            <p class="text-xs font-medium uppercase tracking-[0.18em] text-smoke">Produk baru</p>
            <h2 class="text-3xl font-semibold tracking-[-0.04em] text-white">Tambah produk {{ strtolower($sectionLabel) }}</h2>
            <p class="max-w-2xl text-sm leading-7 text-smoke">
                Isi data utama produk dengan lengkap agar katalog lebih rapi dan mudah dicari.
            </p>
        </div>

        <form action="{{ route('produk.store', ['section' => $section]) }}" method="POST" enctype="multipart/form-data"
            class="space-y-6">
            @csrf
            @include('produk.partials.form', [
                'sectionLabel' => $sectionLabel,
                'submitLabel' => 'Simpan Produk',
                'cancelUrl' => route('produk.index', ['section' => $section]),
            ])
        </form>
    </section>
@endsection
