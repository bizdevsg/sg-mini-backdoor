@extends('layouts.app')

@section('title', $signal->title_id)

@section('content')
    <section class="space-y-6">
        <div class="rounded-[28px] border border-white/8 bg-white/3 px-7 py-6 shadow-[0_24px_60px_rgba(0,0,0,0.3)]">
            <div class="space-y-2">
                <div class="flex items-center gap-2 text-xs text-smoke/60">
                    <a href="{{ route('signal-categories.index') }}" class="hover:text-smoke">Kategori Signal</a>
                    <i class="fa-solid fa-chevron-right text-[8px]"></i>
                    <a href="{{ route('signal.index', $signalCategory) }}" class="hover:text-smoke">{{ $signalCategory->name }}</a>
                </div>
                <h1 class="text-2xl font-semibold text-white lg:text-3xl">{{ $signal->title_id }}</h1>
                <p class="text-sm text-blue-200/80">{{ $signal->title_en }}</p>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-[1fr_320px]">
            <div class="space-y-6">
                <article class="rounded-2xl border border-white/8 bg-white/3 p-6">
                    <h2 class="mb-4 text-sm font-semibold uppercase tracking-[0.16em] text-smoke/70">Konten ID</h2>
                    <div class="prose prose-invert max-w-none prose-p:text-sm prose-p:leading-7">
                        {!! $signal->content_for_display !!}
                    </div>
                </article>

                <article class="rounded-2xl border border-white/8 bg-white/3 p-6">
                    <h2 class="mb-4 text-sm font-semibold uppercase tracking-[0.16em] text-smoke/70">Konten EN</h2>
                    <div class="prose prose-invert max-w-none prose-p:text-sm prose-p:leading-7">
                        {!! $signal->content_en_for_display !!}
                    </div>
                </article>
            </div>

            <aside class="space-y-6">
                <div class="rounded-2xl border border-white/8 bg-white/3 p-5">
                    <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-smoke/70">Informasi Signal</p>
                    <div class="mt-4 space-y-3 text-sm text-smoke">
                        <div>
                            <p class="text-xs uppercase tracking-[0.16em] text-smoke/60">Kategori</p>
                            <p class="mt-1 font-medium text-white">{{ $signalCategory->name }}</p>
                        </div>
                        <div>
                            <p class="text-xs uppercase tracking-[0.16em] text-smoke/60">Author</p>
                            <p class="mt-1 font-medium text-white">{{ $signal->author }}</p>
                        </div>
                        <div>
                            <p class="text-xs uppercase tracking-[0.16em] text-smoke/60">Source</p>
                            <p class="mt-1 font-medium text-white">{{ $signal->source }}</p>
                        </div>
                        <div>
                            <p class="text-xs uppercase tracking-[0.16em] text-smoke/60">Slug</p>
                            <p class="mt-1 font-mono text-champagne/80">{{ $signal->slug }}</p>
                        </div>
                    </div>
                </div>

                @if ($signal->image_url)
                    <div class="rounded-2xl border border-white/8 bg-white/3 p-5 space-y-3">
                        <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-smoke/70">Gambar</p>
                        <div class="overflow-hidden rounded-xl border border-white/8">
                            <img src="{{ $signal->image_url }}" alt="{{ $signal->title_id }}" class="w-full object-cover">
                        </div>
                    </div>
                @endif
            </aside>
        </div>
    </section>
@endsection
