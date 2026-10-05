<?php

use App\Models\Banner;
use App\Models\CrudUndoAction;
use App\Models\Legalitas;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

uses(LazilyRefreshDatabase::class);

function makeUndoLegalitas(): Legalitas
{
    return Legalitas::query()->create([
        'title' => 'Izin Lama',
        'nomor' => '001/OLD',
        'description' => 'Deskripsi lama.',
        'slug' => 'izin-lama',
    ]);
}

test('an authenticated user can undo their own update once', function () {
    $user = User::factory()->create();
    $legalitas = makeUndoLegalitas();

    $response = $this->actingAs($user)->put(route('legalitas.update', $legalitas), [
        'title' => 'Izin Baru',
        'nomor' => '002/NEW',
        'description' => 'Deskripsi baru.',
    ]);

    $response->assertRedirect(route('legalitas.index'))
        ->assertSessionHas('undo_notification');

    $token = $response->getSession()->get('undo_notification')['token'];

    expect($legalitas->refresh()->title)->toBe('Izin Baru');

    $this->actingAs($user)
        ->post(route('crud-undo.perform'), ['token' => $token])
        ->assertRedirect(route('legalitas.index'))
        ->assertSessionHas('status', 'Perubahan berhasil dibatalkan.');

    expect($legalitas->refresh()->title)->toBe('Izin Lama')
        ->and($legalitas->nomor)->toBe('001/OLD');

    $this->actingAs($user)
        ->post(route('crud-undo.perform'), ['token' => $token])
        ->assertSessionHas('status_type', 'error');
});

test('delete undo restores the record and its private file backup', function () {
    Storage::fake('public');
    Storage::fake('local');

    $user = User::factory()->create();
    $banner = Banner::factory()->create([
        'image' => 'uploads/banner/original.webp',
    ]);
    Storage::disk('public')->put($banner->image, 'original-image');

    $response = $this->actingAs($user)
        ->delete(route('banner.destroy', $banner))
        ->assertRedirect(route('banner.index'))
        ->assertSessionHas('undo_notification');

    $token = $response->getSession()->get('undo_notification')['token'];

    $this->assertModelMissing($banner);
    Storage::disk('public')->assertMissing('uploads/banner/original.webp');

    $this->actingAs($user)
        ->post(route('crud-undo.perform'), ['token' => $token])
        ->assertRedirect(route('banner.index'));

    $restoredBanner = Banner::query()->find($banner->id);

    expect($restoredBanner)->not->toBeNull();
    Storage::disk('public')->assertExists('uploads/banner/original.webp');
    expect(Storage::disk('public')->get('uploads/banner/original.webp'))->toBe('original-image');
});

test('undo tokens are owner bound and expire with the notification', function () {
    Carbon::setTestNow('2026-10-05 12:00:00');

    $owner = User::factory()->create();
    $otherUser = User::factory()->create();
    $legalitas = makeUndoLegalitas();

    $response = $this->actingAs($owner)->delete(route('legalitas.destroy', $legalitas));
    $token = $response->getSession()->get('undo_notification')['token'];

    $this->actingAs($otherUser)
        ->post(route('crud-undo.perform'), ['token' => $token])
        ->assertSessionHas('status_type', 'error');

    Carbon::setTestNow(now()->addSeconds(11));

    $this->actingAs($owner)
        ->post(route('crud-undo.perform'), ['token' => $token])
        ->assertSessionHas('status', 'Waktu Undo sudah berakhir.')
        ->assertSessionHas('status_type', 'error');

    expect(CrudUndoAction::query()->where('token_hash', hash('sha256', $token))->value('used_at'))->toBeNull();
    $this->assertModelMissing($legalitas);

    Carbon::setTestNow();
});

test('the admin layout renders a bottom notification with undo action', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->withSession([
            'status' => 'Data berhasil diperbarui.',
            'undo_notification' => [
                'token' => str_repeat('a', 64),
                'message' => 'Data berhasil diperbarui.',
                'expires_in' => 10,
            ],
        ])
        ->get(route('dashboard'))
        ->assertSuccessful()
        ->assertSee('Pemberitahuan')
        ->assertSee('Undo')
        ->assertSee('fixed inset-x-4 bottom-4', false);
});
