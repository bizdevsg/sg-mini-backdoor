@extends('layouts.app')

@section('title', 'Edit Kategori Wakil Pialang')

@section('content')
    <section class="space-y-6">
        <div class="rounded-[28px] border border-black/8 bg-black/3 px-7 py-6">
            <h1 class="text-2xl font-semibold text-ivory">Edit Kategori: {{ $wakilPialangCategory->nama_kategori }}</h1>
            <p class="mt-2 text-sm text-smoke">Perbarui data kategori dan kantor cabang.</p>
        </div>

        <form action="{{ route('wakil-pialang-categories.update', $wakilPialangCategory) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            @include('wakil-pialang-categories.partials.form', [
                'wakilPialangCategory' => $wakilPialangCategory,
                'confirmTitle' => 'Simpan perubahan kategori?',
                'confirmMessage' => 'Pastikan data kategori sudah benar sebelum disimpan.',
                'confirmActionLabel' => 'Ya, update',
                'submitLabel' => 'Update Kategori',
                'cancelUrl' => route('wakil-pialang-categories.index'),
            ])
        </form>
    </section>
@endsection
