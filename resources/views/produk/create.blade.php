@extends('layouts.app')

@section('title', 'Tambah Produk ' . $sectionName)

@section('content')
    <section class="space-y-6">
        @include('components.molecules.page-header', [
            'eyebrow' => 'Produk baru',
            'title' => 'Tambah produk ' . strtolower($sectionName),
            'description' => 'Isi data utama produk dengan lengkap agar katalog lebih rapi dan mudah dicari.',
        ])

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
