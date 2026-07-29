<?php

use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;

uses(LazilyRefreshDatabase::class);

test('guests are redirected to login when accessing api documentation page', function () {
    $this->get(route('api-documentation.show'))
        ->assertRedirect(route('login'));
});

test('api documentation root redirects to getting started page for superadmin', function () {
    $user = User::factory()->superadmin()->create();

    $this->actingAs($user)
        ->get(route('api-documentation.show'))
        ->assertRedirect(route('api-documentation.section', ['section' => 'getting-started']));
});

test('superadmin can access api documentation section page', function () {
    config()->set('api-auth.header', 'X-API-Key');
    config()->set('api-auth.key', 'super-secret-api-key');

    $user = User::factory()->superadmin()->create();

    $this->actingAs($user)
        ->get(route('api-documentation.section', ['section' => 'authentication']))
        ->assertSuccessful()
        ->assertSee('Authentication')
        ->assertSee('X-API-Key')
        ->assertSee('super-secret-api-key')
        ->assertSee('Website')
        ->assertSee('Mobile App');
});

test('superadmin can access endpoints documentation page', function () {
    $user = User::factory()->superadmin()->create();

    $this->actingAs($user)
        ->get(route('api-documentation.section', ['section' => 'endpoints']))
        ->assertSuccessful()
        ->assertSee('/banner')
        ->assertSee('/privacy-policy');
});

test('superadmin can access printable api documentation page', function () {
    $user = User::factory()->superadmin()->create();

    $this->actingAs($user)
        ->get(route('api-documentation.pdf'))
        ->assertSuccessful()
        ->assertSee('Download PDF')
        ->assertSee('Dokumentasi API Lengkap')
        ->assertSee('Getting Started')
        ->assertSee('Authentication')
        ->assertSee('Endpoints')
        ->assertSee('Query Params')
        ->assertSee('cURL Examples')
        ->assertSee('window.print()');
});

test('admin users cannot access api documentation page', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('api-documentation.section', ['section' => 'getting-started']))
        ->assertForbidden();
});
