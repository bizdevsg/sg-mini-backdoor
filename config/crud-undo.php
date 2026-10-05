<?php

use App\Models\Banner;
use App\Models\Berita;
use App\Models\BeritaCategory;
use App\Models\CompanyProfile;
use App\Models\Ebook;
use App\Models\EbookCategory;
use App\Models\Informasi;
use App\Models\Legalitas;
use App\Models\Penghargaan;
use App\Models\PrivacyPolicy;
use App\Models\Produk;
use App\Models\Signal;
use App\Models\SignalCategory;
use App\Models\TermsAndCondition;
use App\Models\TradingviewSymbol;
use App\Models\User;
use App\Models\WakilPialang;
use App\Models\WakilPialangCategory;

return [
    'ttl_seconds' => 10,
    'retention_hours' => 24,

    'singletons' => [
        'company-profile.update' => CompanyProfile::class,
        'terms-and-conditions.update' => TermsAndCondition::class,
        'privacy-policy.update' => PrivacyPolicy::class,
    ],

    'models' => [
        Banner::class => ['label' => 'Banner', 'files' => ['image']],
        Produk::class => ['label' => 'Produk', 'files' => ['image']],
        Informasi::class => ['label' => 'Pengumuman', 'files' => ['image']],
        Ebook::class => ['label' => 'Ebook', 'files' => ['image', 'file']],
        EbookCategory::class => ['label' => 'Kategori ebook', 'files' => []],
        Signal::class => ['label' => 'Signal', 'files' => ['image']],
        SignalCategory::class => ['label' => 'Kategori signal', 'files' => []],
        Berita::class => ['label' => 'Berita', 'files' => ['image']],
        BeritaCategory::class => ['label' => 'Kategori berita', 'files' => []],
        WakilPialang::class => ['label' => 'Wakil pialang', 'files' => []],
        WakilPialangCategory::class => ['label' => 'Kategori wakil pialang', 'files' => []],
        Penghargaan::class => ['label' => 'Penghargaan', 'files' => ['image']],
        Legalitas::class => ['label' => 'Legalitas', 'files' => []],
        TradingviewSymbol::class => ['label' => 'Kode TradingView', 'files' => []],
        CompanyProfile::class => ['label' => 'Profil perusahaan', 'files' => []],
        TermsAndCondition::class => ['label' => 'Syarat dan ketentuan', 'files' => []],
        PrivacyPolicy::class => ['label' => 'Kebijakan privasi', 'files' => []],
        User::class => ['label' => 'User', 'files' => []],
    ],
];
