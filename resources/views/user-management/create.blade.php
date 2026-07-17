@extends('layouts.app')

@section('title', 'Tambah User')

@section('content')
    <section class="space-y-6">
        @include('components.molecules.page-header', [
            'eyebrow' => 'User baru',
            'title' => 'Tambah user',
        ])

        <form action="{{ route('user-management.store') }}" method="POST" class="space-y-6">
            @csrf
            @include('user-management.partials.form', [
                'confirmTitle' => 'Simpan user baru?',
                'confirmMessage' => 'Pastikan nama, email, role, dan password sudah benar sebelum user dibuat.',
                'confirmActionLabel' => 'Ya, simpan',
                'submitLabel' => 'Simpan User',
                'cancelUrl' => route('user-management.index'),
            ])
        </form>

        @include('user-management.partials.confirm-modal')
    </section>
@endsection
