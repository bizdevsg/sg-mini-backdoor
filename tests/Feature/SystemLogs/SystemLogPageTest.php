<?php

use App\Models\SystemActivityLog;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;

uses(LazilyRefreshDatabase::class);

test('guests are redirected to login when accessing system log pages', function () {
    $this->get(route('system-logs.show', ['category' => 'login']))
        ->assertRedirect(route('login'));
});

test('superadmin can access login api and data log pages', function () {
    $user = User::factory()->superadmin()->create();

    SystemActivityLog::query()->create([
        'user_id' => $user->id,
        'category' => 'api',
        'subject' => 'banner',
        'event' => 'api_request',
        'description' => 'GET Banner API diakses.',
        'context' => [
            'module' => 'banner',
            'method' => 'GET',
            'path' => '/api/v1/banner',
            'status_code' => 200,
            'duration_ms' => 12,
        ],
    ]);

    SystemActivityLog::query()->create([
        'user_id' => $user->id,
        'category' => 'data',
        'subject' => 'client-area',
        'event' => 'client_area_toggle',
        'description' => 'Client Area Development diaktifkan.',
        'context' => [
            'module' => 'client-area',
            'target' => 'dev',
            'previous_status' => false,
            'new_status' => true,
        ],
    ]);

    $this->actingAs($user)
        ->get(route('system-logs.show', ['category' => 'login']))
        ->assertSuccessful()
        ->assertSee('System Log')
        ->assertSee('Login');

    $this->actingAs($user)
        ->get(route('system-logs.show', ['category' => 'api']))
        ->assertSuccessful()
        ->assertSee('API')
        ->assertSee('Banner')
        ->assertSee('/api/v1/banner');

    $this->actingAs($user)
        ->get(route('system-logs.show', ['category' => 'data']))
        ->assertSuccessful()
        ->assertSee('Data')
        ->assertSee('Client Area Development diaktifkan.')
        ->assertSee('System Settings');
});

test('superadmin can filter api logs by module category', function () {
    $user = User::factory()->superadmin()->create();

    SystemActivityLog::query()->create([
        'user_id' => $user->id,
        'category' => 'api',
        'subject' => 'banner',
        'event' => 'api_request',
        'description' => 'GET Banner API diakses.',
        'context' => [
            'module' => 'banner',
            'method' => 'GET',
            'path' => '/api/v1/banner',
            'status_code' => 200,
            'duration_ms' => 10,
        ],
    ]);

    SystemActivityLog::query()->create([
        'user_id' => $user->id,
        'category' => 'api',
        'subject' => 'privacy-policy',
        'event' => 'api_request',
        'description' => 'GET Kebijakan Privasi API diakses.',
        'context' => [
            'module' => 'privacy-policy',
            'method' => 'GET',
            'path' => '/api/v1/privacy-policy',
            'status_code' => 200,
            'duration_ms' => 15,
        ],
    ]);

    $this->actingAs($user)
        ->get(route('system-logs.show', ['category' => 'api', 'module' => 'banner']))
        ->assertSuccessful()
        ->assertSee('/api/v1/banner')
        ->assertDontSee('/api/v1/privacy-policy');
});

test('admin users cannot access system log pages', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('system-logs.show', ['category' => 'login']))
        ->assertForbidden();
});

test('superadmin can filter data logs by module category', function () {
    $user = User::factory()->superadmin()->create();

    SystemActivityLog::query()->create([
        'user_id' => $user->id,
        'category' => 'data',
        'subject' => 'banner',
        'event' => 'data_create',
        'description' => 'Aksi tambah pada Banner dari backdoor.',
        'context' => [
            'module' => 'banner',
            'action' => 'create',
            'action_label' => 'tambah',
            'path' => '/banner',
            'status_code' => 302,
        ],
    ]);

    SystemActivityLog::query()->create([
        'user_id' => $user->id,
        'category' => 'data',
        'subject' => 'user-management',
        'event' => 'data_update',
        'description' => 'Aksi ubah pada User Management dari backdoor.',
        'context' => [
            'module' => 'user-management',
            'action' => 'update',
            'action_label' => 'ubah',
            'path' => '/user-management/1',
            'status_code' => 302,
        ],
    ]);

    $this->actingAs($user)
        ->get(route('system-logs.show', ['category' => 'data', 'module' => 'banner']))
        ->assertSuccessful()
        ->assertSee('/banner')
        ->assertDontSee('/user-management/1');
});
