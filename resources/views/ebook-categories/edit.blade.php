@extends('layouts.app')

@section('title', 'Edit Kategori Ebook')

@section('content')
    <section class="space-y-6">
        @include('components.molecules.page-header', [
            'eyebrow' => 'Update kategori ebook',
            'title' => $ebookCategory->name,
        ])

        <form action="{{ route('ebook-categories.update', $ebookCategory) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            @include('ebook-categories.partials.form', [
                'ebookCategory' => $ebookCategory,
                'submitLabel' => 'Update Kategori Ebook',
                'cancelUrl' => route('ebook-categories.index'),
            ])
        </form>
    </section>
@endsection
