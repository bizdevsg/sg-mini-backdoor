@extends('layouts.app')

@section('title', 'Tambah Penghargaan')

@section('content')
    <section class="space-y-6">
        @include('components.molecules.page-header', [
            'eyebrow' => 'Penghargaan baru',
            'title' => 'Tambah penghargaan',
        ])

        <form action="{{ route('penghargaan.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @include('penghargaan.partials.form', [
                'submitLabel' => 'Simpan Penghargaan',
                'cancelUrl' => route('penghargaan.index'),
            ])
        </form>
    </section>
@endsection
