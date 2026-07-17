<tr class="{{ cn('transition-colors hover:bg-white/4') }}">
    @include('components.atoms.table-cell', ['text' => $index + 1])

    <td class="{{ cn('px-6 py-4') }}">
        <p class="{{ cn('font-medium text-white') }}">{{ $product->nama_produk }}</p>
    </td>

    @include('components.atoms.table-cell', ['text' => $product->kategori])

    @include('components.atoms.table-cell', ['text' => \Illuminate\Support\Str::limit(strip_tags($product->deskripsi_produk), 80)])

    @include('components.atoms.table-cell', ['text' => $product->created_at?->format('d M Y, H:i') ?? '-'])
</tr>
