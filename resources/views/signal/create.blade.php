@extends('layouts.app')

@section('title', 'Tambah Signal')

@section('content')
    <section class="space-y-6">
        <div class="rounded-[28px] border border-white/8 bg-white/3 px-7 py-6 shadow-[0_24px_60px_rgba(0,0,0,0.3)]">
            <h1 class="text-2xl font-semibold text-white">Tambah Signal</h1>
            <p class="mt-2 text-sm text-smoke">Buat signal baru untuk kategori <span class="text-blue-300">{{ $signalCategory->name }}</span>.</p>
        </div>

        <form action="{{ route('signal.store', $signalCategory) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @include('signal.partials.form', [
                'signalCategory' => $signalCategory,
                'submitLabel' => 'Simpan Signal',
                'cancelUrl' => route('signal.index', $signalCategory),
            ])
        </form>
    </section>
@endsection
