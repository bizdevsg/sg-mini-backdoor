@extends('layouts.app')

@section('title', 'Edit Signal')

@section('content')
    <section class="space-y-6">
        <div class="rounded-[28px] border border-white/8 bg-white/3 px-7 py-6 shadow-[0_24px_60px_rgba(0,0,0,0.3)]">
            <h1 class="text-2xl font-semibold text-white">Edit Signal</h1>
            <p class="mt-2 text-sm text-smoke">Perbarui signal <span class="text-blue-300">{{ $signal->title_id }}</span>.</p>
        </div>

        <form action="{{ route('signal.update', ['signalCategory' => $signalCategory, 'signal' => $signal]) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')
            @include('signal.partials.form', [
                'signal' => $signal,
                'signalCategory' => $signalCategory,
                'submitLabel' => 'Simpan Perubahan',
                'cancelUrl' => route('signal.index', $signalCategory),
            ])
        </form>
    </section>
@endsection
