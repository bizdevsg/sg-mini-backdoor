@extends('layouts.app')

@section('title', $berita->title_id)

@section('content')
    <section class="space-y-6">
        <div class="rounded-[28px] border border-white/8 bg-white/3 px-7 py-6 shadow-[0_24px_60px_rgba(0,0,0,0.3)]">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div class="space-y-2">
                    <div class="flex items-center gap-2 text-xs text-smoke/60">
                        <a href="{{ route('berita-categories.index') }}" class="hover:text-smoke">Kategori Berita</a>
                        <i class="fa-solid fa-chevron-right text-[8px]"></i>
                        <a href="{{ route('berita.index', $beritaCategory) }}" class="hover:text-smoke">{{ $beritaCategory->name }}</a>
                    </div>
                    <h1 class="text-2xl font-semibold text-white lg:text-3xl">{{ $berita->title_id }}</h1>
                    <p class="text-sm text-gold-soft/80">{{ $berita->title_en }}</p>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('berita.index', $beritaCategory) }}"
                        class="inline-flex items-center gap-2 rounded-xl border border-white/10 bg-white/5 px-4 py-2.5 text-sm font-medium text-smoke hover:border-white/18 hover:bg-white/8 hover:text-white">
                        Kembali
                    </a>
                    <a href="{{ route('berita.edit', ['beritaCategory' => $beritaCategory, 'berita' => $berita]) }}"
                        class="inline-flex items-center gap-2 rounded-xl bg-gold px-5 py-2.5 text-sm font-semibold text-obsidian hover:bg-gold-soft">
                        <i class="fa-solid fa-pen text-xs"></i>
                        Edit
                    </a>
                </div>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-[1fr_320px]">
            <div class="space-y-6">
                <article class="rounded-2xl border border-white/8 bg-white/3 p-6">
                    <h2 class="mb-4 text-sm font-semibold uppercase tracking-[0.16em] text-smoke/70">Konten ID</h2>
                    <div class="prose prose-invert max-w-none prose-p:text-sm prose-headings:text-white prose-strong:text-white">
                        {!! $berita->content_for_display !!}
                    </div>
                </article>

                <article class="rounded-2xl border border-white/8 bg-white/3 p-6">
                    <h2 class="mb-4 text-sm font-semibold uppercase tracking-[0.16em] text-smoke/70">Konten EN</h2>
                    <div class="prose prose-invert max-w-none prose-p:text-sm prose-headings:text-white prose-strong:text-white">
                        {!! $berita->content_en_for_display !!}
                    </div>
                </article>
            </div>

            <div class="space-y-6">
                <div class="rounded-2xl border border-white/8 bg-white/3 p-5 space-y-4">
                    <div>
                        <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-smoke/70">Informasi Berita</p>
                    </div>
                    <div class="space-y-3 text-sm text-smoke">
                        <div>
                            <p class="text-xs uppercase tracking-[0.16em] text-smoke/60">Kategori</p>
                            <p class="mt-1 font-medium text-white">{{ $beritaCategory->name }}</p>
                        </div>
                        <div>
                            <p class="text-xs uppercase tracking-[0.16em] text-smoke/60">Author</p>
                            <p class="mt-1 font-medium text-white">{{ $berita->author }}</p>
                        </div>
                        <div>
                            <p class="text-xs uppercase tracking-[0.16em] text-smoke/60">Source</p>
                            <p class="mt-1 font-medium text-white">{{ $berita->source }}</p>
                        </div>
                        <div>
                            <p class="text-xs uppercase tracking-[0.16em] text-smoke/60">Slug</p>
                            <p class="mt-1 font-mono text-champagne/80">{{ $berita->slug }}</p>
                        </div>
                        <div>
                            <p class="text-xs uppercase tracking-[0.16em] text-smoke/60">Dibuat</p>
                            <p class="mt-1 font-medium text-white">{{ $berita->created_at?->format('d M Y H:i') ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                @if ($berita->image_url)
                    <div class="rounded-2xl border border-white/8 bg-white/3 p-5 space-y-3">
                        <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-smoke/70">Gambar Sampul</p>
                        <div class="overflow-hidden rounded-xl border border-white/8">
                            <img src="{{ $berita->image_url }}" alt="{{ $berita->title_id }}" class="w-full object-cover">
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection
