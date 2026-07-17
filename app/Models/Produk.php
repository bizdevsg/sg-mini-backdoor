<?php

namespace App\Models;

use App\Support\ImagePath;
use DOMDocument;
use DOMElement;
use DOMNode;
use DOMXPath;
use Database\Factories\ProdukFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

#[Fillable([
    'nama_produk',
    'slug',
    'deskripsi_produk',
    'specs',
    'image',
    'kategori',
])]
class Produk extends Model
{
    /** @use HasFactory<ProdukFactory> */
    use HasFactory;

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public static function generateSlug(string $namaProduk, ?self $ignore = null): string
    {
        $baseSlug = now()->format('dmYHi') . '-' . Str::slug($namaProduk);
        $slug = $baseSlug;
        $counter = 2;

        while (static::query()
            ->when($ignore, fn ($query) => $query->whereKeyNot($ignore->getKey()))
            ->where('slug', $slug)
            ->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    protected function specsForDisplay(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->cleanSpecsHtml($this->specs),
        );
    }

    protected function imageUrl(): Attribute
    {
        return Attribute::make(
            get: function () {
                $image = trim((string) $this->image);

                if ($image === '') {
                    return null;
                }

                if (Str::startsWith($image, ['http://', 'https://', '/'])) {
                    return $image;
                }

                return asset('storage/' . ltrim((string) ImagePath::normalize($image), '/'));
            },
        );
    }

    private function cleanSpecsHtml(?string $specs): string
    {
        $html = trim((string) $specs);

        if ($html === '') {
            return '<p>Tidak ada spesifikasi.</p>';
        }

        $previousState = libxml_use_internal_errors(true);

        $document = new DOMDocument('1.0', 'UTF-8');
        $document->loadHTML(
            '<?xml encoding="utf-8" ?><div id="specs-root">' . $html . '</div>',
            LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
        );

        $xpath = new DOMXPath($document);

        foreach ($xpath->query('//temporary | //span[contains(@class, "ql-ui")]') as $node) {
            $node?->parentNode?->removeChild($node);
        }

        foreach ($xpath->query('//*[@data-row or @data-cell or @contenteditable or @height]') as $element) {
            if (! $element instanceof DOMElement) {
                continue;
            }

            $element->removeAttribute('data-row');
            $element->removeAttribute('data-cell');
            $element->removeAttribute('contenteditable');
            $element->removeAttribute('height');
        }

        foreach ($xpath->query('//*[@class]') as $element) {
            if (! $element instanceof DOMElement) {
                continue;
            }

            $classes = collect(preg_split('/\s+/', trim((string) $element->getAttribute('class'))))
                ->filter()
                ->reject(fn (string $class) => in_array($class, [
                    'ql-cell-focused',
                    'ql-cell-selected',
                    'ql-cell-selected-after',
                    'ql-table-block',
                    'table-list',
                    'table-list-container',
                ], true))
                ->map(fn (string $class) => $class === 'ql-table-better' ? 'produk-specs-table' : $class)
                ->values()
                ->all();

            if ($classes === []) {
                $element->removeAttribute('class');
                continue;
            }

            $element->setAttribute('class', implode(' ', $classes));
        }

        $root = $document->getElementById('specs-root');

        if (! $root instanceof DOMElement) {
            libxml_clear_errors();
            libxml_use_internal_errors($previousState);

            return $html;
        }

        $output = '';

        foreach ($root->childNodes as $childNode) {
            if (! $childNode instanceof DOMNode) {
                continue;
            }

            $output .= $document->saveHTML($childNode);
        }

        libxml_clear_errors();
        libxml_use_internal_errors($previousState);

        return $output !== '' ? $output : '<p>Tidak ada spesifikasi.</p>';
    }
}
