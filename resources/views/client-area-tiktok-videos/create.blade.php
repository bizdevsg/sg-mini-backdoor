@extends('layouts.app')

@section('title', 'Tambah Video TikTok')

@section('content')
    <section class="space-y-6">
        <div class="rounded-[28px] border border-black/8 bg-black/3 px-7 py-6 lg:px-9 lg:py-8">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <span class="text-[10px] font-semibold uppercase tracking-[0.24em] text-gold-soft">Video Baru</span>
                    <h1 class="mt-2 text-2xl font-semibold tracking-[-0.04em] text-ivory lg:text-3xl">Tambah Video TikTok</h1>
                    <p class="mt-2 text-sm text-smoke">Tambahkan URL video untuk ditampilkan sebagai embed pada client area.</p>
                </div>
                <a href="{{ route('tiktok-videos.index') }}"
                    class="inline-flex w-fit items-center gap-2 rounded-xl border border-black/10 bg-black/5 px-4 py-2.5 text-sm text-smoke hover:text-ivory">
                    <i class="fa-solid fa-arrow-left text-xs"></i> Kembali
                </a>
            </div>
        </div>

        <form action="{{ route('tiktok-videos.store') }}" method="POST" class="space-y-6">
            @csrf
            @include('client-area-tiktok-videos.partials.form', [
                'confirmTitle' => 'Simpan video TikTok baru?',
                'confirmMessage' => 'Pastikan URL, judul, dan status tampil video sudah benar.',
                'confirmActionLabel' => 'Ya, simpan',
                'submitLabel' => 'Simpan Video',
            ])
        </form>
    </section>
@endsection
