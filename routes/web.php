<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\BeritaCategoryController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\CompanyProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EbookController;
use App\Http\Controllers\EbookCategoryController;
use App\Http\Controllers\InformasiController;
use App\Http\Controllers\LegalitasController;
use App\Http\Controllers\PenghargaanController;
use App\Http\Controllers\PrivacyPolicyController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\SignalCategoryController;
use App\Http\Controllers\SignalController;
use App\Http\Controllers\TermsAndConditionsController;
use App\Http\Controllers\TinyMceImageController;
use App\Http\Controllers\UserManagementController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route(auth()->user()?->adminLandingRouteName() ?? 'dashboard');
});

Route::middleware(['auth', 'admin.panel.access'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::prefix('banner')
        ->name('banner.')
        ->group(function () {
            Route::get('/', [BannerController::class, 'index'])->name('index');
            Route::get('/create', [BannerController::class, 'create'])->name('create');
            Route::post('/', [BannerController::class, 'store'])->name('store');
            Route::get('/{banner}/edit', [BannerController::class, 'edit'])->name('edit');
            Route::put('/{banner}', [BannerController::class, 'update'])->name('update');
            Route::delete('/{banner}', [BannerController::class, 'destroy'])->name('destroy');
        });
    Route::prefix('produk/{section}')
        ->whereIn('section', ['spa', 'jfx'])
        ->name('produk.')
        ->group(function () {
            Route::get('/', [ProdukController::class, 'index'])->name('index');
            Route::get('/create', [ProdukController::class, 'create'])->name('create');
            Route::post('/', [ProdukController::class, 'store'])->name('store');
            Route::get('/{produk}', [ProdukController::class, 'show'])->name('show');
            Route::get('/{produk}/edit', [ProdukController::class, 'edit'])->name('edit');
            Route::put('/{produk}', [ProdukController::class, 'update'])->name('update');
            Route::delete('/{produk}', [ProdukController::class, 'destroy'])->name('destroy');
        });
    Route::prefix('pengumuman')
        ->name('pengumuman.')
        ->group(function () {
            Route::get('/', [InformasiController::class, 'index'])->name('index');
            Route::get('/create', [InformasiController::class, 'create'])->name('create');
            Route::post('/', [InformasiController::class, 'store'])->name('store');
            Route::get('/{informasi}', [InformasiController::class, 'show'])->name('show');
            Route::get('/{informasi}/edit', [InformasiController::class, 'edit'])->name('edit');
            Route::put('/{informasi}', [InformasiController::class, 'update'])->name('update');
            Route::delete('/{informasi}', [InformasiController::class, 'destroy'])->name('destroy');
        });
    Route::prefix('ebook')
        ->name('ebook.')
        ->group(function () {
            Route::get('/{ebookCategory}', [EbookController::class, 'index'])->name('index');
            Route::get('/{ebookCategory}/create', [EbookController::class, 'create'])->name('create');
            Route::post('/{ebookCategory}', [EbookController::class, 'store'])->name('store');
            Route::get('/{ebookCategory}/{ebook}', [EbookController::class, 'show'])->name('show');
            Route::get('/{ebookCategory}/{ebook}/edit', [EbookController::class, 'edit'])->name('edit');
            Route::put('/{ebookCategory}/{ebook}', [EbookController::class, 'update'])->name('update');
            Route::delete('/{ebookCategory}/{ebook}', [EbookController::class, 'destroy'])->name('destroy');
        });
    Route::prefix('signal')
        ->name('signal.')
        ->group(function () {
            Route::get('/{signalCategory}', [SignalController::class, 'index'])->name('index');
            Route::get('/{signalCategory}/create', [SignalController::class, 'create'])->name('create');
            Route::post('/{signalCategory}', [SignalController::class, 'store'])->name('store');
            Route::get('/{signalCategory}/{signal}', [SignalController::class, 'show'])->name('show');
            Route::get('/{signalCategory}/{signal}/edit', [SignalController::class, 'edit'])->name('edit');
            Route::put('/{signalCategory}/{signal}', [SignalController::class, 'update'])->name('update');
            Route::delete('/{signalCategory}/{signal}', [SignalController::class, 'destroy'])->name('destroy');
        });
    Route::prefix('kategori-signal')
        ->name('signal-categories.')
        ->group(function () {
            Route::get('/', [SignalCategoryController::class, 'index'])->name('index');
            Route::get('/create', [SignalCategoryController::class, 'create'])->name('create');
            Route::post('/', [SignalCategoryController::class, 'store'])->name('store');
            Route::get('/{signalCategory}/edit', [SignalCategoryController::class, 'edit'])->name('edit');
            Route::put('/{signalCategory}', [SignalCategoryController::class, 'update'])->name('update');
            Route::delete('/{signalCategory}', [SignalCategoryController::class, 'destroy'])->name('destroy');
        });
    Route::prefix('berita')
        ->name('berita.')
        ->group(function () {
            Route::get('/{beritaCategory}', [BeritaController::class, 'index'])->name('index');
            Route::get('/{beritaCategory}/create', [BeritaController::class, 'create'])->name('create');
            Route::post('/{beritaCategory}', [BeritaController::class, 'store'])->name('store');
            Route::get('/{beritaCategory}/{berita}', [BeritaController::class, 'show'])->name('show');
            Route::get('/{beritaCategory}/{berita}/edit', [BeritaController::class, 'edit'])->name('edit');
            Route::put('/{beritaCategory}/{berita}', [BeritaController::class, 'update'])->name('update');
            Route::delete('/{beritaCategory}/{berita}', [BeritaController::class, 'destroy'])->name('destroy');
        });
    Route::prefix('kategori-berita')
        ->name('berita-categories.')
        ->group(function () {
            Route::get('/', [BeritaCategoryController::class, 'index'])->name('index');
            Route::get('/create', [BeritaCategoryController::class, 'create'])->name('create');
            Route::post('/', [BeritaCategoryController::class, 'store'])->name('store');
            Route::get('/{beritaCategory}/edit', [BeritaCategoryController::class, 'edit'])->name('edit');
            Route::put('/{beritaCategory}', [BeritaCategoryController::class, 'update'])->name('update');
            Route::delete('/{beritaCategory}', [BeritaCategoryController::class, 'destroy'])->name('destroy');
        });
    Route::prefix('kategori-ebook')
        ->name('ebook-categories.')
        ->group(function () {
            Route::get('/', [EbookCategoryController::class, 'index'])->name('index');
            Route::get('/create', [EbookCategoryController::class, 'create'])->name('create');
            Route::post('/', [EbookCategoryController::class, 'store'])->name('store');
            Route::get('/{ebookCategory}/edit', [EbookCategoryController::class, 'edit'])->name('edit');
            Route::put('/{ebookCategory}', [EbookCategoryController::class, 'update'])->name('update');
            Route::delete('/{ebookCategory}', [EbookCategoryController::class, 'destroy'])->name('destroy');
        });
    Route::prefix('penghargaan')
        ->name('penghargaan.')
        ->group(function () {
            Route::get('/', [PenghargaanController::class, 'index'])->name('index');
            Route::get('/create', [PenghargaanController::class, 'create'])->name('create');
            Route::post('/', [PenghargaanController::class, 'store'])->name('store');
            Route::get('/{penghargaan}/edit', [PenghargaanController::class, 'edit'])->name('edit');
            Route::put('/{penghargaan}', [PenghargaanController::class, 'update'])->name('update');
            Route::delete('/{penghargaan}', [PenghargaanController::class, 'destroy'])->name('destroy');
        });
    Route::prefix('legalitas')
        ->name('legalitas.')
        ->group(function () {
            Route::get('/', [LegalitasController::class, 'index'])->name('index');
            Route::get('/create', [LegalitasController::class, 'create'])->name('create');
            Route::post('/', [LegalitasController::class, 'store'])->name('store');
            Route::get('/{legalitas}/edit', [LegalitasController::class, 'edit'])->name('edit');
            Route::put('/{legalitas}', [LegalitasController::class, 'update'])->name('update');
            Route::delete('/{legalitas}', [LegalitasController::class, 'destroy'])->name('destroy');
        });
    Route::prefix('profil-perusahaan')
        ->name('company-profile.')
        ->group(function () {
            Route::get('/', [CompanyProfileController::class, 'show'])->name('show');
            Route::put('/', [CompanyProfileController::class, 'update'])->name('update');
        });
    Route::prefix('syarat-dan-ketentuan')
        ->name('terms-and-conditions.')
        ->group(function () {
            Route::get('/', [TermsAndConditionsController::class, 'show'])->name('show');
            Route::put('/', [TermsAndConditionsController::class, 'update'])->name('update');
        });
    Route::prefix('kebijakan-privasi')
        ->name('privacy-policy.')
        ->group(function () {
            Route::get('/', [PrivacyPolicyController::class, 'show'])->name('show');
            Route::put('/', [PrivacyPolicyController::class, 'update'])->name('update');
        });
    Route::prefix('user-management')
        ->name('user-management.')
        ->middleware('can:manage-user-management')
        ->group(function () {
            Route::get('/', [UserManagementController::class, 'index'])->name('index');
            Route::get('/create', [UserManagementController::class, 'create'])->name('create');
            Route::post('/', [UserManagementController::class, 'store'])->name('store');
            Route::get('/{user}/edit', [UserManagementController::class, 'edit'])->name('edit');
            Route::put('/{user}', [UserManagementController::class, 'update'])->name('update');
            Route::delete('/{user}', [UserManagementController::class, 'destroy'])->name('destroy');
        });
    Route::post('/tinymce/images', TinyMceImageController::class)->name('tinymce.images.store');
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});

require __DIR__.'/auth.php';
