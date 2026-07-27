<?php

use App\Models\ClientAreaSetting;
use App\Models\SystemActivityLog;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;

uses(LazilyRefreshDatabase::class);

test('guests are redirected to login when accessing client area setting page', function () {
    $this->get(route('client-area.show'))
        ->assertRedirect(route('login'));
});

test('superadmin can access client area setting page', function () {
    $user = User::factory()->superadmin()->create();

    $this->actingAs($user)
        ->get(route('client-area.show'))
        ->assertSuccessful()
        ->assertSee('Client Area')
        ->assertSee('Development')
        ->assertSee('Production')
        ->assertDontSee('System Log');
});

test('superadmin can toggle client area statuses independently', function () {
    $user = User::factory()->superadmin()->create();

    $this->actingAs($user)
        ->put(route('client-area.update'), [
            'target' => 'dev',
            'enabled' => true,
        ])
        ->assertRedirect(route('client-area.show'));

    $this->actingAs($user)
        ->put(route('client-area.update'), [
            'target' => 'prod',
            'enabled' => false,
        ])
        ->assertRedirect(route('client-area.show'));

    $this->actingAs($user)
        ->get(route('client-area.show'))
        ->assertSuccessful()
        ->assertSee('Client Area Aktif')
        ->assertSee('Client Area Nonaktif');

    $settings = ClientAreaSetting::query()->firstOrFail();
    $log = SystemActivityLog::query()->where('category', 'data')->latest()->first();

    expect($settings->client_area_dev)->toBeTrue()
        ->and($settings->client_area_prod)->toBeFalse()
        ->and($log?->event)->toBe('client_area_toggle')
        ->and($log?->context['target'] ?? null)->toBe('prod');
});

test('admin users cannot access client area setting page', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('client-area.show'))
        ->assertForbidden();
});
