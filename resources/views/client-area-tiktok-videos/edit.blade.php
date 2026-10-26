@extends('layouts.app')

@section('title', 'Edit Video TikTok')

@section('content')
    <section class="space-y-6">
        <div class="rounded-[28px] border border-black/8 bg-black/3 px-7 py-6 lg:px-9 lg:py-8">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <span class="text-[10px] font-semibold uppercase tracking-[0.24em] text-gold-soft">Update Video</span>
                    <h1 class="mt-2 text-2xl font-semibold tracking-[-0.04em] text-ivory lg:text-3xl">Edit: {{ $video->title }}</h1>
                    <p class="mt-2 text-sm text-smoke">Perbarui URL, judul, atau status tampil video TikTok.</p>
                </div>
                <a href="{{ route('tiktok-videos.index') }}"
                    class="inline-flex w-fit items-center gap-2 rounded-xl border border-black/10 bg-black/5 px-4 py-2.5 text-sm text-smoke hover:text-ivory">
                    <i class="fa-solid fa-arrow-left text-xs"></i> Kembali
                </a>
            </div>
        </div>

        <form action="{{ route('tiktok-videos.update', $video) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            @include('client-area-tiktok-videos.partials.form', [
                'confirmTitle' => 'Simpan perubahan video ini?',
                'confirmMessage' => 'Perubahan URL, judul, dan status tampil akan langsung disimpan.',
                'confirmActionLabel' => 'Ya, update',
                'submitLabel' => 'Update Video',
            ])
        </form>
    </section>
@endsection
