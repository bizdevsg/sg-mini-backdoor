@extends('layouts.app')

@section('title', 'Tambah Berita')

@section('content')
    <section class="space-y-6">
        <div class="rounded-[28px] border border-white/8 bg-white/3 px-7 py-6 shadow-[0_24px_60px_rgba(0,0,0,0.3)]">
            <h1 class="text-2xl font-semibold text-white">Tambah Berita</h1>
            <p class="mt-2 text-sm text-smoke">Buat berita baru untuk kategori <span class="text-gold-soft">{{ $beritaCategory->name }}</span>.</p>
        </div>

        <form action="{{ route('berita.store', $beritaCategory) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @include('berita.partials.form', [
                'beritaCategory' => $beritaCategory,
                'submitLabel' => 'Simpan Berita',
                'cancelUrl' => route('berita.index', $beritaCategory),
            ])
        </form>
    </section>
@endsection
