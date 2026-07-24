@extends('layouts.app')

@section('title', 'Edit Kategori Signal')

@section('content')
    <section class="space-y-6">
        <div class="rounded-[28px] border border-white/8 bg-white/3 px-7 py-6 shadow-[0_24px_60px_rgba(0,0,0,0.3)]">
            <h1 class="text-2xl font-semibold text-white">Edit Kategori Signal</h1>
            <p class="mt-2 text-sm text-smoke">Perbarui kategori <span class="text-blue-300">{{ $signalCategory->name }}</span>.</p>
        </div>

        <form action="{{ route('signal-categories.update', $signalCategory) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            @include('signal-categories.partials.form', [
                'signalCategory' => $signalCategory,
                'submitLabel' => 'Simpan Perubahan',
                'cancelUrl' => route('signal-categories.index'),
            ])
        </form>
    </section>
@endsection
