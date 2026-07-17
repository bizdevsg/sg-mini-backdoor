@extends('layouts.app')

@section('title', 'Tambah Pengumuman')

@section('content')
    <section class="space-y-6">
        @include('components.molecules.page-header', [
            'eyebrow' => 'Pengumuman baru',
            'title' => 'Tambah pengumuman',
        ])

        <form action="{{ route('pengumuman.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @include('pengumuman.partials.form', [
                'submitLabel' => 'Simpan Pengumuman',
                'cancelUrl' => route('pengumuman.index'),
            ])
        </form>
    </section>
@endsection
