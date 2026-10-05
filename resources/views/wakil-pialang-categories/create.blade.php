@extends('layouts.app')

@section('title', 'Tambah Kategori Wakil Pialang')

@section('content')
    <section class="space-y-6">
        <div class="rounded-[28px] border border-black/8 bg-black/3 px-7 py-6">
            <h1 class="text-2xl font-semibold text-ivory">Tambah Kategori Wakil Pialang</h1>
            <p class="mt-2 text-sm text-smoke">Buat kategori baru beserta data kantor cabangnya.</p>
        </div>

        <form action="{{ route('wakil-pialang-categories.store') }}" method="POST" class="space-y-6">
            @csrf
            @include('wakil-pialang-categories.partials.form', [
                'confirmTitle' => 'Simpan kategori baru?',
                'confirmMessage' => 'Pastikan data kategori sudah benar sebelum disimpan.',
                'confirmActionLabel' => 'Ya, simpan',
                'submitLabel' => 'Simpan Kategori',
                'cancelUrl' => route('wakil-pialang-categories.index'),
            ])
        </form>
    </section>
@endsection
