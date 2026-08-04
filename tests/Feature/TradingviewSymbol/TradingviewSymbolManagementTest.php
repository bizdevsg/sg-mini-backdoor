<?php

use App\Models\TradingviewSymbol;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;

uses(LazilyRefreshDatabase::class);

test('guests are redirected to login when accessing tradingview symbol pages', function () {
    $symbol = TradingviewSymbol::factory()->create();

    $this->get(route('tradingview.index'))->assertRedirect(route('login'));
    $this->get(route('tradingview.create'))->assertRedirect(route('login'));
    $this->get(route('tradingview.edit', $symbol))->assertRedirect(route('login'));
});

test('regular admins cannot access tradingview symbol pages', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('tradingview.index'))
        ->assertForbidden();
});

test('superadmin can access tradingview symbol pages', function () {
    $user = User::factory()->superadmin()->create();
    $symbol = TradingviewSymbol::factory()->create(['name' => 'Emas Spot']);

    $this->actingAs($user);

    $this->get(route('tradingview.index'))
        ->assertSuccessful()
        ->assertSee('Kode TradingView')
        ->assertSee('Emas Spot');

    $this->get(route('tradingview.create'))
        ->assertSuccessful()
        ->assertSee('Tambah Kode TradingView');

    $this->get(route('tradingview.edit', $symbol))
        ->assertSuccessful()
        ->assertSee($symbol->symbol_ws);
});

test('superadmin can create update and delete tradingview symbols', function () {
    $user = User::factory()->superadmin()->create();

    $this->actingAs($user)
        ->post(route('tradingview.store'), [
            'name' => 'Emas Spot',
            'symbol_ws' => 'XAUUSD',
            'symbol_tv' => 'OANDA:XAUUSD',
        ])
        ->assertRedirect(route('tradingview.index'));

    $symbol = TradingviewSymbol::query()->firstOrFail();

    expect($symbol->name)->toBe('Emas Spot')
        ->and($symbol->symbol_ws)->toBe('XAUUSD')
        ->and($symbol->symbol_tv)->toBe('OANDA:XAUUSD');

    $this->actingAs($user)
        ->put(route('tradingview.update', $symbol), [
            'name' => 'Emas Spot Updated',
            'symbol_ws' => 'XAUUSD',
            'symbol_tv' => 'FOREXCOM:XAUUSD',
        ])
        ->assertRedirect(route('tradingview.index'));

    $symbol->refresh();

    expect($symbol->name)->toBe('Emas Spot Updated')
        ->and($symbol->symbol_tv)->toBe('FOREXCOM:XAUUSD');

    $this->actingAs($user)
        ->delete(route('tradingview.destroy', $symbol))
        ->assertRedirect(route('tradingview.index'));

    $this->assertModelMissing($symbol);
});

test('creating a tradingview symbol requires unique symbol_ws', function () {
    $user = User::factory()->superadmin()->create();
    $existing = TradingviewSymbol::factory()->create(['symbol_ws' => 'XAUUSD']);

    $this->actingAs($user)
        ->post(route('tradingview.store'), [
            'name' => 'Duplikat',
            'symbol_ws' => $existing->symbol_ws,
            'symbol_tv' => 'OANDA:XAUUSD',
        ])
        ->assertInvalid(['symbol_ws']);
});

test('create and edit forms render a save confirmation prompt', function () {
    $user = User::factory()->superadmin()->create();
    $symbol = TradingviewSymbol::factory()->create();

    $this->actingAs($user);

    $this->get(route('tradingview.create'))
        ->assertSuccessful()
        ->assertSee('data-confirm-submit', false)
        ->assertSee('Simpan kode TradingView baru?');

    $this->get(route('tradingview.edit', $symbol))
        ->assertSuccessful()
        ->assertSee('data-confirm-submit', false)
        ->assertSee('Simpan perubahan kode ini?');
});
