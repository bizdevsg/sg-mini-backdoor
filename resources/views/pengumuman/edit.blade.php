@extends('layouts.app')

@section('title', 'Edit Pengumuman')

@section('content')
    <section class="space-y-6">
        <div class="rounded-2xl border border-white/8 bg-white/4 p-6">
            <p class="text-xs font-medium uppercase tracking-[0.18em] text-smoke">Update pengumuman</p>
            <h2 class="mt-2 text-3xl font-semibold tracking-[-0.04em] text-white">{{ $informasi->title }}</h2>
        </div>

        <form action="{{ route('pengumuman.update', $informasi) }}" method="POST" enctype="multipart/form-data"
            class="space-y-6">
            @csrf
            @method('PUT')
            @include('pengumuman.partials.form', [
                'informasi' => $informasi,
                'submitLabel' => 'Update Pengumuman',
                'cancelUrl' => route('pengumuman.show', $informasi),
            ])
        </form>
    </section>
@endsection
