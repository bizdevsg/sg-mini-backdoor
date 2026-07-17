@extends('layouts.app')

@section('title', 'Ebook')

@section('content')
    <section class="space-y-6">
        @include('components.molecules.page-header', [
            'eyebrow' => 'Ebook management',
            'title' => 'Ebook ' . $ebookCategory->name,
            'description' => 'Kelola katalog ebook dalam kategori ' . $ebookCategory->name . '.',
            'action' => [
                'href' => route('ebook.create', $ebookCategory),
                'label' => 'Tambah Ebook',
                'icon' => 'fa-solid fa-plus text-xs',
                'class' => 'inline-flex items-center justify-center gap-2 rounded-lg bg-white px-5 py-3 text-sm font-medium text-obsidian transition-colors hover:bg-slate-200',
            ],
        ])

        <div class="rounded-2xl border border-white/8 bg-white/4 p-6">
            <form action="{{ route('ebook.index', $ebookCategory) }}" method="GET" class="grid gap-4 lg:grid-cols-[1fr_180px]">
                <label class="block">
                    <span class="mb-2 block text-sm font-medium text-white">Cari ebook</span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Judul, kategori, atau slug"
                        class="w-full rounded-lg border border-white/8 bg-onyx px-4 py-3 text-sm text-champagne placeholder:text-smoke focus:border-gold/30 focus:outline-none focus:ring-2 focus:ring-gold/15">
                </label>

                <div class="flex items-end gap-3">
                    <button type="submit"
                        class="inline-flex flex-1 items-center justify-center rounded-lg border border-white/8 bg-white/6 px-4 py-3 text-sm font-medium text-white transition-colors hover:bg-white/10">
                        Filter
                    </button>
                    <a href="{{ route('ebook.index', $ebookCategory) }}"
                        class="inline-flex items-center justify-center rounded-lg border border-white/8 bg-transparent px-4 py-3 text-sm font-medium text-smoke transition-colors hover:border-white/12 hover:text-white">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <div class="overflow-hidden rounded-2xl border border-white/8 bg-white/4">
            @if ($ebooks->isEmpty())
                <div class="px-6 py-16 text-center">
                    <div
                        class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl border border-white/8 bg-white/5 text-gold-soft">
                        <i class="fa-solid fa-book-open text-xl"></i>
                    </div>
                    <h3 class="mt-5 text-2xl font-semibold text-white">Belum ada ebook</h3>
                    <p class="mt-2 text-sm text-smoke">Mulai dengan menambahkan ebook pertama untuk kategori {{ $ebookCategory->name }}.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-white/8">
                        <thead class="bg-noir/70">
                            <tr class="text-left text-xs uppercase tracking-[0.18em] text-smoke">
                                <th class="px-6 py-4 font-medium">Judul</th>
                                <th class="px-6 py-4 font-medium">Kategori</th>
                                <th class="px-6 py-4 font-medium">Slug</th>
                                <th class="px-6 py-4 font-medium">File</th>
                                <th class="px-6 py-4 text-right font-medium">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/6">
                            @foreach ($ebooks as $ebook)
                                <tr class="align-top transition-colors hover:bg-white/4">
                                    <td class="px-6 py-5">
                                        <p class="font-medium text-white">{{ $ebook->title }}</p>
                                        <p class="mt-1 line-clamp-2 max-w-xl text-sm text-smoke">
                                            {{ \Illuminate\Support\Str::limit(strip_tags($ebook->description), 140) }}
                                        </p>
                                    </td>
                                    <td class="px-6 py-5">
                                        <span
                                            class="rounded-full border border-gold/20 bg-gold/10 px-3 py-1 text-xs font-medium text-gold-soft">
                                            {{ $ebook->kategori }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-5">
                                        <span
                                            class="rounded-lg border border-white/8 bg-black/10 px-3 py-2 font-mono text-xs text-champagne">
                                            {{ $ebook->slug }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-5 text-sm text-smoke">
                                        @if ($ebook->file_url)
                                            <a href="{{ $ebook->file_url }}" target="_blank" rel="noreferrer"
                                                class="inline-flex items-center gap-2 rounded-lg border border-white/8 bg-white/5 px-3 py-2 text-white transition-colors hover:bg-white/10">
                                                <i class="fa-solid fa-download text-xs"></i>
                                                Download
                                            </a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-6 py-5">
                                        <div class="flex justify-end gap-2">
                                            <a href="{{ route('ebook.show', ['ebookCategory' => $ebookCategory->slug, 'ebook' => $ebook->slug]) }}"
                                                class="inline-flex items-center rounded-lg border border-white/8 bg-white/5 px-3 py-2 text-sm font-medium text-white transition-colors hover:bg-white/10">
                                                Detail
                                            </a>
                                            <a href="{{ route('ebook.edit', ['ebookCategory' => $ebookCategory->slug, 'ebook' => $ebook->slug]) }}"
                                                class="inline-flex items-center rounded-lg border border-white/8 bg-white/5 px-3 py-2 text-sm font-medium text-white transition-colors hover:bg-white/10">
                                                Edit
                                            </a>
                                            <form action="{{ route('ebook.destroy', ['ebookCategory' => $ebookCategory->slug, 'ebook' => $ebook->slug]) }}" method="POST"
                                                onsubmit="return confirm('Hapus ebook ini? Tindakan ini tidak bisa dibatalkan.');">
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
                    {{ $ebooks->links() }}
                </div>
            @endif
        </div>
    </section>
@endsection
