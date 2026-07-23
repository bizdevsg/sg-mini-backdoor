<?php

use App\Models\TermsAndCondition;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;

uses(LazilyRefreshDatabase::class);

test('guests are redirected to login when accessing terms and conditions page', function () {
    $this->get(route('terms-and-conditions.show'))
        ->assertRedirect(route('login'));
});

test('authenticated users can access terms and conditions page', function () {
    $user = User::factory()->create();
    TermsAndCondition::query()->create([
        'content' => '<p>Syarat dan ketentuan awal.</p>',
        'content_en' => '<p>Initial terms and conditions.</p>',
    ]);

    $this->actingAs($user)
        ->get(route('terms-and-conditions.show'))
        ->assertSuccessful()
        ->assertSee('Syarat dan Ketentuan')
        ->assertSee('Singleton record')
        ->assertSee('Syarat dan ketentuan awal.', false)
        ->assertSee('Initial terms and conditions.', false);
});

test('authenticated users can update terms and conditions content', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->put(route('terms-and-conditions.update'), [
            'content' => '<p>Dokumen syarat baru.</p><ul><li>Poin penting</li></ul>',
            'content_en' => '<p>Updated terms document.</p><ul><li>Important point</li></ul>',
        ])
        ->assertRedirect(route('terms-and-conditions.show'));

    $terms = TermsAndCondition::query()->firstOrFail();

    expect($terms->content)->toContain('Dokumen syarat baru.')
        ->and($terms->content)->toContain('Poin penting')
        ->and($terms->content_en)->toContain('Updated terms document.')
        ->and($terms->content_en)->toContain('Important point');
});

test('dashboard shows terms and conditions navigation', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertSuccessful()
        ->assertSee('Syarat dan Ketentuan');
});
