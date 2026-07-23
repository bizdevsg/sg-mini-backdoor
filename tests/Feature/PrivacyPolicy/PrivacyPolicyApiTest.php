<?php

use App\Models\PrivacyPolicy;
use App\Support\ApiJsonCacheService;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;

uses(LazilyRefreshDatabase::class);

test('privacy policy api returns singleton document', function () {
    $policy = PrivacyPolicy::query()->create([
        'content' => '<p>Dokumen privasi utama.</p>',
        'content_en' => '<p>Main privacy document.</p>',
    ]);

    app(ApiJsonCacheService::class)->refreshPrivacyPolicy();

    $this->getJson('/api/v1/privacy-policy')
        ->assertSuccessful()
        ->assertJsonPath('data.id', $policy->id)
        ->assertJsonPath('data.content', '<p>Dokumen privasi utama.</p>')
        ->assertJsonPath('data.content_en', '<p>Main privacy document.</p>');
});

test('privacy policy api returns not found when document is unavailable', function () {
    $this->getJson('/api/v1/privacy-policy')
        ->assertNotFound();
});
