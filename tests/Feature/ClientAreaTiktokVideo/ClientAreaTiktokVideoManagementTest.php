<?php

use App\Models\ClientAreaTiktokVideo;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(LazilyRefreshDatabase::class);

test('superadmin can upload and replace an mp4 video', function () {
    Storage::fake('public');
    $user = User::factory()->superadmin()->create();
    $data = ['video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ', 'title' => 'Video', 'is_active' => '1'];

    $this->actingAs($user)->post(route('tiktok-videos.store'), $data + [
        'video_file' => UploadedFile::fake()->create('video.mp4', 100, 'video/mp4'),
    ])->assertRedirect(route('tiktok-videos.index'));
    $video = ClientAreaTiktokVideo::query()->firstOrFail();
    $oldPath = $video->file_path;
    Storage::disk('public')->assertExists($oldPath);

    $this->put(route('tiktok-videos.update', $video), $data + [
        'video_file' => UploadedFile::fake()->create('new.mp4', 100, 'video/mp4'),
    ])->assertRedirect(route('tiktok-videos.index'));
    $video->refresh();
    Storage::disk('public')->assertMissing($oldPath);
    Storage::disk('public')->assertExists($video->file_path);

    $this->delete(route('tiktok-videos.destroy', $video))->assertRedirect(route('tiktok-videos.index'));
    Storage::disk('public')->assertMissing($video->file_path);
});

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
    $video = ClientAreaTiktokVideo::factory()->create(['title' => 'Video']);

    $this->actingAs($user);

    $this->get(route('tiktok-videos.index'))
        ->assertSuccessful()
        ->assertSee('Video');

    $this->get(route('tiktok-videos.create'))
        ->assertSuccessful()
        ->assertSee('Tambah Video');

    $this->get(route('tiktok-videos.edit', $video))
        ->assertSuccessful()
        ->assertSee($video->video_url);
});

test('superadmin can create update and delete tiktok videos', function () {
    $user = User::factory()->superadmin()->create();
    $videoUrl = 'https://www.youtube.com/embed/dQw4w9WgXcQ';

    $this->actingAs($user)
        ->post(route('tiktok-videos.store'), [
            'video_url' => $videoUrl,
            'title' => 'Video',
            'is_active' => '1',
        ])
        ->assertRedirect(route('tiktok-videos.index'));

    $video = ClientAreaTiktokVideo::query()->firstOrFail();

    expect($video->video_id)->toBe('dQw4w9WgXcQ')
        ->and($video->video_url)->toBe($videoUrl)
        ->and($video->title)->toBe('Video')
        ->and($video->is_active)->toBeTrue();

    $this->actingAs($user)
        ->put(route('tiktok-videos.update', $video), [
            'video_url' => 'https://www.youtube.com/embed/aaaaaaaaaaa',
            'title' => 'Video Terbaru',
            'is_active' => '0',
        ])
        ->assertRedirect(route('tiktok-videos.index'));

    $video->refresh();

    expect($video->video_id)->toBe('aaaaaaaaaaa')
        ->and($video->title)->toBe('Video Terbaru')
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
            'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
            'title' => 'Video',
        ])
        ->assertRedirect(route('tiktok-videos.index'));

    expect(ClientAreaTiktokVideo::query()->firstOrFail()->is_active)->toBeFalse();
});

test('tiktok video url must be a canonical video url', function (string $videoUrl) {
    $user = User::factory()->superadmin()->create();

    $this->actingAs($user)
        ->post(route('tiktok-videos.store'), [
            'video_url' => $videoUrl,
            'title' => 'Video',
        ])
        ->assertInvalid(['video_url']);
})->with([
    'another domain' => 'https://example.com/embed/dQw4w9WgXcQ',
    'short video id' => 'https://www.youtube.com/embed/abc',
]);

test('create and edit forms render a save confirmation prompt', function () {
    $user = User::factory()->superadmin()->create();
    $video = ClientAreaTiktokVideo::factory()->create();

    $this->actingAs($user);

    $this->get(route('tiktok-videos.create'))
        ->assertSuccessful()
        ->assertSee('data-confirm-submit', false)
        ->assertSee('Simpan video baru?');

    $this->get(route('tiktok-videos.edit', $video))
        ->assertSuccessful()
        ->assertSee('data-confirm-submit', false)
        ->assertSee('Simpan perubahan video ini?');
});

test('youtube video id is extracted from embed, watch, short link and iframe code', function (string $input) {
    expect(ClientAreaTiktokVideo::detect($input))->toMatchArray(['platform' => 'youtube', 'video_id' => 'dQw4w9WgXcQ']);
})->with([
    'embed' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
    'watch' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
    'short link' => 'https://youtu.be/dQw4w9WgXcQ?t=10',
    'iframe code' => '<iframe width="560" height="315" src="https://www.youtube.com/embed/dQw4w9WgXcQ?si=abc" title="YouTube" allowfullscreen></iframe>',
]);

test('tiktok video url is detected and stored with the tiktok platform', function () {
    $user = User::factory()->superadmin()->create();
    $url = 'https://www.tiktok.com/@solid.prime/video/7700863615682858247';

    $this->actingAs($user)
        ->post(route('tiktok-videos.store'), ['video_url' => $url, 'title' => 'Video'])
        ->assertRedirect(route('tiktok-videos.index'));

    $video = ClientAreaTiktokVideo::query()->firstOrFail();

    expect($video->platform)->toBe('tiktok')
        ->and($video->video_id)->toBe('7700863615682858247')
        ->and($video->video_url)->toBe($url);
});
