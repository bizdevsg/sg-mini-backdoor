@extends('layouts.app')

@section('title', 'Edit Berita')

@section('content')
    <section class="space-y-6">
        <div class="rounded-[28px] border border-white/8 bg-white/3 px-7 py-6 shadow-[0_24px_60px_rgba(0,0,0,0.3)]">
            <h1 class="text-2xl font-semibold text-white">Edit Berita</h1>
            <p class="mt-2 text-sm text-smoke">Perbarui berita <span class="text-gold-soft">{{ $berita->title_id }}</span>.</p>
        </div>

        <form action="{{ route('berita.update', ['beritaCategory' => $beritaCategory, 'berita' => $berita]) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')
            @include('berita.partials.form', [
                'berita' => $berita,
                'beritaCategory' => $beritaCategory,
                'submitLabel' => 'Simpan Perubahan',
                'cancelUrl' => route('berita.show', ['beritaCategory' => $beritaCategory, 'berita' => $berita]),
            ])
        </form>
    </section>
@endsection
