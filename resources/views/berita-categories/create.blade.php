@extends('layouts.app')

@section('title', 'Tambah Kategori Berita')

@section('content')
    <section class="space-y-6">
        <div class="rounded-[28px] border border-black/8 bg-black/3 px-7 py-6  ">
            <h1 class="text-2xl font-semibold text-ivory">Tambah Kategori Berita</h1>
            <p class="mt-2 text-sm text-smoke">Buat kategori baru untuk mengelompokkan berita.</p>
        </div>

        <form action="{{ route('berita-categories.store') }}" method="POST" class="space-y-6">
            @csrf
            @include('berita-categories.partials.form', [
                'confirmTitle' => 'Simpan kategori baru?',
                'confirmMessage' => 'Pastikan nama kategori sudah benar sebelum disimpan.',
                'confirmActionLabel' => 'Ya, simpan',
                'submitLabel' => 'Simpan Kategori',
                'cancelUrl' => route('berita-categories.index'),
            ])
        </form>
    </section>
@endsection
