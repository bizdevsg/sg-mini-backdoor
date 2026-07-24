<?php

namespace App\Support;

use Illuminate\Support\Str;

class ImagePath
{
    /**
     * @var array<string, string>
     */
    private const LEGACY_PREFIXES = [
        'banner-images/' => 'uploads/banner/',
        'signal-images/' => 'uploads/signal/',
        'berita-images/' => 'uploads/berita/',
        'ebook-images/' => 'uploads/ebook/',
        'produk-images/' => 'uploads/produk/',
        'penghargaan-images/' => 'uploads/penghargaan/',
        'informasi-images/' => 'uploads/informasi/',
        'informasi/' => 'uploads/informasi/',
    ];

    public static function normalize(?string $path): ?string
    {
        $path = trim((string) $path);

        if ($path === '') {
            return null;
        }

        foreach (self::LEGACY_PREFIXES as $legacyPrefix => $targetPrefix) {
            if (Str::startsWith($path, $legacyPrefix)) {
                return $targetPrefix . ltrim(Str::after($path, $legacyPrefix), '/');
            }
        }

        return $path;
    }
}
