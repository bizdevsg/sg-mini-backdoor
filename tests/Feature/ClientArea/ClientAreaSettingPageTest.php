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
        ->assertSee('System Settings')
        ->assertSee('Client Area Development')
        ->assertSee('Client Area Production')
        ->assertSee('Tawk.to Development')
        ->assertSee('Tawk.to Production')
        ->assertSee('API & Security')
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
        ->put(route('client-area.update'), [
            'target' => 'tawk_to_dev',
            'enabled' => true,
        ])
        ->assertRedirect(route('client-area.show'));

    $this->actingAs($user)
        ->put(route('client-area.update'), [
            'target' => 'tawk_to_prod',
            'enabled' => false,
        ])
        ->assertRedirect(route('client-area.show'));

    $this->actingAs($user)
        ->get(route('client-area.show'))
        ->assertSuccessful()
        ->assertSee('Client Area Aktif')
        ->assertSee('Client Area Nonaktif')
        ->assertSee('Tawk.to Aktif');

    $settings = ClientAreaSetting::query()->firstOrFail();
    $log = SystemActivityLog::query()->where('category', 'data')->latest()->first();

    expect($settings->client_area_dev)->toBeTrue()
        ->and($settings->client_area_prod)->toBeFalse()
        ->and($settings->tawk_to_dev)->toBeTrue()
        ->and($settings->tawk_to_prod)->toBeFalse()
        ->and($log?->event)->toBe('tawk_to_toggle')
        ->and($log?->context['target'] ?? null)->toBe('tawk_to_prod');
});

test('superadmin can update api security settings', function () {
    $user = User::factory()->superadmin()->create();

    $this->actingAs($user)
        ->put(route('client-area.update-api-security'), [
            'api_enabled' => true,
            'api_key_rotation_notice' => 'Rotasi API key dijadwalkan 15 Agustus 2026.',
            'allowed_origin_frontend' => "https://frontend-dev.test\nhttps://frontend-prod.test",
        ])
        ->assertRedirect(route('client-area.show'));

    $settings = ClientAreaSetting::query()->firstOrFail();
    $log = SystemActivityLog::query()->where('category', 'data')->latest()->first();

    expect($settings->api_enabled)->toBeTrue()
        ->and($settings->api_key_rotation_notice)->toBe('Rotasi API key dijadwalkan 15 Agustus 2026.')
        ->and($settings->allowed_origin_frontend)->toBe("https://frontend-dev.test\nhttps://frontend-prod.test")
        ->and($log?->event)->toBe('api_security_update');
});

test('admin users cannot access client area setting page', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('client-area.show'))
        ->assertForbidden();
});
