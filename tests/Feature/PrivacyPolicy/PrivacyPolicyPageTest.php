<?php

use App\Models\PrivacyPolicy;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;

uses(LazilyRefreshDatabase::class);

test('guests are redirected to login when accessing privacy policy page', function () {
    $this->get(route('privacy-policy.show'))
        ->assertRedirect(route('login'));
});

test('authenticated users can access privacy policy page', function () {
    $user = User::factory()->create();
    PrivacyPolicy::query()->create([
        'content' => '<p>Kebijakan privasi awal.</p>',
        'content_en' => '<p>Initial privacy policy.</p>',
    ]);

    $this->actingAs($user)
        ->get(route('privacy-policy.show'))
        ->assertSuccessful()
        ->assertSee('Kebijakan Privasi')
        ->assertSee('Singleton record')
        ->assertSee('Kebijakan privasi awal.', false)
        ->assertSee('Initial privacy policy.', false);
});

test('authenticated users can update privacy policy content', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->put(route('privacy-policy.update'), [
            'content' => '<p>Dokumen privasi baru.</p><ul><li>Data pengguna</li></ul>',
            'content_en' => '<p>Updated privacy document.</p><ul><li>User data</li></ul>',
        ])
        ->assertRedirect(route('privacy-policy.show'));

    $policy = PrivacyPolicy::query()->firstOrFail();

    expect($policy->content)->toContain('Dokumen privasi baru.')
        ->and($policy->content)->toContain('Data pengguna')
        ->and($policy->content_en)->toContain('Updated privacy document.')
        ->and($policy->content_en)->toContain('User data');
});

test('dashboard shows privacy policy navigation', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertSuccessful()
        ->assertSee('Kebijakan Privasi');
});
