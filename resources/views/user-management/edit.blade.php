@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
    <section class="space-y-6">
        @include('components.molecules.page-header', [
            'eyebrow' => 'Update user',
            'title' => $managedUser->name,
        ])

        <form action="{{ route('user-management.update', $managedUser) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            @include('user-management.partials.form', [
                'confirmTitle' => 'Simpan perubahan user?',
                'confirmMessage' => 'Perubahan data user akan langsung diterapkan setelah disimpan.',
                'confirmActionLabel' => 'Ya, update',
                'managedUser' => $managedUser,
                'submitLabel' => 'Update User',
                'cancelUrl' => route('user-management.index'),
            ])
        </form>

        @include('user-management.partials.confirm-modal')
    </section>
@endsection
