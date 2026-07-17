<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Models\Banner;
use App\Models\Informasi;
use App\Models\Penghargaan;
use App\Models\Produk;
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

        $produkJFXcount = Produk::query()->where('kategori', 'JFX')->count('*');

        $produkSPAcount = Produk::query()->where('kategori', 'SPA')->count('*');

        $informasiCount = Informasi::query()->count('*');

        $penghargaanCount = Penghargaan::query()->count('*');

        $bannerCount = Banner::query()
            ->where('is_active', true)
            ->count('*');

        $recentProducts = Produk::query()
            ->latest()
            ->take(5)
            ->get(['id', 'nama_produk', 'kategori', 'deskripsi_produk', 'created_at']);

        return view('dashboard.beranda', compact(
            'userCount',
            'superadminCount',
            'produkSPAcount',
            'produkJFXcount',
            'informasiCount',
            'penghargaanCount',
            'bannerCount',
            'recentProducts',
        ));
    }
}
