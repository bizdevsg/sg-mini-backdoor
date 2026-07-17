@php($penghargaan = $penghargaan ?? null)
@php($currentImageUrl = $penghargaan?->image_url)

@if ($errors->any())
    <div class="rounded-xl border border-red-500/30 bg-red-950/30 px-4 py-3 text-sm text-red-200">
        {{ $errors->first() }}
    </div>
@endif

<div class="space-y-6">
    <div class="rounded-2xl border border-white/8 bg-white/4 p-6">
        <div class="grid gap-5">
            <div>
                <label for="title" class="mb-2 block text-sm font-medium text-white">Judul</label>
                <input type="text" id="title" name="title" value="{{ old('title', $penghargaan?->title) }}"
                    class="w-full rounded-lg border bg-onyx px-4 py-3 text-champagne placeholder:text-smoke focus:border-gold/30 focus:outline-none focus:ring-2 focus:ring-gold/15 {{ $errors->has('title') ? 'border-red-400/60' : 'border-white/8' }}"
                    placeholder="Contoh: Best Market Performance 2024" required>
                @error('title')
                    <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="subtitle" class="mb-2 block text-sm font-medium text-white">Subtitle</label>
                <x-forms.tinymce-editor id="subtitle" name="subtitle" :value="old('subtitle', $penghargaan?->subtitle)"
                    :height="260" placeholder="Tulis subtitle singkat penghargaan." required />
            </div>

            <div>
                <label for="image" class="mb-2 block text-sm font-medium text-white">Image</label>
                <input type="file" id="image" name="image"
                    accept=".jpg,.jpeg,.png,.webp,.avif,image/jpeg,image/png,image/webp,image/avif"
                    class="block w-full rounded-lg border bg-onyx px-4 py-3 text-sm text-champagne file:mr-4 file:rounded-md file:border-0 file:bg-white file:px-3 file:py-2 file:text-sm file:font-medium file:text-obsidian hover:file:bg-slate-200 focus:border-gold/30 focus:outline-none focus:ring-2 focus:ring-gold/15 {{ $errors->has('image') ? 'border-red-400/60' : 'border-white/8' }}">
                <p class="mt-2 text-xs text-smoke">Upload JPG, PNG, WebP, atau AVIF. File akan disimpan sebagai AVIF atau WebP.</p>
                @error('image')
                    <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                @enderror
            </div>

            @if ($currentImageUrl)
                <div class="rounded-xl border border-white/8 bg-onyx p-4">
                    <p class="text-xs font-medium uppercase tracking-[0.18em] text-smoke">Image saat ini</p>
                    <div class="mt-4 overflow-hidden rounded-xl border border-white/8">
                        <img src="{{ $currentImageUrl }}" alt="{{ $penghargaan->title }}" class="h-48 w-full object-cover">
                    </div>
                    <p class="mt-3 break-all text-xs text-smoke">{{ $penghargaan->image }}</p>
                </div>
            @endif
        </div>
    </div>
</div>

<div class="flex flex-col gap-3 sm:flex-row sm:justify-end">
    <a href="{{ $cancelUrl }}"
        class="inline-flex items-center justify-center rounded-lg border border-white/8 bg-white/5 px-5 py-3 text-sm font-medium text-white transition-colors hover:bg-white/10">
        Batal
    </a>
    <button type="submit"
        class="inline-flex items-center justify-center rounded-lg bg-white px-5 py-3 text-sm font-medium text-obsidian transition-colors hover:bg-slate-200">
        {{ $submitLabel }}
    </button>
</div>
