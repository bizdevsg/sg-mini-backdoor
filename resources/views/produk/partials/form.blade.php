@php($produk = $produk ?? null)
@php($confirmTitle = $confirmTitle ?? 'Simpan data?')
@php($confirmMessage = $confirmMessage ?? 'Pastikan data yang diisi sudah benar sebelum dilanjutkan.')
@php($confirmActionLabel = $confirmActionLabel ?? 'Ya, simpan')
@php($currentImageUrl = null)
@if ($produk?->image)
    @php($currentImageUrl = $produk->image_url)
@endif

@if ($errors->any())
    <div class="rounded-xl border border-red-500/30 bg-red-50/30 px-4 py-3 text-sm text-red-800">
        {{ $errors->first() }}
    </div>
@endif

<div class="space-y-6">
    <div class="space-y-6">
        <div class="rounded-2xl border border-black/8 bg-black/4 p-6">
            <input type="hidden" name="kategori" value="{{ $sectionLabel }}">

            <div class="grid gap-5">
                <div>
                    <label for="image" class="mb-2 block text-sm font-medium text-ivory">Upload Image</label>
                    <input type="file" id="image" name="image"
                        accept=".jpg,.jpeg,.png,.webp,.avif,image/jpeg,image/png,image/webp,image/avif"
                        class="block w-full rounded-lg border bg-onyx px-4 py-3 text-sm text-champagne file:mr-4 file:rounded-md file:border-0 file:bg-white file:px-3 file:py-2 file:text-sm file:font-medium file:text-obsidian hover:file:bg-slate-200 focus:border-gold/30 focus:outline-none focus:ring-2 focus:ring-gold/15 {{ $errors->has('image') ? 'border-red-400/60' : 'border-black/8' }}"
                        {{ $produk ? '' : 'required' }}>
                    <p class="mt-2 text-xs text-smoke">Upload JPG, PNG, WebP, atau AVIF. File akan disimpan sebagai AVIF
                        atau WebP.</p>
                    @error('image')
                        <p class="mt-2 text-sm text-red-700">{{ $message }}</p>
                    @enderror
                </div>

                @if ($currentImageUrl)
                    <div class="rounded-xl border border-black/8 bg-onyx p-4">
                        <p class="text-xs font-medium uppercase tracking-[0.18em] text-smoke">Image saat ini</p>
                        <div class="mt-4 overflow-hidden rounded-xl border border-black/8">
                            <img src="{{ $currentImageUrl }}" alt="{{ $produk->nama_produk }}"
                                class="h-48 w-full object-cover">
                        </div>
                        <p class="mt-3 break-all text-xs text-smoke">{{ $produk->image }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="space-y-6">
        <div class="rounded-2xl border border-black/8 bg-black/4 p-6">
            <div class="grid gap-5">
                <div>
                    <label for="nama_produk" class="mb-2 block text-sm font-medium text-ivory">Nama Produk</label>
                    <input type="text" id="nama_produk" name="nama_produk"
                        value="{{ old('nama_produk', $produk?->nama_produk) }}"
                        class="w-full rounded-lg border bg-onyx px-4 py-3 text-champagne placeholder:text-smoke focus:border-gold/30 focus:outline-none focus:ring-2 focus:ring-gold/15 {{ $errors->has('nama_produk') ? 'border-red-400/60' : 'border-black/8' }}"
                        placeholder="Contoh: SG Premium Account" required>
                    @error('nama_produk')
                        <p class="mt-2 text-sm text-red-700">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="deskripsi_produk" class="mb-2 block text-sm font-medium text-ivory">Deskripsi
                        Produk</label>
                    <textarea id="deskripsi_produk" name="deskripsi_produk" rows="8"
                        class="w-full rounded-lg border bg-onyx px-4 py-3 text-champagne placeholder:text-smoke focus:border-gold/30 focus:outline-none focus:ring-2 focus:ring-gold/15 {{ $errors->has('deskripsi_produk') ? 'border-red-400/60' : 'border-black/8' }}"
                        placeholder="Jelaskan produk secara ringkas dan jelas." required>{{ old('deskripsi_produk', $produk?->deskripsi_produk) }}</textarea>
                    @error('deskripsi_produk')
                        <p class="mt-2 text-sm text-red-700">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="specs" class="mb-2 block text-sm font-medium text-ivory">Spesifikasi</label>
                    <x-forms.tinymce-editor id="specs" name="specs" :value="old('specs', $produk?->specs)" :height="520"
                        placeholder="Tuliskan detail spesifikasi produk." required
                        helper="Editor TinyMCE aktif dengan toolbar lengkap, termasuk tabel, media, code, dan preview." />
                </div>
            </div>
        </div>
    </div>
</div>

<div class="flex flex-col gap-3 sm:flex-row sm:justify-end">
    <a href="{{ $cancelUrl }}"
        class="inline-flex items-center justify-center rounded-lg border border-black/8 bg-black/5 px-5 py-3 text-sm font-medium text-ivory transition-colors hover:bg-black/10">
        Batal
    </a>
    <button type="submit"
        data-confirm-submit
        data-confirm-intent="save"
        data-confirm-title="{{ $confirmTitle }}"
        data-confirm-message="{{ $confirmMessage }}"
        data-confirm-action-label="{{ $confirmActionLabel }}"
        class="inline-flex items-center justify-center rounded-lg bg-gold px-5 py-3 text-sm font-semibold text-obsidian shadow-[0_4px_18px_rgba(199,161,90,0.28)] transition-all duration-200 hover:bg-gold-soft hover:text-white">
        {{ $submitLabel }}
    </button>
</div>
