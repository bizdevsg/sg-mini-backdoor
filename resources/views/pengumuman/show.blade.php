@extends('layouts.app')

@section('title', 'Detail Pengumuman')

@section('content')
    <section class="space-y-6">
        <div class="rounded-2xl border border-white/8 bg-white/4 p-6">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                <div class="space-y-3">
                    <p class="text-xs font-medium uppercase tracking-[0.18em] text-smoke">Detail pengumuman</p>
                    <h2 class="text-3xl font-semibold tracking-[-0.04em] text-white">{{ $informasi->title }}</h2>
                    <span class="inline-flex rounded-lg border border-white/8 bg-onyx px-3 py-1.5 font-mono text-xs text-champagne">
                        {{ $informasi->slug }}
                    </span>
                </div>

                <div class="flex gap-3">
                    <a href="{{ route('pengumuman.index') }}"
                        class="inline-flex items-center justify-center rounded-lg border border-white/8 bg-white/5 px-4 py-3 text-sm font-medium text-white transition-colors hover:bg-white/10">
                        Kembali
                    </a>
                    <a href="{{ route('pengumuman.edit', $informasi) }}"
                        class="inline-flex items-center justify-center rounded-lg bg-white px-4 py-3 text-sm font-medium text-obsidian transition-colors hover:bg-slate-200">
                        Edit
                    </a>
                </div>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-[320px_minmax(0,1fr)]">
            <div class="space-y-6">
                <div class="overflow-hidden rounded-2xl border border-white/8 bg-white/4">
                    @if ($informasi->image_url)
                        <img src="{{ $informasi->image_url }}" alt="{{ $informasi->title }}" class="h-64 w-full object-cover">
                    @else
                        <div class="flex h-64 items-center justify-center bg-onyx text-sm text-smoke">
                            Image tidak tersedia
                        </div>
                    @endif
                </div>

                <div class="rounded-2xl border border-white/8 bg-white/4 p-6">
                    <h3 class="text-lg font-semibold text-white">Informasi</h3>
                    <dl class="mt-4 space-y-4 text-sm">
                        <div>
                            <dt class="text-smoke">Dibuat</dt>
                            <dd class="mt-1 text-white">{{ $informasi->created_at?->format('d M Y, H:i') ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-smoke">Diupdate</dt>
                            <dd class="mt-1 text-white">{{ $informasi->updated_at?->format('d M Y, H:i') ?? '-' }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <div class="rounded-2xl border border-white/8 bg-white/4 p-6">
                <h3 class="text-lg font-semibold text-white">Konten</h3>
                <div
                    class="prose prose-invert mt-4 max-w-none text-sm leading-6 prose-headings:mt-4 prose-headings:mb-2 prose-headings:font-semibold prose-headings:text-white prose-p:my-2 prose-p:text-smoke prose-strong:text-white prose-em:text-champagne prose-a:text-gold-soft prose-a:no-underline hover:prose-a:text-white prose-ul:my-2 prose-ul:list-disc prose-ul:pl-5 prose-ul:text-smoke prose-ol:my-2 prose-ol:list-decimal prose-ol:pl-5 prose-ol:text-smoke prose-li:my-0.5 prose-li:text-smoke prose-li:marker:text-gold-soft prose-blockquote:border-gold prose-blockquote:py-0 prose-blockquote:text-white prose-code:rounded prose-code:bg-white/6 prose-code:px-1.5 prose-code:py-0.5 prose-code:text-white prose-pre:overflow-x-auto prose-pre:rounded-2xl prose-pre:border prose-pre:border-white/8 prose-pre:bg-onyx prose-pre:px-5 prose-pre:py-4 prose-pre:text-white prose-pre:prose-code:bg-transparent prose-pre:prose-code:px-0 prose-pre:prose-code:py-0 prose-hr:my-4 prose-hr:border-white/10 prose-table:my-3 prose-table:w-full prose-table:border-collapse prose-img:rounded-2xl prose-th:border prose-th:border-white/12 prose-th:bg-white/4 prose-th:px-4 prose-th:py-2.5 prose-th:text-left prose-th:align-top prose-th:font-semibold prose-th:text-white prose-td:border prose-td:border-white/12 prose-td:px-4 prose-td:py-2.5 prose-td:text-left prose-td:align-top prose-td:text-smoke [&_temporary]:hidden [&_.ql-ui]:hidden [&_figure.image]:mx-auto [&_figure.image]:max-w-full [&_img]:h-auto [&_img]:max-w-full [&_img]:rounded-2xl [&_img.img-full]:w-full [&_img.img-narrow]:mx-auto [&_img.img-narrow]:w-full [&_img.img-narrow]:max-w-[28rem] [&>:first-child]:mt-0 [&>:last-child]:mb-0">
                    {!! $informasi->content_for_display !!}
                </div>
            </div>
        </div>
    </section>
@endsection
