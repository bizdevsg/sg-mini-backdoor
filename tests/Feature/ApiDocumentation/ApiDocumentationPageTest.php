<?php

use App\Models\SystemActivityLog;
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

test('superadmin can download printable api documentation page after providing purpose and recipient', function () {
    $user = User::factory()->superadmin()->create();

    $this->actingAs($user)
        ->post(route('api-documentation.pdf'), [
            'purpose' => 'Integrasi API untuk aplikasi mobile versi 2.0',
            'recipient' => 'Tim Mobile - mobile@partner.test',
        ])
        ->assertSuccessful()
        ->assertSee('Download PDF')
        ->assertSee('Dokumentasi API Lengkap')
        ->assertSee('Getting Started')
        ->assertSee('Authentication')
        ->assertSee('Endpoints')
        ->assertSee('Query Params')
        ->assertSee('cURL Examples')
        ->assertSee('window.print()')
        ->assertSee('Info Distribusi Dokumen')
        ->assertSee('Integrasi API untuk aplikasi mobile versi 2.0')
        ->assertSee('Tim Mobile - mobile@partner.test')
        ->assertSee($user->name);
});

test('downloading printable api documentation requires purpose and recipient', function () {
    $user = User::factory()->superadmin()->create();

    $this->actingAs($user)
        ->post(route('api-documentation.pdf'), [])
        ->assertInvalid(['purpose', 'recipient']);
});

test('downloading printable api documentation records a trackable system activity log', function () {
    $user = User::factory()->superadmin()->create();

    $this->actingAs($user)
        ->post(route('api-documentation.pdf'), [
            'purpose' => 'Audit keamanan endpoint signal',
            'recipient' => 'Konsultan Keamanan - security@vendor.test',
        ])
        ->assertSuccessful();

    $log = SystemActivityLog::query()->where('subject', 'api-documentation')->first();

    expect($log)->not->toBeNull()
        ->and($log->category)->toBe('data')
        ->and($log->event)->toBe('data_download')
        ->and($log->user_id)->toBe($user->id)
        ->and($log->context['purpose'])->toBe('Audit keamanan endpoint signal')
        ->and($log->context['recipient'])->toBe('Konsultan Keamanan - security@vendor.test');
});

test('admin users cannot access api documentation page', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('api-documentation.section', ['section' => 'getting-started']))
        ->assertForbidden();
});
