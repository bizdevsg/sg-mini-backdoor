@php($ebookCategory = $ebookCategory ?? null)

@if ($errors->any())
    <div class="rounded-xl border border-red-500/30 bg-red-950/30 px-4 py-3 text-sm text-red-200">
        {{ $errors->first() }}
    </div>
@endif

<div class="space-y-6">
    <div class="rounded-2xl border border-white/8 bg-white/4 p-6">
        <div class="grid gap-5">
            <div>
                <label for="name" class="mb-2 block text-sm font-medium text-white">Nama Kategori</label>
                <input type="text" id="name" name="name" value="{{ old('name', $ebookCategory?->name) }}"
                    class="w-full rounded-lg border bg-onyx px-4 py-3 text-champagne placeholder:text-smoke focus:border-gold/30 focus:outline-none focus:ring-2 focus:ring-gold/15 {{ $errors->has('name') ? 'border-red-400/60' : 'border-white/8' }}"
                    placeholder="Contoh: Analisis Pasar" required>
                @error('name')
                    <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                @enderror
            </div>
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
