@extends('layouts.app')

@section('title', 'Tambah Kategori Signal')

@section('content')
    <section class="space-y-6">
        <div class="rounded-[28px] border border-white/8 bg-white/3 px-7 py-6 shadow-[0_24px_60px_rgba(0,0,0,0.3)]">
            <h1 class="text-2xl font-semibold text-white">Tambah Kategori Signal</h1>
            <p class="mt-2 text-sm text-smoke">Buat kategori baru untuk mengelompokkan signal.</p>
        </div>

        <form action="{{ route('signal-categories.store') }}" method="POST" class="space-y-6">
            @csrf
            @include('signal-categories.partials.form', [
                'submitLabel' => 'Simpan Kategori',
                'cancelUrl' => route('signal-categories.index'),
            ])
        </form>
    </section>
@endsection
