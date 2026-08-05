@extends('layouts.app')

@section('title', 'Edit Kategori Signal')

@section('content')
    <section class="space-y-6">
        <div class="rounded-[28px] border border-black/8 bg-black/3 px-7 py-6  ">
            <h1 class="text-2xl font-semibold text-ivory">Edit Kategori Signal</h1>
            <p class="mt-2 text-sm text-smoke">Perbarui kategori <span
                    class="text-blue-700">{{ $signalCategory->name }}</span>.</p>
        </div>

        <form action="{{ route('signal-categories.update', $signalCategory) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            @include('signal-categories.partials.form', [
                'signalCategory' => $signalCategory,
                'confirmTitle' => 'Simpan perubahan kategori ini?',
                'confirmMessage' => 'Perubahan nama kategori akan langsung diterapkan.',
                'confirmActionLabel' => 'Ya, update',
                'submitLabel' => 'Simpan Perubahan',
                'cancelUrl' => route('signal-categories.index'),
            ])
        </form>
    </section>
@endsection
