@extends('layouts.app')

@section('title', 'Banner')

@section('content')
    <section class="space-y-6">
        @include('components.molecules.page-header', [
            'eyebrow' => 'Banner management',
            'title' => 'Banner',
            'description' => 'Kelola image carousel, syarat dan ketentuan, urutan tampil slide, dan status aktif untuk banner website.',
            'action' => [
                'href' => route('banner.create'),
                'label' => 'Tambah Banner',
                'icon' => 'fa-solid fa-plus text-xs',
                'class' => 'inline-flex items-center justify-center gap-2 rounded-lg bg-white px-5 py-3 text-sm font-medium text-obsidian transition-colors hover:bg-slate-200',
            ],
        ])

        <div class="grid gap-4 md:grid-cols-3">
            <div class="rounded-2xl border border-white/8 bg-white/4 p-5">
                <p class="text-xs font-medium uppercase tracking-[0.18em] text-smoke">Total Banner</p>
                <p class="mt-3 text-3xl font-semibold text-white">{{ $banners->total() }}</p>
            </div>
            <div class="rounded-2xl border border-white/8 bg-white/4 p-5">
                <p class="text-xs font-medium uppercase tracking-[0.18em] text-smoke">Aktif</p>
                <p class="mt-3 text-3xl font-semibold text-white">{{ $activeCount }}</p>
            </div>
            <div class="rounded-2xl border border-white/8 bg-white/4 p-5">
                <p class="text-xs font-medium uppercase tracking-[0.18em] text-smoke">Nonaktif</p>
                <p class="mt-3 text-3xl font-semibold text-white">{{ $inactiveCount }}</p>
            </div>
        </div>

        @if ($banners->isEmpty())
            <div class="rounded-2xl border border-white/8 bg-white/4 px-6 py-16 text-center">
                <div
                    class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl border border-white/8 bg-white/5 text-gold-soft">
                    <i class="fa-solid fa-image text-xl"></i>
                </div>
                <h3 class="mt-5 text-2xl font-semibold text-white">Belum ada banner</h3>
                <p class="mt-2 text-sm text-smoke">Mulai dengan menambahkan image pertama untuk carousel website.</p>
                <a href="{{ route('banner.create') }}"
                    class="mt-6 inline-flex items-center justify-center rounded-lg bg-white px-5 py-3 text-sm font-medium text-obsidian transition-colors hover:bg-slate-200">
                    Tambah Banner
                </a>
            </div>
        @else
            <div class="grid gap-6 xl:grid-cols-2">
                @foreach ($banners as $banner)
                    <article class="rounded-2xl border border-white/8 bg-white/4 p-6 transition-colors hover:bg-white/6">
                        <div class="overflow-hidden rounded-2xl border border-white/8 bg-onyx">
                            @if ($banner->image_url)
                                <img src="{{ $banner->image_url }}" alt="{{ $banner->title }}" class="h-56 w-full object-cover">
                            @else
                                <div class="flex h-56 items-center justify-center text-sm text-smoke">
                                    Image tidak tersedia
                                </div>
                            @endif
                        </div>

                        <div class="mt-5">
                            <h3 class="text-lg font-semibold text-white">{{ $banner->title }}</h3>
                            <div class="mt-3 flex flex-wrap items-center gap-2">
                                <span class="inline-flex rounded-full border border-white/8 bg-black/10 px-3 py-1 font-mono text-xs text-champagne">
                                    {{ $banner->slug }}
                                </span>
                                <span
                                    class="inline-flex rounded-full border px-3 py-1 text-xs font-medium {{ $banner->is_active ? 'border-emerald-500/20 bg-emerald-500/10 text-emerald-100' : 'border-white/10 bg-white/5 text-smoke' }}">
                                    {{ $banner->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                                <span class="inline-flex rounded-full border border-gold/20 bg-gold/10 px-3 py-1 text-xs font-medium text-gold-soft">
                                    Urutan {{ $banner->sort_order }}
                                </span>
                                <span class="inline-flex rounded-full border border-white/8 bg-black/10 px-3 py-1 font-mono text-xs text-champagne">
                                    ID {{ $banner->id }}
                                </span>
                            </div>
                        </div>

                        <p class="mt-4 break-all rounded-2xl border border-white/8 bg-black/10 px-4 py-3 text-xs text-smoke">
                            {{ $banner->image }}
                        </p>

                        <div class="mt-4 rounded-2xl border border-white/8 bg-black/10 px-4 py-4">
                            <p class="text-xs font-medium uppercase tracking-[0.18em] text-smoke">Syarat dan Ketentuan</p>
                            @if (filled($banner->terms_and_conditions))
                                <p class="mt-3 text-sm leading-7 text-champagne/90">
                                    {{ \Illuminate\Support\Str::limit(trim(strip_tags($banner->terms_and_conditions)), 220) }}
                                </p>
                            @else
                                <p class="mt-3 text-sm text-smoke">Belum ada syarat dan ketentuan.</p>
                            @endif
                        </div>

                        <div class="mt-6 flex flex-wrap gap-2">
                            <a href="{{ route('banner.edit', $banner) }}"
                                class="inline-flex items-center rounded-lg border border-white/8 bg-white/5 px-3 py-2 text-sm font-medium text-white transition-colors hover:bg-white/10">
                                Edit
                            </a>
                            <form action="{{ route('banner.destroy', $banner) }}" method="POST"
                                onsubmit="return confirm('Hapus banner ini? Tindakan ini tidak bisa dibatalkan.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="inline-flex items-center rounded-lg border border-red-400/30 bg-red-500/10 px-3 py-2 text-sm font-medium text-red-200 transition-colors hover:bg-red-500/20">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="rounded-2xl border border-white/8 bg-white/4 px-6 py-4">
                {{ $banners->links() }}
            </div>
        @endif
    </section>
@endsection
