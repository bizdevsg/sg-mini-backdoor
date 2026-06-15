<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\InformasiController;
use App\Http\Controllers\PenghargaanController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\UserManagementController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware('auth')->group(function () {
    Route::view('/dashboard', 'dashboard.beranda')->name('dashboard');
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
    Route::prefix('user-management')
        ->name('user-management.')
        ->group(function () {
            Route::get('/', [UserManagementController::class, 'index'])->name('index');
        });
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});

require __DIR__ . '/auth.php';
