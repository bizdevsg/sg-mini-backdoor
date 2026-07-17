<div class="{{ cn('overflow-hidden rounded-2xl border border-white/8 bg-white/4') }}">
    @include('components.molecules.section-header', [
        'eyebrow' => 'Produk terbaru',
        'title' => 'Aktivitas katalog terbaru',
        'actions' => $actions ?? [],
    ])

    <div class="{{ cn('overflow-x-auto') }}">
        <table class="{{ cn('min-w-full divide-y divide-white/8') }}">
            <thead class="{{ cn('bg-noir/70') }}">
                <tr class="{{ cn('text-left text-xs uppercase tracking-[0.18em] text-smoke') }}">
                    @include('components.atoms.table-cell', ['as' => 'th', 'muted' => false, 'class' => 'font-medium', 'text' => 'No'])
                    @include('components.atoms.table-cell', ['as' => 'th', 'muted' => false, 'class' => 'font-medium', 'text' => 'Nama Produk'])
                    @include('components.atoms.table-cell', ['as' => 'th', 'muted' => false, 'class' => 'font-medium', 'text' => 'Kategori'])
                    @include('components.atoms.table-cell', ['as' => 'th', 'muted' => false, 'class' => 'font-medium', 'text' => 'Desc'])
                    @include('components.atoms.table-cell', ['as' => 'th', 'muted' => false, 'class' => 'font-medium', 'text' => 'Dibuat'])
                </tr>
            </thead>
            <tbody class="{{ cn('divide-y divide-white/6') }}">
                @forelse ($products as $index => $product)
                    @include('components.molecules.recent-product-row', [
                        'product' => $product,
                        'index' => $index,
                    ])
                @empty
                    <tr>
                        <td colspan="5" class="{{ cn('px-6 py-10 text-center text-sm text-smoke') }}">
                            Belum ada produk terbaru untuk ditampilkan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
