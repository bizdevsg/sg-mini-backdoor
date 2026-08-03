<?php

use App\Models\TradingviewSymbol;
use App\Support\ApiJsonCacheService;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;

uses(LazilyRefreshDatabase::class);

test('tradingview symbol api lists all symbols', function () {
    TradingviewSymbol::factory()->create(['name' => 'Emas Spot', 'symbol_ws' => 'XAUUSD']);
    TradingviewSymbol::factory()->create(['name' => 'Bitcoin', 'symbol_ws' => 'BTCUSD']);

    app(ApiJsonCacheService::class)->refreshTradingviewSymbol();

    $this->getJson('/api/v1/tradingview-symbol', apiKeyHeaders())
        ->assertSuccessful()
        ->assertJsonPath('meta.total', 2);
});

test('tradingview symbol api can search by name or symbol', function () {
    TradingviewSymbol::factory()->create(['name' => 'Emas Spot', 'symbol_ws' => 'XAUUSD']);
    TradingviewSymbol::factory()->create(['name' => 'Bitcoin', 'symbol_ws' => 'BTCUSD']);

    app(ApiJsonCacheService::class)->refreshTradingviewSymbol();

    $this->getJson('/api/v1/tradingview-symbol?search=XAUUSD', apiKeyHeaders())
        ->assertSuccessful()
        ->assertJsonPath('meta.total', 1)
        ->assertJsonPath('data.0.symbol_ws', 'XAUUSD');
});

test('tradingview symbol api can show a single symbol by symbol_ws', function () {
    $symbol = TradingviewSymbol::factory()->create([
        'name' => 'Emas Spot',
        'symbol_ws' => 'XAUUSD',
        'symbol_tv' => 'OANDA:XAUUSD',
    ]);

    app(ApiJsonCacheService::class)->refreshTradingviewSymbol();

    $this->getJson('/api/v1/tradingview-symbol/'.$symbol->symbol_ws, apiKeyHeaders())
        ->assertSuccessful()
        ->assertJsonPath('data.symbol_ws', 'XAUUSD')
        ->assertJsonPath('data.symbol_tv', 'OANDA:XAUUSD');
});

test('tradingview symbol api returns 404 for unknown symbol_ws', function () {
    app(ApiJsonCacheService::class)->refreshTradingviewSymbol();

    $this->getJson('/api/v1/tradingview-symbol/UNKNOWN', apiKeyHeaders())
        ->assertNotFound();
});

test('tradingview symbol api requires a valid api key', function () {
    TradingviewSymbol::factory()->create();

    app(ApiJsonCacheService::class)->refreshTradingviewSymbol();

    $this->getJson('/api/v1/tradingview-symbol')
        ->assertForbidden();
});
