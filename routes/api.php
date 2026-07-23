<?php

use App\Http\Controllers\Api\BannerApiController;
use App\Http\Controllers\Api\CompanyProfileController;
use App\Http\Controllers\Api\EbookApiController;
use App\Http\Controllers\Api\EbookCategoryApiController;
use App\Http\Controllers\Api\InformasiApiController;
use App\Http\Controllers\Api\LegalitasApiController;
use App\Http\Controllers\Api\MassageApiController;
use App\Http\Controllers\Api\PenghargaanApiController;
use App\Http\Controllers\Api\PrivacyPolicyApiController;
use App\Http\Controllers\Api\ProdukApiController;
use App\Http\Controllers\Api\TermsAndConditionsApiController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::prefix('banner')->group(function () {
        Route::get('/', [BannerApiController::class, 'index']);
        Route::get('/{slug}', [BannerApiController::class, 'show']);
    });

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

    Route::prefix('ebook')->group(function () {
        Route::get('/categories', [EbookCategoryApiController::class, 'index']);
        Route::get('/categories/{slug}', [EbookCategoryApiController::class, 'show']);
        Route::get('/categories/{slug}/detail', [EbookCategoryApiController::class, 'detail']);
        Route::get('/', [EbookApiController::class, 'index']);
        Route::get('/{slug}', [EbookApiController::class, 'show']);
    });

    Route::prefix('penghargaan')->group(function () {
        Route::get('/', [PenghargaanApiController::class, 'index']);
        Route::get('/{slug}', [PenghargaanApiController::class, 'show']);
    });

    Route::prefix('legalitas')->group(function () {
        Route::get('/', [LegalitasApiController::class, 'index']);
        Route::get('/{slug}', [LegalitasApiController::class, 'show']);
    });

    Route::prefix('company-profile')->group(function () {
        Route::get('/', [CompanyProfileController::class, 'show']);
    });

    Route::prefix('terms-and-conditions')->group(function () {
        Route::get('/', [TermsAndConditionsApiController::class, 'show']);
    });

    Route::prefix('privacy-policy')->group(function () {
        Route::get('/', [PrivacyPolicyApiController::class, 'show']);
    });

    Route::prefix('massages')->group(function () {
        Route::post('/', [MassageApiController::class, 'store'])
            ->middleware('throttle:contact-form');
    });
});
