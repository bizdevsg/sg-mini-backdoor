<?php

use App\Enums\UserRole;
use App\Models\SystemActivityLog;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;

uses(LazilyRefreshDatabase::class);

test('users default to admin role enum', function () {
    $user = User::factory()->create();

    expect($user->role)
        ->toBe(UserRole::Admin)
        ->and($user->isAdmin())->toBeTrue()
        ->and($user->isSuperadmin())->toBeFalse();
});

test('user management displays admin and superadmin roles', function () {
    $viewer = User::factory()->superadmin()->create();
    $admin = User::factory()->create([
        'name' => 'Admin Operasional',
        'email' => 'admin@example.com',
        'role' => UserRole::Admin,
    ]);
    $superadmin = User::factory()->superadmin()->create([
        'name' => 'Superadmin Utama',
        'email' => 'superadmin@example.com',
    ]);

    $this->actingAs($viewer)
        ->get(route('user-management.index'))
        ->assertSuccessful()
        ->assertSee($admin->name)
        ->assertSee($superadmin->name)
        ->assertSee('Admin')
        ->assertSee('Superadmin');
});

test('user management can be filtered by role', function () {
    $viewer = User::factory()->superadmin()->create();
    $admin = User::factory()->create([
        'name' => 'Admin Operasional',
        'email' => 'admin@example.com',
    ]);
    $superadmin = User::factory()->superadmin()->create([
        'name' => 'Superadmin Utama',
        'email' => 'superadmin@example.com',
    ]);

    $this->actingAs($viewer)
        ->get(route('user-management.index', ['role' => UserRole::Superadmin->value]))
        ->assertSuccessful()
        ->assertSee($superadmin->name)
        ->assertDontSee($admin->email);
});

test('admin dashboard does not show user management navigation', function () {
    $viewer = User::factory()->create([
        'email' => 'admin@example.com',
    ]);

    $this->actingAs($viewer)
        ->get(route('dashboard'))
        ->assertSuccessful()
        ->assertDontSee('User Management')
        ->assertDontSee('Kelola User')
        ->assertDontSee('Monitoring Log');
});

test('user management mutations are tracked in data logs', function () {
    $viewer = User::factory()->superadmin()->create();

    $this->actingAs($viewer)
        ->post(route('user-management.store'), [
            'name' => 'Audit User',
            'email' => 'audit-user@example.com',
            'role' => UserRole::Admin->value,
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ])
        ->assertRedirect(route('user-management.index'));

    $log = SystemActivityLog::query()
        ->where('category', 'data')
        ->latest()
        ->first();

    expect($log)->not->toBeNull()
        ->and($log?->subject)->toBe('user-management')
        ->and($log?->event)->toBe('data_create')
        ->and($log?->context['route_name'] ?? null)->toBe('user-management.store')
        ->and($log?->context['action'] ?? null)->toBe('create');
});
