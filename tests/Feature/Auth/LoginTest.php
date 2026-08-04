<?php

use App\Models\SystemActivityLog;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\DB;

uses(LazilyRefreshDatabase::class);

test('guests are redirected to the login page when visiting the dashboard', function () {
    $this->get(route('dashboard'))
        ->assertRedirect(route('login'));
});

test('users can log in with valid credentials', function () {
    $user = User::factory()->create([
        'password' => 'password',
    ]);

    $this->from(route('login'))
        ->post(route('login.store'), [
            'email' => $user->email,
            'password' => 'password',
        ])
        ->assertRedirect(route('dashboard'));

    $this->assertAuthenticatedAs($user);

    expect(SystemActivityLog::query()->where('category', 'login')->count())
        ->toBe(1);
});

test('logging in terminates other active sessions for the same account', function () {
    config()->set('session.driver', 'database');

    $user = User::factory()->create([
        'password' => 'password',
    ]);

    DB::table('sessions')->insert([
        'id' => 'stale-session-id-for-test',
        'user_id' => $user->id,
        'ip_address' => '127.0.0.1',
        'user_agent' => 'PHPUnit',
        'payload' => base64_encode(serialize([])),
        'last_activity' => time(),
    ]);

    $this->from(route('login'))
        ->post(route('login.store'), [
            'email' => $user->email,
            'password' => 'password',
        ])
        ->assertRedirect(route('dashboard'));

    expect(DB::table('sessions')->where('id', 'stale-session-id-for-test')->exists())->toBeFalse();

    expect(SystemActivityLog::query()->where('event', 'login_other_sessions_terminated')->count())
        ->toBe(1);
});

test('users cannot log in with invalid credentials', function () {
    $user = User::factory()->create([
        'password' => 'password',
    ]);

    $this->from(route('login'))
        ->post(route('login.store'), [
            'email' => $user->email,
            'password' => 'wrong-password',
        ])
        ->assertRedirect(route('login'))
        ->assertSessionHasErrors('email');

    $this->assertGuest();
});
