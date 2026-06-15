@extends('layouts.app')

@section('title', 'Edit Penghargaan')

@section('content')
    <section class="space-y-6">
        <div class="rounded-2xl border border-white/8 bg-white/4 p-6">
            <p class="text-xs font-medium uppercase tracking-[0.18em] text-smoke">Update penghargaan</p>
            <h2 class="mt-2 text-3xl font-semibold tracking-[-0.04em] text-white">{{ $penghargaan->title }}</h2>
        </div>

        <form action="{{ route('penghargaan.update', $penghargaan) }}" method="POST" enctype="multipart/form-data"
            class="space-y-6">
            @csrf
            @method('PUT')
            @include('penghargaan.partials.form', [
                'penghargaan' => $penghargaan,
                'submitLabel' => 'Update Penghargaan',
                'cancelUrl' => route('penghargaan.index'),
            ])
        </form>
    </section>
@endsection
