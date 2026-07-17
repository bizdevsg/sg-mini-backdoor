@extends('layouts.app')

@section('title', 'Tambah Banner')

@section('content')
    <section class="space-y-6">
        @include('components.molecules.page-header', [
            'eyebrow' => 'Slide carousel baru',
            'title' => 'Tambah image banner',
        ])

        <form action="{{ route('banner.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @include('banner.partials.form', [
                'submitLabel' => 'Simpan Banner',
                'cancelUrl' => route('banner.index'),
            ])
        </form>
    </section>
@endsection
