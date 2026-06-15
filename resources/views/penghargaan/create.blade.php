@extends('layouts.app')

@section('title', 'Tambah Penghargaan')

@section('content')
    <section class="space-y-6">
        <div class="rounded-2xl border border-white/8 bg-white/4 p-6">
            <p class="text-xs font-medium uppercase tracking-[0.18em] text-smoke">Penghargaan baru</p>
            <h2 class="mt-2 text-3xl font-semibold tracking-[-0.04em] text-white">Tambah penghargaan</h2>
        </div>

        <form action="{{ route('penghargaan.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @include('penghargaan.partials.form', [
                'submitLabel' => 'Simpan Penghargaan',
                'cancelUrl' => route('penghargaan.index'),
            ])
        </form>
    </section>
@endsection
