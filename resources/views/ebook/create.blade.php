@extends('layouts.app')

@section('title', 'Tambah Ebook')

@section('content')
    <section class="space-y-6">
        @include('components.molecules.page-header', [
            'eyebrow' => 'Ebook baru',
            'title' => 'Tambah ebook ' . $ebookCategory->name,
        ])

        <form action="{{ route('ebook.store', $ebookCategory) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @include('ebook.partials.form', [
                'ebookCategory' => $ebookCategory,
                'submitLabel' => 'Simpan Ebook',
                'cancelUrl' => route('ebook.index', $ebookCategory),
            ])
        </form>
    </section>
@endsection
