@extends('layouts.app')

@section('title', 'Edit Produk ' . $sectionName)

@section('content')
    <section class="space-y-6">
        @include('components.molecules.page-header', [
            'eyebrow' => 'Update produk',
            'title' => 'Edit ' . $produk->nama_produk,
            'description' => 'Perbarui informasi produk agar data katalog tetap akurat dan konsisten.',
        ])

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
