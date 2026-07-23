<?php

use App\Models\TermsAndCondition;
use App\Support\ApiJsonCacheService;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;

uses(LazilyRefreshDatabase::class);

test('terms and conditions api returns singleton document', function () {
    $terms = TermsAndCondition::query()->create([
        'content' => '<p>Dokumen syarat utama.</p>',
        'content_en' => '<p>Main terms document.</p>',
    ]);

    app(ApiJsonCacheService::class)->refreshTermsAndConditions();

    $this->getJson('/api/v1/terms-and-conditions')
        ->assertSuccessful()
        ->assertJsonPath('data.id', $terms->id)
        ->assertJsonPath('data.content', '<p>Dokumen syarat utama.</p>')
        ->assertJsonPath('data.content_en', '<p>Main terms document.</p>');
});

test('terms and conditions api returns not found when document is unavailable', function () {
    $this->getJson('/api/v1/terms-and-conditions')
        ->assertNotFound();
});
