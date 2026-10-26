@php($video = $video ?? null)

@if ($errors->any())
    <div class="flex items-center gap-3 rounded-xl border border-red-500/30 bg-red-50/40 px-4 py-3 text-sm text-red-800 shadow-lg">
        <i class="fa-solid fa-triangle-exclamation text-red-600"></i>
        <div>
            <p class="font-medium text-red-700">Terdapat kesalahan pengisian:</p>
            <p class="text-xs text-red-800/80">{{ $errors->first() }}</p>
        </div>
    </div>
@endif

<div class="grid gap-6 lg:grid-cols-[1fr_360px]">
    <div class="rounded-2xl border border-black/8 bg-black/3 p-6">
        <div class="border-b border-black/6 pb-4">
            <h3 class="text-base font-semibold text-ivory">Detail Video TikTok</h3>
            <p class="mt-1 text-xs text-smoke">Video ID akan diambil otomatis dari URL TikTok.</p>
        </div>

        <div class="mt-5 space-y-5">
            <div>
                <label for="title" class="mb-2 block text-xs font-semibold uppercase tracking-[0.16em] text-smoke">
                    Judul Video <span class="text-gold-soft">*</span>
                </label>
                <input id="title" name="title" type="text" maxlength="150"
                    value="{{ old('title', $video?->title) }}" placeholder="Contoh: Video TikTok"
                    class="w-full rounded-xl border bg-onyx px-4 py-3 text-sm text-champagne placeholder:text-smoke/40 focus:border-gold/35 focus:outline-none focus:ring-2 focus:ring-gold/12 {{ $errors->has('title') ? 'border-red-400/60' : 'border-black/8' }}"
                    required>
                @error('title')
                    <p class="mt-1.5 text-xs font-medium text-red-700">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="video_url" class="mb-2 block text-xs font-semibold uppercase tracking-[0.16em] text-smoke">
                    URL Video TikTok <span class="text-gold-soft">*</span>
                </label>
                <input id="video_url" name="video_url" type="url" maxlength="500"
                    value="{{ old('video_url', $video?->video_url) }}"
                    placeholder="https://www.tiktok.com/@username/video/7700863615682858247"
                    class="w-full rounded-xl border bg-onyx px-4 py-3 font-mono text-sm text-champagne placeholder:text-smoke/40 focus:border-gold/35 focus:outline-none focus:ring-2 focus:ring-gold/12 {{ $errors->has('video_url') ? 'border-red-400/60' : 'border-black/8' }}"
                    required>
                <p class="mt-2 text-[11px] leading-5 text-smoke/70">
                    Gunakan URL lengkap dengan format <span class="font-mono text-champagne/80">/@username/video/{video_id}</span>.
                </p>
                @error('video_url')
                    <p class="mt-1.5 text-xs font-medium text-red-700">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    <div class="space-y-6">
        <div class="rounded-2xl border border-black/8 bg-black/3 p-5">
            <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-smoke/70">Status Tampil</p>
            <div class="mt-3 flex items-start gap-3 rounded-xl border border-black/6 bg-onyx/60 p-4">
                <input type="hidden" name="is_active" value="0">
                <input id="is_active" name="is_active" type="checkbox" value="1"
                    @checked(old('is_active', $video?->is_active ?? false))
                    class="mt-0.5 h-4 w-4 cursor-pointer rounded border-black/20 bg-onyx text-gold focus:ring-gold/30">
                <div>
                    <label for="is_active" class="cursor-pointer select-none text-sm font-semibold text-ivory">
                        Tampilkan video
                    </label>
                    <p class="mt-1 text-xs leading-5 text-smoke/80">Centang jika video siap ditampilkan pada client area.</p>
                </div>
            </div>
            @error('is_active')
                <p class="mt-2 text-xs font-medium text-red-700">{{ $message }}</p>
            @enderror
        </div>

        <div class="rounded-2xl border border-black/8 bg-black/3 p-5">
            <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-smoke/70">Preview Embed</p>
            @if ($video)
                <div class="mt-3 overflow-hidden rounded-xl border border-black/10 bg-black">
                    <iframe src="https://www.tiktok.com/player/v1/{{ $video->video_id }}"
                        title="Preview {{ $video->title }}" class="aspect-[9/16] w-full" loading="lazy"
                        allow="fullscreen" referrerpolicy="strict-origin-when-cross-origin"></iframe>
                </div>
            @else
                <div class="mt-3 flex min-h-52 flex-col items-center justify-center gap-3 rounded-xl border border-dashed border-black/12 bg-onyx/50 p-6 text-center">
                    <i class="fa-brands fa-tiktok text-3xl text-smoke/50"></i>
                    <p class="text-xs leading-5 text-smoke/60">Preview embed tersedia setelah video disimpan.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<div class="flex items-center justify-end gap-3 border-t border-black/6 pt-6">
    <a href="{{ route('tiktok-videos.index') }}"
        class="inline-flex items-center justify-center rounded-xl border border-black/10 bg-black/5 px-5 py-2.5 text-sm font-medium text-smoke transition hover:bg-black/8 hover:text-ivory">
        Batal
    </a>
    <button type="submit" data-confirm-submit data-confirm-intent="save"
        data-confirm-title="{{ $confirmTitle }}" data-confirm-message="{{ $confirmMessage }}"
        data-confirm-action-label="{{ $confirmActionLabel }}"
        class="inline-flex items-center justify-center gap-2 rounded-xl bg-gold px-6 py-2.5 text-sm font-semibold text-obsidian shadow-[0_4px_18px_rgba(199,161,90,0.28)] transition hover:bg-gold-soft">
        <i class="fa-solid fa-check text-xs"></i>
        {{ $submitLabel }}
    </button>
</div>
