<?php

use App\Models\Signal;
use App\Models\SignalCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;

uses(LazilyRefreshDatabase::class);

test('authenticated users can edit and update signals through admin routes using uuid', function () {
    $user = User::factory()->create();
    $category = SignalCategory::query()->create([
        'name' => 'Gold',
        'slug' => 'gold',
    ]);
    $signal = Signal::query()->create([
        'category_id' => $category->id,
        'potensi' => 'buy',
        'timeframe' => '15M',
        'confident' => 'High',
        'entry' => '3320',
        'taking_profit' => '3340',
        'stop_loss' => '3300',
        'sumber' => 'Desk analysis',
    ]);

    $editUrl = route('signal.edit', [
        'signalCategory' => $category,
        'signal' => $signal->uuid,
    ]);

    expect($editUrl)->toContain('/signal/gold/'.$signal->uuid.'/edit')
        ->and($editUrl)->not->toContain('/signal/gold/'.$signal->id.'/edit');

    $this->actingAs($user)
        ->get($editUrl)
        ->assertSuccessful()
        ->assertSee('Edit Signal')
        ->assertSee($signal->uuid);

    $this->put(route('signal.update', [
        'signalCategory' => $category,
        'signal' => $signal->uuid,
    ]), [
        'category_id' => $category->id,
        'potensi' => 'sell',
        'timeframe' => '1H',
        'confident' => 'Medium',
        'entry' => '3310',
        'taking_profit' => '3280',
        'stop_loss' => '3335',
        'sumber' => 'Updated desk analysis',
    ])->assertRedirect(route('signal.index', $category));

    $signal->refresh();

    expect($signal->potensi)->toBe('sell')
        ->and($signal->timeframe)->toBe('1H')
        ->and($signal->sumber)->toBe('Updated desk analysis');

    $this->get('/signal/'.$category->slug.'/'.$signal->id.'/edit')
        ->assertNotFound();
});

test('confident is required when storing and updating signals', function () {
    $user = User::factory()->create();
    $category = SignalCategory::query()->create([
        'name' => 'Gold',
        'slug' => 'gold',
    ]);
    $signal = Signal::query()->create([
        'category_id' => $category->id,
        'potensi' => 'buy',
        'timeframe' => '15M',
        'confident' => 'High',
        'entry' => '3320',
        'taking_profit' => '3340',
        'stop_loss' => '3300',
        'sumber' => 'Desk analysis',
    ]);
    $payload = [
        'category_id' => $category->id,
        'potensi' => 'sell',
        'timeframe' => '1H',
        'confident' => '',
        'entry' => '3310',
        'taking_profit' => '3280',
        'stop_loss' => '3335',
        'sumber' => 'Updated desk analysis',
    ];

    $this->actingAs($user)
        ->post(route('signal.store', $category), $payload)
        ->assertSessionHasErrors('confident');

    $this->put(route('signal.update', [
        'signalCategory' => $category,
        'signal' => $signal->uuid,
    ]), $payload)->assertSessionHasErrors('confident');

    expect($signal->refresh()->confident)->toBe('High');
});

test('signal admin detail and delete routes resolve uuid within the selected category', function () {
    $user = User::factory()->create();
    $category = SignalCategory::query()->create([
        'name' => 'Gold',
        'slug' => 'gold',
    ]);
    $otherCategory = SignalCategory::query()->create([
        'name' => 'Forex',
        'slug' => 'forex',
    ]);
    $signal = Signal::query()->create([
        'category_id' => $category->id,
        'potensi' => 'buy',
        'timeframe' => '15M',
        'entry' => '3320',
        'taking_profit' => '3340',
        'stop_loss' => '3300',
        'sumber' => 'Desk analysis',
    ]);

    $this->actingAs($user)
        ->get(route('signal.show', [
            'signalCategory' => $category,
            'signal' => $signal->uuid,
        ]))
        ->assertSuccessful()
        ->assertSee($signal->uuid);

    $this->get(route('signal.show', [
        'signalCategory' => $otherCategory,
        'signal' => $signal->uuid,
    ]))->assertNotFound();

    $this->delete(route('signal.destroy', [
        'signalCategory' => $category,
        'signal' => $signal->uuid,
    ]))->assertRedirect(route('signal.index', $category));

    $this->assertModelMissing($signal);
});
