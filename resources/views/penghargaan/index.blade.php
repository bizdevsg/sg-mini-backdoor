@extends('layouts.app')

@section('title', 'Penghargaan')

@section('content')
    <section class="space-y-6">
        @include('components.molecules.page-header', [
            'eyebrow' => 'Recognition management',
            'title' => 'Penghargaan',
            'description' => 'Kelola daftar penghargaan dalam bentuk card yang ringkas dan mudah dibaca.',
            'action' => [
                'href' => route('penghargaan.create'),
                'label' => 'Tambah Penghargaan',
                'icon' => 'fa-solid fa-plus text-xs',
                'class' => 'inline-flex items-center justify-center gap-2 rounded-lg bg-white px-5 py-3 text-sm font-medium text-obsidian transition-colors hover:bg-slate-200',
            ],
        ])

        <div class="rounded-2xl border border-white/8 bg-white/4 p-6">
            <form action="{{ route('penghargaan.index') }}" method="GET" class="grid gap-4 lg:grid-cols-[1fr_180px]">
                <label class="block">
                    <span class="mb-2 block text-sm font-medium text-white">Cari penghargaan</span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Judul atau slug"
                        class="w-full rounded-lg border border-white/8 bg-onyx px-4 py-3 text-sm text-champagne placeholder:text-smoke focus:border-gold/30 focus:outline-none focus:ring-2 focus:ring-gold/15">
                </label>

                <div class="flex items-end gap-3">
                    <button type="submit"
                        class="inline-flex flex-1 items-center justify-center rounded-lg border border-white/8 bg-white/6 px-4 py-3 text-sm font-medium text-white transition-colors hover:bg-white/10">
                        Filter
                    </button>
                    <a href="{{ route('penghargaan.index') }}"
                        class="inline-flex items-center justify-center rounded-lg border border-white/8 bg-transparent px-4 py-3 text-sm font-medium text-smoke transition-colors hover:border-white/12 hover:text-white">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        @if ($penghargaans->isEmpty())
            <div class="rounded-2xl border border-white/8 bg-white/4 px-6 py-16 text-center">
                <div
                    class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl border border-white/8 bg-white/5 text-gold-soft">
                    <i class="fa-solid fa-trophy text-xl"></i>
                </div>
                <h3 class="mt-5 text-2xl font-semibold text-white">Belum ada penghargaan</h3>
                <p class="mt-2 text-sm text-smoke">Mulai dengan menambahkan data penghargaan pertama.</p>
                <a href="{{ route('penghargaan.create') }}"
                    class="mt-6 inline-flex items-center justify-center rounded-lg bg-white px-5 py-3 text-sm font-medium text-obsidian transition-colors hover:bg-slate-200">
                    Tambah Penghargaan
                </a>
            </div>
        @else
            <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
                @foreach ($penghargaans as $penghargaan)
                    <article class="rounded-2xl border border-white/8 bg-white/4 p-6 transition-colors hover:bg-white/6">
                        <div class="overflow-hidden rounded-2xl border border-white/8 bg-onyx">
                            @if ($penghargaan->image_url)
                                <img src="{{ $penghargaan->image_url }}" alt="{{ $penghargaan->title }}"
                                    class="h-48 w-full object-cover">
                            @else
                                <div class="flex h-48 items-center justify-center text-sm text-smoke">
                                    Image tidak tersedia
                                </div>
                            @endif
                        </div>

                        <div class="mt-5 flex items-center justify-between gap-3">
                            <p class="text-xs font-medium uppercase tracking-[0.18em] text-smoke">Penghargaan</p>
                            <span class="rounded-full border border-gold/20 bg-gold/10 px-3 py-1 text-xs font-medium text-gold-soft">
                                {{ $penghargaan->created_at?->format('Y') ?? '-' }}
                            </span>
                        </div>

                        <div class="mt-3">
                            <h3 class="mt-2 text-xl font-semibold text-white">{{ $penghargaan->title }}</h3>
                            <p class="mt-3 text-sm leading-7 text-smoke">
                                {{ \Illuminate\Support\Str::limit(strip_tags($penghargaan->subtitle), 140) }}
                            </p>
                        </div>

                        <div class="mt-6 flex flex-wrap gap-2">
                            <a href="{{ route('penghargaan.edit', $penghargaan) }}"
                                class="inline-flex items-center rounded-lg border border-white/8 bg-white/5 px-3 py-2 text-sm font-medium text-white transition-colors hover:bg-white/10">
                                Edit
                            </a>
                            <form action="{{ route('penghargaan.destroy', $penghargaan) }}" method="POST"
                                onsubmit="return confirm('Hapus penghargaan ini? Tindakan ini tidak bisa dibatalkan.');">
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
                {{ $penghargaans->links() }}
            </div>
        @endif
    </section>
@endsection
