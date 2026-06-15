@extends('layouts.app')

@section('title', 'Edit Produk ' . $sectionLabel)

@section('content')
    <section class="space-y-6">
        <div class="flex flex-col gap-3 rounded-2xl border border-white/8 bg-white/4 p-6">
            <p class="text-xs font-medium uppercase tracking-[0.18em] text-smoke">Update produk</p>
            <h2 class="text-3xl font-semibold tracking-[-0.04em] text-white">Edit {{ $produk->nama_produk }}</h2>
            <p class="max-w-2xl text-sm leading-7 text-smoke">
                Perbarui informasi produk agar data katalog tetap akurat dan konsisten.
            </p>
        </div>

        <form action="{{ route('produk.update', ['section' => $section, 'produk' => $produk]) }}" method="POST"
            enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')
            @include('produk.partials.form', [
                'produk' => $produk,
                'sectionLabel' => $sectionLabel,
                'submitLabel' => 'Update Produk',
                'cancelUrl' => route('produk.show', ['produk' => $produk, 'section' => $section]),
            ])
        </form>
    </section>
@endsection
