@extends('layouts.app')

@section('title', 'Tambah Berita')

@section('content')
    <section class="space-y-6">
        <div class="rounded-[28px] border border-black/8 bg-black/3 px-7 py-6  ">
            <h1 class="text-2xl font-semibold text-ivory">Tambah Berita</h1>
            <p class="mt-2 text-sm text-smoke">Buat berita baru untuk kategori <span
                    class="text-gold-soft">{{ $beritaCategory->name }}</span>.</p>
        </div>

        <form action="{{ route('berita.store', $beritaCategory) }}" method="POST" enctype="multipart/form-data"
            class="space-y-6">
            @csrf
            @include('berita.partials.form', [
                'beritaCategory' => $beritaCategory,
                'confirmTitle' => 'Simpan berita baru?',
                'confirmMessage' => 'Pastikan metadata dan konten bilingual sudah benar sebelum disimpan.',
                'confirmActionLabel' => 'Ya, simpan',
                'submitLabel' => 'Simpan Berita',
                'cancelUrl' => route('berita.index', $beritaCategory),
            ])
        </form>
    </section>
@endsection
