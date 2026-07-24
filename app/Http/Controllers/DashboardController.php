<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Models\Banner;
use App\Models\Berita;
use App\Models\BeritaCategory;
use App\Models\Ebook;
use App\Models\EbookCategory;
use App\Models\Informasi;
use App\Models\Penghargaan;
use App\Models\Produk;
use App\Models\Signal;
use App\Models\SignalCategory;
use App\Models\User;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $userCount = User::query()->count('*');

        $superadminCount = User::query()
            ->where('role', UserRole::Superadmin->value)
            ->count('*');

        $adminCount = User::query()
            ->where('role', UserRole::Admin->value)
            ->count('*');

        $adminHostCount = User::query()
            ->where('role', UserRole::AdminHost->value)
            ->count('*');

        $produkJFXcount = Produk::query()->where('kategori', 'JFX')->count('*');

        $produkSPAcount = Produk::query()->where('kategori', 'SPA')->count('*');

        $informasiCount = Informasi::query()->count('*');

        $beritaCount = Berita::query()->count('*');

        $penghargaanCount = Penghargaan::query()->count('*');

        $bannerCount = Banner::query()
            ->where('is_active', true)
            ->count('*');

        $signalCategoryCount = SignalCategory::query()->count('*');

        $beritaCategoryCount = BeritaCategory::query()->count('*');

        $signalCount = Signal::query()->count('*');

        $ebookCount = Ebook::query()->count('*');

        $ebookCategoryCount = EbookCategory::query()->count('*');

        $recentProducts = Produk::query()
            ->latest()
            ->take(5)
            ->get(['id', 'nama_produk', 'kategori', 'deskripsi_produk', 'created_at']);

        $recentEbooks = Ebook::query()
            ->with('category:id,name,slug')
            ->latest()
            ->take(5)
            ->get(['id', 'title', 'slug', 'ebook_category_id', 'created_at']);

        return view('dashboard.beranda', compact(
            'userCount',
            'superadminCount',
            'adminCount',
            'adminHostCount',
            'produkSPAcount',
            'produkJFXcount',
            'informasiCount',
            'beritaCount',
            'penghargaanCount',
            'bannerCount',
            'signalCategoryCount',
            'beritaCategoryCount',
            'signalCount',
            'ebookCount',
            'ebookCategoryCount',
            'recentProducts',
            'recentEbooks',
        ));
    }
}
