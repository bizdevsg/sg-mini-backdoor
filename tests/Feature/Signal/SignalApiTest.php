<?php

use App\Models\Signal;
use App\Models\SignalCategory;
use App\Support\ApiJsonCacheService;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Carbon;

uses(LazilyRefreshDatabase::class);

test('signal timeframe expiry mapping follows configured history windows', function (string $timeframe, int $expirationMinutes) {
    Carbon::setTestNow(Carbon::parse('2026-08-11 12:00:00'));

    $signal = new Signal([
        'timeframe' => $timeframe,
    ]);
    $signal->created_at = Carbon::parse('2026-08-11 08:00:00');

    expect($signal->expirationMinutes())->toBe($expirationMinutes)
        ->and($signal->signalExpiresAt()?->toIso8601String())
        ->toBe(
            Carbon::parse('2026-08-11 08:00:00')
                ->addMinutes($expirationMinutes)
                ->toIso8601String()
        );

    Carbon::setTestNow();
})->with([
    '1M expires in 15 minutes' => ['1M', 15],
    '5M expires in 1 hour' => ['5M', 60],
    '15M expires in 3 hours' => ['15M', 180],
    '30M expires in 4 hours' => ['30M', 240],
    '1H expires in 5 hours' => ['1H', 300],
    '4H expires in 18 hours' => ['4H', 1080],
    '1D expires in 1 day' => ['1D', 1440],
    '1W expires in 4 days' => ['1W', 5760],
]);

test('signal api returns expiry metadata for list detail and category detail endpoints', function () {
    Carbon::setTestNow(Carbon::parse('2026-08-11 12:00:00'));

    $category = SignalCategory::query()->create([
        'name' => 'Gold',
        'slug' => 'gold',
    ]);

    $expiredSignal = Signal::query()->create([
        'category_id' => $category->id,
        'slug' => 'gold-buy-15m',
        'potensi' => 'buy',
        'timeframe' => '15M',
        'confident' => 'High',
        'entry' => '3320',
        'taking_profit' => '3340',
        'stop_loss' => '3300',
        'sumber' => 'Desk analysis',
    ]);
    $expiredSignal->forceFill([
        'created_at' => Carbon::parse('2026-08-11 08:00:00'),
        'updated_at' => Carbon::parse('2026-08-11 08:00:00'),
    ])->saveQuietly();

    $activeSignal = Signal::query()->create([
        'category_id' => $category->id,
        'slug' => 'gold-sell-1d',
        'potensi' => 'sell',
        'timeframe' => '1D',
        'confident' => 'Medium',
        'entry' => '3310',
        'taking_profit' => '3280',
        'stop_loss' => '3335',
        'sumber' => 'Desk analysis',
    ]);
    $activeSignal->forceFill([
        'created_at' => Carbon::parse('2026-08-11 10:00:00'),
        'updated_at' => Carbon::parse('2026-08-11 10:00:00'),
    ])->saveQuietly();

    app(ApiJsonCacheService::class)->refreshSignal();
    app(ApiJsonCacheService::class)->refreshSignalCategories();

    $this->getJson('/api/v1/signal', apiKeyHeaders())
        ->assertSuccessful()
        ->assertJsonPath('meta.total', 2)
        ->assertJsonPath('data.0.id', $activeSignal->id)
        ->assertJsonPath('data.0.expires_at', '2026-08-12T10:00:00+07:00')
        ->assertJsonPath('data.0.remaining_seconds', 79200)
        ->assertJsonPath('data.0.is_expired', false)
        ->assertJsonPath('data.1.id', $expiredSignal->id)
        ->assertJsonPath('data.1.expires_at', '2026-08-11T11:00:00+07:00')
        ->assertJsonPath('data.1.remaining_seconds', 0)
        ->assertJsonPath('data.1.is_expired', true);

    $this->getJson('/api/v1/signal/'.$expiredSignal->id, apiKeyHeaders())
        ->assertSuccessful()
        ->assertJsonPath('data.id', $expiredSignal->id)
        ->assertJsonPath('data.expires_at', '2026-08-11T11:00:00+07:00')
        ->assertJsonPath('data.remaining_seconds', 0)
        ->assertJsonPath('data.is_expired', true);

    $this->getJson('/api/v1/signal/categories/'.$category->slug.'/detail', apiKeyHeaders())
        ->assertSuccessful()
        ->assertJsonPath('data.slug', $category->slug)
        ->assertJsonPath('data.signals.0.id', $activeSignal->id)
        ->assertJsonPath('data.signals.0.expires_at', '2026-08-12T10:00:00+07:00')
        ->assertJsonPath('data.signals.0.is_expired', false)
        ->assertJsonPath('data.signals.1.id', $expiredSignal->id)
        ->assertJsonPath('data.signals.1.expires_at', '2026-08-11T11:00:00+07:00')
        ->assertJsonPath('data.signals.1.is_expired', true);

    Carbon::setTestNow();
});
