@extends('layouts.app')

@section('title', 'Edit Ebook')

@section('content')
    <section class="space-y-6">
        @include('components.molecules.page-header', [
            'eyebrow' => 'Update ebook',
            'title' => $ebook->title . ' - ' . $ebookCategory->name,
        ])

        <form action="{{ route('ebook.update', ['ebookCategory' => $ebookCategory, 'ebook' => $ebook]) }}" method="POST" enctype="multipart/form-data"
            class="space-y-6">
            @csrf
            @method('PUT')
            @include('ebook.partials.form', [
                'ebook' => $ebook,
                'ebookCategory' => $ebookCategory,
                'submitLabel' => 'Update Ebook',
                'cancelUrl' => route('ebook.show', ['ebookCategory' => $ebookCategory, 'ebook' => $ebook]),
            ])
        </form>
    </section>
@endsection
