@extends('layouts.app')

@section('title', 'Edit Penghargaan')

@section('content')
    <section class="space-y-6">
        @include('components.molecules.page-header', [
            'eyebrow' => 'Update penghargaan',
            'title' => $penghargaan->title,
        ])

        <form action="{{ route('penghargaan.update', $penghargaan) }}" method="POST" enctype="multipart/form-data"
            class="space-y-6">
            @csrf
            @method('PUT')
            @include('penghargaan.partials.form', [
                'penghargaan' => $penghargaan,
                'submitLabel' => 'Update Penghargaan',
                'cancelUrl' => route('penghargaan.index'),
            ])
        </form>
    </section>
@endsection
