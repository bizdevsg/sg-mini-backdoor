@extends('layouts.app')

@section('title', 'Tambah Legalitas')

@section('content')
    <section class="space-y-6">
        @include('components.molecules.page-header', [
            'eyebrow' => 'Legalitas baru',
            'title' => 'Tambah legalitas',
        ])

        <form action="{{ route('legalitas.store') }}" method="POST" class="space-y-6">
            @csrf
            @include('legalitas.partials.form', [
                'submitLabel' => 'Simpan Legalitas',
                'cancelUrl' => route('legalitas.index'),
            ])
        </form>
    </section>
@endsection
