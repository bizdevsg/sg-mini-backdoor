@extends('layouts.app')

@section('title', 'Edit Berita')

@section('content')
    <section class="space-y-6">
        <div class="rounded-[28px] border border-black/8 bg-black/3 px-7 py-6  ">
            <h1 class="text-2xl font-semibold text-ivory">Edit Berita</h1>
            <p class="mt-2 text-sm text-smoke">Perbarui berita <span class="text-gold-soft">{{ $berita->title_id }}</span>.
            </p>
        </div>

        <form action="{{ route('berita.update', ['beritaCategory' => $beritaCategory, 'berita' => $berita]) }}" method="POST"
            enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')
            @include('berita.partials.form', [
                'berita' => $berita,
                'beritaCategory' => $beritaCategory,
                'confirmTitle' => 'Simpan perubahan berita ini?',
                'confirmMessage' => 'Perubahan akan langsung diterapkan setelah disimpan.',
                'confirmActionLabel' => 'Ya, update',
                'submitLabel' => 'Simpan Perubahan',
                'cancelUrl' => route('berita.show', ['beritaCategory' => $beritaCategory, 'berita' => $berita]),
            ])
        </form>
    </section>
@endsection
