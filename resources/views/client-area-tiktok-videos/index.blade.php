@extends('layouts.app')

@section('title', 'Video TikTok')

@section('content')
    <section class="space-y-6">
        <div class="rounded-[28px] border border-black/8 bg-black/3 px-7 py-6 lg:px-9 lg:py-8">
            <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <span class="text-[10px] font-semibold uppercase tracking-[0.24em] text-gold-soft">Client Area</span>
                    <h1 class="mt-2 text-2xl font-semibold tracking-[-0.04em] text-ivory lg:text-3xl">Video TikTok</h1>
                    <p class="mt-2 max-w-2xl text-sm leading-6 text-smoke">Kelola video TikTok yang dapat ditampilkan sebagai embed pada client area.</p>
                </div>
                <a href="{{ route('tiktok-videos.create') }}"
                    class="inline-flex w-fit items-center gap-2 rounded-xl bg-gold px-5 py-2.5 text-sm font-semibold text-obsidian shadow-[0_4px_18px_rgba(199,161,90,0.28)] transition hover:bg-gold-soft">
                    <i class="fa-solid fa-plus text-xs"></i> Tambah Video
                </a>
            </div>
        </div>

        <div class="grid gap-4 sm:grid-cols-3">
            <div class="rounded-2xl border border-black/8 bg-black/3 p-5">
                <p class="text-xs text-smoke">Total Video</p>
                <p class="mt-2 text-2xl font-semibold text-ivory">{{ $videos->total() }}</p>
            </div>
            <div class="rounded-2xl border border-emerald-500/20 bg-emerald-500/5 p-5">
                <p class="text-xs text-smoke">Aktif</p>
                <p class="mt-2 text-2xl font-semibold text-emerald-700">{{ $activeCount }}</p>
            </div>
            <div class="rounded-2xl border border-black/8 bg-black/3 p-5">
                <p class="text-xs text-smoke">Nonaktif</p>
                <p class="mt-2 text-2xl font-semibold text-ivory">{{ $inactiveCount }}</p>
            </div>
        </div>

        <form action="{{ route('tiktok-videos.index') }}" method="GET" class="flex gap-3 rounded-2xl border border-black/8 bg-black/3 p-4">
            <div class="relative flex-1">
                <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-xs text-smoke/60"></i>
                <input type="search" name="search" value="{{ request('search') }}"
                    placeholder="Cari judul, video ID, atau URL..."
                    class="w-full rounded-xl border border-black/8 bg-onyx py-2.5 pl-10 pr-4 text-sm text-champagne placeholder:text-smoke/40 focus:border-gold/35 focus:outline-none focus:ring-2 focus:ring-gold/12">
            </div>
            <button type="submit" class="rounded-xl border border-black/10 bg-black/5 px-5 py-2.5 text-sm font-medium text-ivory hover:bg-black/8">Cari</button>
            @if (request()->filled('search'))
                <a href="{{ route('tiktok-videos.index') }}" class="rounded-xl px-4 py-2.5 text-sm text-smoke hover:text-ivory">Reset</a>
            @endif
        </form>

        <div class="overflow-hidden rounded-2xl border border-black/8 bg-black/3">
            @if ($videos->isEmpty())
                <div class="flex min-h-72 flex-col items-center justify-center px-6 text-center">
                    <i class="fa-brands fa-tiktok text-4xl text-smoke/40"></i>
                    <h2 class="mt-5 text-lg font-semibold text-ivory">Belum ada video TikTok</h2>
                    <p class="mt-2 text-sm text-smoke">Tambahkan URL video TikTok pertama untuk mulai menampilkan embed.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[850px] text-left">
                        <thead class="border-b border-black/8 bg-black/4 text-[10px] uppercase tracking-[0.16em] text-smoke/70">
                            <tr>
                                <th class="px-6 py-4 font-semibold">Video</th>
                                <th class="px-6 py-4 font-semibold">Video ID</th>
                                <th class="px-6 py-4 font-semibold">Status</th>
                                <th class="px-6 py-4 text-right font-semibold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-black/6">
                            @foreach ($videos as $video)
                                <tr class="transition hover:bg-black/3">
                                    <td class="px-6 py-4">
                                        <p class="font-medium text-ivory">{{ $video->title }}</p>
                                        <a href="{{ $video->video_url }}" target="_blank" rel="noopener noreferrer"
                                            class="mt-1 block max-w-md truncate text-xs text-gold-soft hover:underline">
                                            {{ $video->video_url }}
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 font-mono text-xs text-smoke">{{ $video->video_id }}</td>
                                    <td class="px-6 py-4">
                                        @if ($video->is_active)
                                            <span class="inline-flex items-center gap-1.5 rounded-full border border-emerald-500/25 bg-emerald-500/10 px-2.5 py-1 text-xs font-medium text-emerald-700">
                                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Aktif
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 rounded-full border border-black/10 bg-black/5 px-2.5 py-1 text-xs font-medium text-smoke">Nonaktif</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('tiktok-videos.edit', $video) }}"
                                                class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-black/10 bg-black/5 text-smoke hover:text-gold-soft"
                                                aria-label="Edit {{ $video->title }}">
                                                <i class="fa-solid fa-pen text-xs"></i>
                                            </a>
                                            <form action="{{ route('tiktok-videos.destroy', $video) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" data-confirm-submit data-confirm-intent="delete"
                                                    data-confirm-title="Hapus video TikTok ini?"
                                                    data-confirm-message="Video akan dihapus dari daftar client area."
                                                    data-confirm-action-label="Ya, hapus"
                                                    class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-red-500/20 bg-red-500/5 text-red-700 hover:bg-red-500/10"
                                                    aria-label="Hapus {{ $video->title }}">
                                                    <i class="fa-solid fa-trash text-xs"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if ($videos->hasPages())
                    <div class="border-t border-black/8 px-6 py-4">{{ $videos->links() }}</div>
                @endif
            @endif
        </div>
    </section>
@endsection
