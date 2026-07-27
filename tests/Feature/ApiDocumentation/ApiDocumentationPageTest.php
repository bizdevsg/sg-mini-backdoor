<?php

use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;

uses(LazilyRefreshDatabase::class);

test('guests are redirected to login when accessing api documentation page', function () {
    $this->get(route('api-documentation.show'))
        ->assertRedirect(route('login'));
});

test('superadmin can access api documentation page', function () {
    config()->set('api-auth.header', 'X-API-Key');
    config()->set('api-auth.key', 'super-secret-api-key');

    $user = User::factory()->superadmin()->create();

    $this->actingAs($user)
        ->get(route('api-documentation.show'))
        ->assertSuccessful()
        ->assertSee('Dokumentasi API')
        ->assertSee('X-API-Key')
        ->assertSee('super-secret-api-key')
        ->assertSee('/banner')
        ->assertSee('/privacy-policy');
});

test('admin users cannot access api documentation page', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('api-documentation.show'))
        ->assertForbidden();
});
