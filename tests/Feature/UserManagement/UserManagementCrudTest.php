<?php

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;

uses(LazilyRefreshDatabase::class);

test('authenticated users can view create and edit user management pages', function () {
    $viewer = User::factory()->superadmin()->create();
    $managedUser = User::factory()->create();

    $this->actingAs($viewer)
        ->get(route('user-management.create'))
        ->assertSuccessful()
        ->assertSee('Tambah user');

    $this->actingAs($viewer)
        ->get(route('user-management.edit', $managedUser))
        ->assertSuccessful()
        ->assertSee($managedUser->name);
});

test('authenticated users can create and update users', function () {
    $viewer = User::factory()->superadmin()->create();

    $this->actingAs($viewer)
        ->post(route('user-management.store'), [
            'name' => 'Admin Baru',
            'email' => 'adminbaru@example.com',
            'role' => UserRole::Admin->value,
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ])
        ->assertRedirect(route('user-management.index'));

    $managedUser = User::query()->where('email', 'adminbaru@example.com')->firstOrFail();

    expect($managedUser->name)->toBe('Admin Baru')
        ->and($managedUser->role)->toBe(UserRole::Admin);

    $this->actingAs($viewer)
        ->put(route('user-management.update', $managedUser), [
            'name' => 'Admin Update',
            'email' => 'adminupdate@example.com',
            'role' => UserRole::Superadmin->value,
            'password' => '',
            'password_confirmation' => '',
        ])
        ->assertRedirect(route('user-management.index'));

    $managedUser->refresh();

    expect($managedUser->name)->toBe('Admin Update')
        ->and($managedUser->email)->toBe('adminupdate@example.com')
        ->and($managedUser->role)->toBe(UserRole::Superadmin);
});

test('authenticated users can delete other users', function () {
    $viewer = User::factory()->superadmin()->create([
        'email' => 'viewer@example.com',
    ]);
    $managedUser = User::factory()->create([
        'email' => 'managed@example.com',
    ]);

    $this->actingAs($viewer)
        ->delete(route('user-management.destroy', $managedUser))
        ->assertRedirect(route('user-management.index'));

    $this->assertModelMissing($managedUser);
});

test('current user cannot delete their own account', function () {
    $viewer = User::factory()->superadmin()->create([
        'email' => 'superadmin@example.com',
    ]);

    $this->actingAs($viewer)
        ->delete(route('user-management.destroy', $viewer))
        ->assertRedirect(route('user-management.index'));

    $this->assertModelExists($viewer);
});
