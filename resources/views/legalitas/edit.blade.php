@extends('layouts.app')

@section('title', 'Edit Legalitas')

@section('content')
    <section class="space-y-6">
        @include('components.molecules.page-header', [
            'eyebrow' => 'Update legalitas',
            'title' => $legalitas->title,
        ])

        <form action="{{ route('legalitas.update', $legalitas) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            @include('legalitas.partials.form', [
                'legalitas' => $legalitas,
                'submitLabel' => 'Update Legalitas',
                'cancelUrl' => route('legalitas.index'),
            ])
        </form>
    </section>
@endsection
