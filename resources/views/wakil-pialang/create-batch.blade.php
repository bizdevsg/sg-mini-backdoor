@extends('layouts.app')

@section('title', 'Tambah Wakil Pialang Berjangka')

@section('content')
    @php($items = old('items', [['nama' => '', 'no_identitas' => '', 'status' => 'aktif']]))

    <section class="space-y-6">
        <div
            class="relative overflow-hidden rounded-[28px] border border-black/8 bg-[radial-gradient(ellipse_70%_80%_at_0%_0%,rgba(199,161,90,0.14),transparent),linear-gradient(160deg,rgba(21,17,13,0.05)_0%,rgba(21,17,13,0.01)_100%)] px-7 py-6 lg:px-9 lg:py-8">
            <div class="pointer-events-none absolute -right-12 -top-12 h-40 w-40 rounded-full bg-gold/8 blur-[56px]"></div>
            <div
                class="pointer-events-none absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-gold/35 to-transparent">
            </div>

            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div class="space-y-2">
                    <span
                        class="inline-flex items-center gap-2 rounded-full border border-gold/20 bg-gold/8 px-3 py-1 text-[10px] font-semibold uppercase tracking-[0.24em] text-gold-soft/90">
                        <span class="h-1.5 w-1.5 animate-pulse rounded-full bg-gold"></span>
                        Tambah
                    </span>
                    <h1 class="text-2xl font-semibold tracking-[-0.04em] text-ivory lg:text-3xl">Tambah Wakil Pialang <span
                            class="bg-gradient-to-r from-gold-soft to-champagne bg-clip-text text-transparent">Sekaligus</span>
                    </h1>
                    <p class="max-w-xl text-sm leading-6 text-smoke">Tambahkan beberapa wakil pialang untuk kategori
                        {{ $wakilPialangCategory->nama_kategori }} dalam satu kali penyimpanan.</p>
                </div>
                <a href="{{ route('wakil-pialang.index', $wakilPialangCategory) }}"
                    class="inline-flex w-fit items-center gap-1.5 rounded-xl border border-black/10 bg-black/5 px-4 py-2.5 text-sm font-medium text-smoke transition-all duration-200 hover:border-black/18 hover:bg-black/8 hover:text-ivory">
                    <i class="fa-solid fa-arrow-left text-[10px]"></i>
                    Kembali
                </a>
            </div>
        </div>

        <form action="{{ route('wakil-pialang.batch.store', $wakilPialangCategory) }}" method="POST" class="space-y-6"
            id="batch-wakil-pialang-form">
            @csrf

            @if ($errors->any())
                <div
                    class="flex items-center gap-3 rounded-xl border border-red-500/30 bg-red-50/40 px-4 py-3 text-sm text-red-800 shadow-lg">
                    <i class="fa-solid fa-triangle-exclamation text-base text-red-600"></i>
                    <div>
                        <p class="font-medium text-red-700">Terdapat kesalahan pengisian.</p>
                        <p class="text-xs text-red-800/80">Periksa setiap baris yang ditandai lalu simpan kembali.</p>
                    </div>
                </div>
            @endif

            <div class="rounded-2xl border border-black/8 bg-black/3 p-6">
                <div
                    class="flex flex-col gap-3 border-b border-black/6 pb-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="text-base font-semibold text-ivory">Daftar Wakil Pialang</h2>
                        <p class="mt-0.5 text-xs text-smoke">Maksimal 100 wakil pialang dalam satu kali tambah.</p>
                    </div>
                    <button type="button" id="add-wakil-pialang-row"
                        class="inline-flex w-fit items-center gap-2 rounded-xl border border-gold/25 bg-gold/10 px-4 py-2.5 text-sm font-semibold text-gold-soft transition-all hover:border-gold/40 hover:bg-gold/18"><i
                            class="fa-solid fa-plus text-xs"></i> Tambah Baris</button>
                </div>

                <div id="wakil-pialang-rows" class="mt-5 space-y-4">
                    @foreach ($items as $index => $item)
                        @include('wakil-pialang.partials.batch-row', ['index' => $index, 'item' => $item])
                    @endforeach
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 border-t border-black/6 pt-6">
                <a href="{{ route('wakil-pialang.index', $wakilPialangCategory) }}"
                    class="inline-flex items-center justify-center gap-2 rounded-xl border border-black/10 bg-black/5 px-5 py-2.5 text-sm font-medium text-smoke transition-all duration-200 hover:border-black/18 hover:bg-black/8 hover:text-ivory">Batal</a>
                <button type="submit" data-confirm-submit data-confirm-intent="save"
                    data-confirm-title="Simpan seluruh wakil pialang?"
                    data-confirm-message="Pastikan seluruh nama dan nomor identitas sudah benar sebelum disimpan."
                    data-confirm-action-label="Ya, simpan semua"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-gold px-6 py-2.5 text-sm font-semibold text-obsidian shadow-[0_4px_18px_rgba(199,161,90,0.28)] transition-all duration-200 hover:bg-gold-soft hover:shadow-[0_6px_24px_rgba(199,161,90,0.4)]"><i
                        class="fa-solid fa-check text-xs"></i> Simpan Semua</button>
            </div>
        </form>

        <template id="wakil-pialang-row-template">
            @include('wakil-pialang.partials.batch-row', [
                'index' => '__INDEX__',
                'item' => ['nama' => '', 'no_identitas' => '', 'status' => 'aktif'],
            ])
        </template>
    </section>
@endsection

@push('scripts')
    <script>
        (() => {
            const rows = document.getElementById('wakil-pialang-rows');
            const template = document.getElementById('wakil-pialang-row-template');
            const addButton = document.getElementById('add-wakil-pialang-row');
            let nextIndex = {{ count($items) }};

            addButton.addEventListener('click', () => {
                rows.insertAdjacentHTML('beforeend', template.innerHTML.replaceAll('__INDEX__', nextIndex));
                nextIndex++;
            });

            rows.addEventListener('click', (event) => {
                const removeButton = event.target.closest('[data-remove-wakil-pialang-row]');
                if (removeButton && rows.children.length > 1) removeButton.closest('[data-wakil-pialang-row]')
                    .remove();
            });
        })();
    </script>
@endpush
