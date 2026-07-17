@extends('layouts.app')

@section('title', 'Edit Pengumuman')

@section('content')
    <section class="space-y-6">
        @include('components.molecules.page-header', [
            'eyebrow' => 'Update pengumuman',
            'title' => $informasi->title,
        ])

        <form action="{{ route('pengumuman.update', $informasi) }}" method="POST" enctype="multipart/form-data"
            class="space-y-6">
            @csrf
            @method('PUT')
            @include('pengumuman.partials.form', [
                'informasi' => $informasi,
                'submitLabel' => 'Update Pengumuman',
                'cancelUrl' => route('pengumuman.show', $informasi),
            ])
        </form>
    </section>
@endsection
