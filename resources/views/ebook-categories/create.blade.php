@extends('layouts.app')

@section('title', 'Tambah Kategori Ebook')

@section('content')
    <section class="space-y-6">
        @include('components.molecules.page-header', [
            'eyebrow' => 'Kategori ebook baru',
            'title' => 'Tambah kategori ebook',
        ])

        <form action="{{ route('ebook-categories.store') }}" method="POST" class="space-y-6">
            @csrf
            @include('ebook-categories.partials.form', [
                'submitLabel' => 'Simpan Kategori Ebook',
                'cancelUrl' => route('ebook-categories.index'),
            ])
        </form>
    </section>
@endsection
