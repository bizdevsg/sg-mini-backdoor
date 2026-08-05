@extends('layouts.app')

@section('title', 'Tambah Signal')

@section('content')
    <section class="space-y-6">
        <div class="rounded-[28px] border border-black/8 bg-black/3 px-7 py-6  ">
            <h1 class="text-2xl font-semibold text-ivory">Tambah Signal</h1>
            <p class="mt-2 text-sm text-smoke">Buat signal baru untuk kategori <span
                    class="text-blue-700">{{ $signalCategory->name }}</span> dengan setup potensi, timeframe, TP, SL, dan sumber.</p>
        </div>

        <form action="{{ route('signal.store', $signalCategory) }}" method="POST" enctype="multipart/form-data"
            class="space-y-6">
            @csrf
            @include('signal.partials.form', [
                'signalCategory' => $signalCategory,
                'confirmTitle' => 'Simpan signal baru?',
                'confirmMessage' => 'Pastikan setup trading yang diisi sudah benar sebelum disimpan.',
                'confirmActionLabel' => 'Ya, simpan',
                'submitLabel' => 'Simpan Signal',
                'cancelUrl' => route('signal.index', $signalCategory),
            ])
        </form>
    </section>
@endsection
