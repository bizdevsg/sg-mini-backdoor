<?php

namespace App\Support;

class SystemActivitySubjectCatalog
{
    /**
     * @return array<string, string>
     */
    public static function api(): array
    {
        return [
            'client-area' => 'System Settings',
            'banner' => 'Banner',
            'produk' => 'Produk',
            'pengumuman' => 'Pengumuman',
            'ebook' => 'Ebook',
            'signal' => 'Signal',
            'berita' => 'Berita',
            'wakil-pialang-berjangka' => 'Wakil Pialang Berjangka',
            'penghargaan' => 'Penghargaan',
            'legalitas' => 'Legalitas',
            'company-profile' => 'Profil Perusahaan',
            'terms-and-conditions' => 'Syarat dan Ketentuan',
            'privacy-policy' => 'Kebijakan Privasi',
            'tradingview-symbol' => 'Kode TradingView',
            'massages' => 'Massages',
            'unknown' => 'Unknown',
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function data(): array
    {
        return [
            'banner' => 'Banner',
            'produk' => 'Produk',
            'pengumuman' => 'Pengumuman',
            'ebook' => 'Ebook',
            'signal' => 'Signal',
            'signal-categories' => 'Kategori Signal',
            'berita' => 'Berita',
            'berita-categories' => 'Kategori Berita',
            'ebook-categories' => 'Kategori Ebook',
            'wakil-pialang-categories' => 'Kategori Wakil Pialang',
            'wakil-pialang' => 'Wakil Pialang Berjangka',
            'penghargaan' => 'Penghargaan',
            'legalitas' => 'Legalitas',
            'company-profile' => 'Profil Perusahaan',
            'terms-and-conditions' => 'Syarat dan Ketentuan',
            'privacy-policy' => 'Kebijakan Privasi',
            'tradingview' => 'Kode TradingView',
            'user-management' => 'User Management',
            'client-area' => 'System Settings',
            'tinymce-images' => 'Media Editor',
            'api-documentation' => 'Dokumentasi API',
            'unknown' => 'Unknown',
        ];
    }
}
