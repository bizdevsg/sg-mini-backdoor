<?php

use App\Models\ClientAreaTiktokVideo;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;

uses(LazilyRefreshDatabase::class);

test('guests are redirected to login when accessing tiktok video pages', function () {
    $video = ClientAreaTiktokVideo::factory()->create();

    $this->get(route('tiktok-videos.index'))->assertRedirect(route('login'));
    $this->get(route('tiktok-videos.create'))->assertRedirect(route('login'));
    $this->get(route('tiktok-videos.edit', $video))->assertRedirect(route('login'));
});

test('regular admins cannot access tiktok video pages', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('tiktok-videos.index'))
        ->assertForbidden();
});

test('superadmin can access tiktok video pages', function () {
    $user = User::factory()->superadmin()->create();
    $video = ClientAreaTiktokVideo::factory()->create(['title' => 'Video TikTok']);

    $this->actingAs($user);

    $this->get(route('tiktok-videos.index'))
        ->assertSuccessful()
        ->assertSee('Video TikTok');

    $this->get(route('tiktok-videos.create'))
        ->assertSuccessful()
        ->assertSee('Tambah Video TikTok');

    $this->get(route('tiktok-videos.edit', $video))
        ->assertSuccessful()
        ->assertSee($video->video_url);
});

test('superadmin can create update and delete tiktok videos', function () {
    $user = User::factory()->superadmin()->create();
    $videoUrl = 'https://www.tiktok.com/@solid.prime/video/7700863615682858247';

    $this->actingAs($user)
        ->post(route('tiktok-videos.store'), [
            'video_url' => $videoUrl,
            'title' => 'Video TikTok',
            'is_active' => '1',
        ])
        ->assertRedirect(route('tiktok-videos.index'));

    $video = ClientAreaTiktokVideo::query()->firstOrFail();

    expect($video->video_id)->toBe('7700863615682858247')
        ->and($video->video_url)->toBe($videoUrl)
        ->and($video->title)->toBe('Video TikTok')
        ->and($video->is_active)->toBeTrue();

    $this->actingAs($user)
        ->put(route('tiktok-videos.update', $video), [
            'video_url' => 'https://www.tiktok.com/@solid.prime/video/7700863615682858248',
            'title' => 'Video TikTok Terbaru',
            'is_active' => '0',
        ])
        ->assertRedirect(route('tiktok-videos.index'));

    $video->refresh();

    expect($video->video_id)->toBe('7700863615682858248')
        ->and($video->title)->toBe('Video TikTok Terbaru')
        ->and($video->is_active)->toBeFalse();

    $this->actingAs($user)
        ->delete(route('tiktok-videos.destroy', $video))
        ->assertRedirect(route('tiktok-videos.index'));

    $this->assertModelMissing($video);
});

test('new tiktok videos default to inactive', function () {
    $user = User::factory()->superadmin()->create();

    $this->actingAs($user)
        ->post(route('tiktok-videos.store'), [
            'video_url' => 'https://www.tiktok.com/@solid.prime/video/7700863615682858247',
            'title' => 'Video TikTok',
        ])
        ->assertRedirect(route('tiktok-videos.index'));

    expect(ClientAreaTiktokVideo::query()->firstOrFail()->is_active)->toBeFalse();
});

test('tiktok video url must be a canonical video url', function (string $videoUrl) {
    $user = User::factory()->superadmin()->create();

    $this->actingAs($user)
        ->post(route('tiktok-videos.store'), [
            'video_url' => $videoUrl,
            'title' => 'Video TikTok',
        ])
        ->assertInvalid(['video_url']);
})->with([
    'another domain' => 'https://example.com/@solid.prime/video/7700863615682858247',
    'tiktok profile' => 'https://www.tiktok.com/@solid.prime',
    'non numeric video id' => 'https://www.tiktok.com/@solid.prime/video/abc',
]);

test('create and edit forms render a save confirmation prompt', function () {
    $user = User::factory()->superadmin()->create();
    $video = ClientAreaTiktokVideo::factory()->create();

    $this->actingAs($user);

    $this->get(route('tiktok-videos.create'))
        ->assertSuccessful()
        ->assertSee('data-confirm-submit', false)
        ->assertSee('Simpan video TikTok baru?');

    $this->get(route('tiktok-videos.edit', $video))
        ->assertSuccessful()
        ->assertSee('data-confirm-submit', false)
        ->assertSee('Simpan perubahan video ini?');
});
