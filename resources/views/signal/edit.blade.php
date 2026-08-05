@extends('layouts.app')

@section('title', 'Edit Signal')

@section('content')
    <section class="space-y-6">
        <div class="rounded-[28px] border border-black/8 bg-black/3 px-7 py-6  ">
            <h1 class="text-2xl font-semibold text-ivory">Edit Signal</h1>
            <p class="mt-2 text-sm text-smoke">Perbarui signal <span class="text-blue-700">{{ $signal->title_id }}</span>.</p>
        </div>

        <form action="{{ route('signal.update', ['signalCategory' => $signalCategory, 'signal' => $signal]) }}" method="POST"
            enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')
            @include('signal.partials.form', [
                'signal' => $signal,
                'signalCategory' => $signalCategory,
                'confirmTitle' => 'Simpan perubahan signal ini?',
                'confirmMessage' => 'Perubahan akan langsung diterapkan setelah disimpan.',
                'confirmActionLabel' => 'Ya, update',
                'submitLabel' => 'Simpan Perubahan',
                'cancelUrl' => route('signal.index', $signalCategory),
            ])
        </form>
    </section>
@endsection
