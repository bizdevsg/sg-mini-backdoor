<?php

use App\Http\Controllers\Api\InformasiApiController;
use App\Http\Controllers\Api\PenghargaanApiController;
use App\Http\Controllers\Api\ProdukApiController;
use Illuminate\Support\Facades\Route;

Route::prefix('produk/{section}')
    ->whereIn('section', ['spa', 'jfx'])
    ->group(function () {
        Route::get('/', [ProdukApiController::class, 'index']);
        Route::get('/{slug}', [ProdukApiController::class, 'show']);
    });

Route::prefix('pengumuman')->group(function () {
    Route::get('/', [InformasiApiController::class, 'index']);
    Route::get('/{slug}', [InformasiApiController::class, 'show']);
});

Route::prefix('penghargaan')->group(function () {
    Route::get('/', [PenghargaanApiController::class, 'index']);
    Route::get('/{slug}', [PenghargaanApiController::class, 'show']);
});
