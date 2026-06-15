@extends('layouts.app')

@section('title', 'Produk ' . $sectionLabel)

@section('content')
    <section class="space-y-6">
        <div
            class="flex flex-col gap-4 rounded-2xl border border-white/8 bg-white/4 p-6 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-xs font-medium uppercase tracking-[0.18em] text-smoke">Catalog management</p>
                <h2 class="mt-2 text-3xl font-semibold tracking-[-0.04em] text-white">Produk {{ $sectionLabel }}</h2>
            </div>

            <a href="{{ route('produk.create', ['section' => $section]) }}"
                class="inline-flex items-center justify-center gap-2 rounded-lg bg-white px-5 py-3 text-sm font-medium text-obsidian transition-colors hover:bg-slate-200">
                <i class="fa-solid fa-plus text-xs"></i>
                Tambah Produk
            </a>
        </div>

        @if (session('status'))
            <div class="rounded-xl border border-gold/20 bg-gold/10 px-4 py-3 text-sm text-gold-soft">
                {{ session('status') }}
            </div>
        @endif

        <div class="rounded-2xl border border-white/8 bg-white/4 p-6">
            <form action="{{ route('produk.index', ['section' => $section]) }}" method="GET"
                class="grid gap-4 lg:grid-cols-[1fr_180px]">
                <label class="block">
                    <span class="mb-2 block text-sm font-medium text-white">Cari produk</span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama produk atau slug"
                        class="w-full rounded-lg border border-white/8 bg-onyx px-4 py-3 text-sm text-champagne placeholder:text-smoke focus:border-gold/30 focus:outline-none focus:ring-2 focus:ring-gold/15">
                </label>

                <div class="flex items-end gap-3">
                    <button type="submit"
                        class="inline-flex flex-1 items-center justify-center rounded-lg border border-white/8 bg-white/6 px-4 py-3 text-sm font-medium text-white transition-colors hover:bg-white/10">
                        Filter
                    </button>
                    <a href="{{ route('produk.index', ['section' => $section]) }}"
                        class="inline-flex items-center justify-center rounded-lg border border-white/8 bg-transparent px-4 py-3 text-sm font-medium text-smoke transition-colors hover:border-white/12 hover:text-white">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <div class="overflow-hidden rounded-2xl border border-white/8 bg-white/4">
            @if ($produks->isEmpty())
                <div class="px-6 py-16 text-center">
                    <div
                        class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl border border-white/8 bg-white/5 text-gold-soft">
                        <i class="fa-solid fa-box-open text-xl"></i>
                    </div>
                    <h3 class="mt-5 text-2xl font-semibold text-white">Belum ada produk {{ strtolower($sectionLabel) }}</h3>
                    <p class="mt-2 text-sm text-smoke">Mulai dengan menambahkan produk pertama ke katalog.</p>
                    <a href="{{ route('produk.create', ['section' => $section]) }}"
                        class="mt-6 inline-flex items-center justify-center rounded-lg bg-white px-5 py-3 text-sm font-medium text-obsidian transition-colors hover:bg-slate-200">
                        Tambah Produk
                    </a>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-white/8">
                        <thead class="bg-noir/70">
                            <tr class="text-left text-xs uppercase tracking-[0.18em] text-smoke">
                                <th class="px-6 py-4 font-medium">Produk</th>
                                <th class="px-6 py-4 font-medium">Kategori</th>
                                <th class="px-6 py-4 font-medium">Created At</th>
                                <th class="px-6 py-4 font-medium">Updated At</th>
                                <th class="px-6 py-4 text-right font-medium">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/6">
                            @foreach ($produks as $produk)
                                <tr class="align-top transition-colors hover:bg-white/4">
                                    <td class="px-6 py-5">
                                        <p class="font-medium text-white">{{ $produk->nama_produk }}</p>
                                        <p class="mt-1 line-clamp-2 max-w-md text-sm text-smoke">
                                            {{ $produk->deskripsi_produk }}
                                        </p>
                                    </td>
                                    <td class="px-6 py-5">
                                        <span
                                            class="inline-flex rounded-full border border-gold/20 bg-gold/10 px-3 py-1 text-xs font-medium text-gold-soft">
                                            {{ $produk->kategori }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-5 text-sm text-smoke">
                                        {{ $produk->created_at?->format('d M Y, H:i') ?? '-' }}
                                    </td>
                                    <td class="px-6 py-5 text-sm text-smoke">
                                        {{ $produk->updated_at?->format('d M Y, H:i') ?? '-' }}
                                    </td>
                                    <td class="px-6 py-5">
                                        <div class="flex justify-end gap-2">
                                            <a href="{{ route('produk.show', ['produk' => $produk, 'section' => $section]) }}"
                                                class="inline-flex items-center rounded-lg border border-white/8 bg-white/5 px-3 py-2 text-sm font-medium text-white transition-colors hover:bg-white/10">
                                                Detail
                                            </a>
                                            <a href="{{ route('produk.edit', ['produk' => $produk, 'section' => $section]) }}"
                                                class="inline-flex items-center rounded-lg border border-white/8 bg-white/5 px-3 py-2 text-sm font-medium text-white transition-colors hover:bg-white/10">
                                                Edit
                                            </a>
                                            <form action="{{ route('produk.destroy', ['produk' => $produk, 'section' => $section]) }}"
                                                method="POST"
                                                onsubmit="return confirm('Hapus produk ini? Tindakan ini tidak bisa dibatalkan.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="inline-flex items-center rounded-lg border border-red-400/30 bg-red-500/10 px-3 py-2 text-sm font-medium text-red-200 transition-colors hover:bg-red-500/20">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="border-t border-white/8 px-6 py-4">
                    {{ $produks->links() }}
                </div>
            @endif
        </div>
    </section>
@endsection
