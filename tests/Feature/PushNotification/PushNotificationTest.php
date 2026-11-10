<?php

use App\Enums\PushNotificationStatus;
use App\Models\Banner;
use App\Models\Berita;
use App\Models\BeritaCategory;
use App\Models\PushNotification;
use App\Models\SystemActivityLog;
use App\Models\User;
use App\Support\PushNotificationService;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;

uses(LazilyRefreshDatabase::class);

const MINI_MICRO_TEST_URL = 'https://mini-micro.test/system/private/send-manual-notification*';

beforeEach(function () {
    config([
        'services.mini_micro.base_url' => 'https://mini-micro.test',
        'services.mini_micro.api_key' => 'test-sai-key',
        'services.mini_micro.dry_run' => true,
        'services.mini_micro.broadcast_cooldown_minutes' => 5,
    ]);

    Http::preventStrayRequests();
});

function pushBerita(array $attributes = []): Berita
{
    $category = BeritaCategory::query()->firstOrCreate(
        ['slug' => 'market-update'],
        ['name' => 'Market Update'],
    );

    return Berita::query()->create([
        'berita_category_id' => $category->id,
        'title_id' => 'Harga Emas Sentuh Rekor Baru di Tengah Pelemahan Dolar AS',
        'title_en' => 'Gold hits a new record',
        'slug' => 'harga-emas-sentuh-rekor-'.uniqid(),
        'content_id' => '<p>Harga emas dunia naik <strong>2%</strong> hari ini didorong pelemahan dolar AS.</p>',
        'is_notification' => true,
        ...$attributes,
    ]);
}

function fakeMiniMicroSuccess(string $fcmTopic = 'Announcement_UAT'): void
{
    Http::fake([
        MINI_MICRO_TEST_URL => Http::response([
            'headers' => ['statusCode' => 200, 'message' => 'OK'],
            'data' => ['fcmTopic' => $fcmTopic],
        ]),
    ]);
}

function validBroadcast(array $overrides = []): array
{
    return [
        'topic' => 'Announcement',
        'title' => 'Server maintenance malam ini',
        'description' => 'Aplikasi tidak bisa diakses pukul 23.00-01.00 WIB.',
        'confirmation' => 'KIRIM',
        ...$overrides,
    ];
}

test('guests are redirected to login', function () {
    $this->get(route('push-notifications.index'))->assertRedirect(route('login'));
    $this->get(route('push-notifications.create'))->assertRedirect(route('login'));
    $this->post(route('push-notifications.store'), validBroadcast())->assertRedirect(route('login'));
});

test('regular admins cannot access or send push notifications', function () {
    $this->actingAs(User::factory()->create());

    $this->get(route('push-notifications.index'))->assertForbidden();
    $this->get(route('push-notifications.create'))->assertForbidden();
    $this->post(route('push-notifications.store'), validBroadcast())->assertForbidden();

    expect(PushNotification::query()->count())->toBe(0);
});

test('admin host cannot send push notifications', function () {
    $this->actingAs(User::factory()->adminHost()->create())
        ->post(route('push-notifications.store'), validBroadcast())
        ->assertForbidden();

    expect(PushNotification::query()->count())->toBe(0);
});

test('superadmin can view the history and the manual form', function () {
    PushNotification::query()->create([
        'topic' => 'Promotion',
        'title' => 'Promo Deposit November',
        'description' => 'Cashback deposit pertama.',
        'payload' => ['topic' => 'Promotion'],
        'status' => 'dry_run',
    ]);

    $this->actingAs(User::factory()->superadmin()->create());

    $this->get(route('push-notifications.index'))
        ->assertSuccessful()
        ->assertSee('Notifikasi Push')
        ->assertSee('Promo Deposit November')
        ->assertSee('Mode uji (dry run) aktif');

    $this->get(route('push-notifications.create'))
        ->assertSuccessful()
        ->assertSee('Buat Notifikasi Push');
});

test('create form is prefilled and truncated from a berita', function () {
    $berita = pushBerita();

    $this->actingAs(User::factory()->superadmin()->create())
        ->get(route('push-notifications.create', ['source_type' => 'berita', 'source_id' => $berita->id]))
        ->assertSuccessful()
        ->assertSee('news_id='.$berita->id)
        ->assertSee('Harga emas dunia naik 2% hari ini didorong pelemahan dolar AS.');

    $draft = app(PushNotificationService::class)->draftFromSource('berita', $berita->id);

    expect(mb_strlen($draft['title']))->toBeLessThanOrEqual(40)
        ->and($draft['title'])->toEndWith('…')
        ->and($draft['description'])->not->toContain('<');
});

test('manual broadcast runs as dry run with logOnly and the api key header', function () {
    fakeMiniMicroSuccess();
    $user = User::factory()->superadmin()->create();

    $this->actingAs($user)
        ->post(route('push-notifications.store'), validBroadcast())
        ->assertRedirect(route('push-notifications.index'))
        ->assertSessionHas('status', fn (string $message) => str_contains($message, 'Dry run berhasil'));

    Http::assertSent(function (Request $request) {
        return $request->method() === 'POST'
            && str_contains($request->url(), 'logOnly=true')
            && $request->hasHeader('X-Api-Key', 'test-sai-key')
            && $request['topic'] === 'Announcement'
            && $request['broadcast'] === true
            && ! isset($request['news_id'])
            && ! isset($request['noAccount']);
    });

    $notification = PushNotification::query()->sole();

    expect($notification->status)->toBe(PushNotificationStatus::DryRun)
        ->and($notification->dry_run)->toBeTrue()
        ->and($notification->fcm_topic)->toBe('Announcement_UAT')
        ->and($notification->user_id)->toBe($user->id)
        ->and($notification->attempts)->toBe(1);

    expect(SystemActivityLog::query()->where('event', 'push_notification_dry_run')->exists())->toBeTrue();
});

test('live broadcast from a berita sends news_id without logOnly', function () {
    config(['services.mini_micro.dry_run' => false]);
    fakeMiniMicroSuccess();
    $berita = pushBerita();

    $this->actingAs(User::factory()->superadmin()->create())
        ->post(route('push-notifications.store'), validBroadcast([
            'source_type' => 'berita',
            'source_id' => $berita->id,
        ]))
        ->assertRedirect(route('push-notifications.index'));

    Http::assertSent(fn (Request $request) => ! str_contains($request->url(), 'logOnly')
        && $request['news_id'] === $berita->id
        && $request['broadcast'] === true);

    expect(PushNotification::query()->sole()->status)->toBe(PushNotificationStatus::Sent);
});

test('broadcast from a banner sends promotion_id with the Promotion topic', function () {
    fakeMiniMicroSuccess('Promotion_UAT');
    $banner = Banner::factory()->create(['is_notification' => true]);

    $this->actingAs(User::factory()->superadmin()->create())
        ->post(route('push-notifications.store'), validBroadcast([
            'topic' => 'Promotion',
            'source_type' => 'banner',
            'source_id' => $banner->id,
        ]))
        ->assertRedirect(route('push-notifications.index'));

    Http::assertSent(fn (Request $request) => $request['topic'] === 'Promotion'
        && $request['promotion_id'] === $banner->id);
});

test('broadcast validation rejects invalid input', function (array $overrides, string $errorField) {
    Http::fake();

    $this->actingAs(User::factory()->superadmin()->create())
        ->from(route('push-notifications.create'))
        ->post(route('push-notifications.store'), validBroadcast($overrides))
        ->assertSessionHasErrors($errorField);

    Http::assertNothingSent();
    expect(PushNotification::query()->count())->toBe(0);
})->with([
    'title too long' => [['title' => str_repeat('a', 41)], 'title'],
    'description too long' => [['description' => str_repeat('a', 151)], 'description'],
    'missing confirmation' => [['confirmation' => ''], 'confirmation'],
    'wrong confirmation' => [['confirmation' => 'kirim'], 'confirmation'],
    'unknown topic' => [['topic' => 'Promo'], 'topic'],
]);

test('a berita that is not marked for notification cannot be broadcast', function () {
    Http::fake();
    $berita = pushBerita(['is_notification' => false]);

    $this->actingAs(User::factory()->superadmin()->create())
        ->post(route('push-notifications.store'), validBroadcast([
            'source_type' => 'berita',
            'source_id' => $berita->id,
        ]))
        ->assertSessionHasErrors('source_id');

    Http::assertNothingSent();
});

test('a berita can not be sent with the Promotion topic', function () {
    Http::fake();
    $berita = pushBerita();

    $this->actingAs(User::factory()->superadmin()->create())
        ->post(route('push-notifications.store'), validBroadcast([
            'topic' => 'Promotion',
            'source_type' => 'berita',
            'source_id' => $berita->id,
        ]))
        ->assertSessionHasErrors('topic');

    Http::assertNothingSent();
});

test('live broadcasts respect the cooldown but dry runs do not', function () {
    fakeMiniMicroSuccess();
    $this->actingAs(User::factory()->superadmin()->create());

    $this->post(route('push-notifications.store'), validBroadcast())->assertSessionHasNoErrors();
    $this->post(route('push-notifications.store'), validBroadcast())->assertSessionHasNoErrors();

    config(['services.mini_micro.dry_run' => false]);

    $this->post(route('push-notifications.store'), validBroadcast())->assertSessionHasNoErrors();
    $this->post(route('push-notifications.store'), validBroadcast())->assertSessionHasErrors('confirmation');

    $this->travel(6)->minutes();

    $this->post(route('push-notifications.store'), validBroadcast())->assertSessionHasNoErrors();

    expect(PushNotification::query()->count())->toBe(4);
});

test('a berita that was already broadcast needs an explicit resend', function () {
    config([
        'services.mini_micro.dry_run' => false,
        'services.mini_micro.broadcast_cooldown_minutes' => 0,
    ]);
    fakeMiniMicroSuccess();
    $berita = pushBerita();
    $payload = validBroadcast(['source_type' => 'berita', 'source_id' => $berita->id]);

    $this->actingAs(User::factory()->superadmin()->create());

    $this->post(route('push-notifications.store'), $payload)->assertSessionHasNoErrors();
    $this->post(route('push-notifications.store'), $payload)->assertSessionHasErrors('resend');
    $this->post(route('push-notifications.store'), [...$payload, 'resend' => '1'])->assertSessionHasNoErrors();

    Http::assertSentCount(2);
});

test('a rejected api key marks the notification as failed without retrying', function () {
    Http::fake([
        MINI_MICRO_TEST_URL => Http::response([
            'headers' => ['statusCode' => 401, 'message' => 'Invalid API key'],
            'data' => null,
        ], 401),
    ]);

    $this->actingAs(User::factory()->superadmin()->create())
        ->post(route('push-notifications.store'), validBroadcast())
        ->assertRedirect(route('push-notifications.index'))
        ->assertSessionHas('status_type', 'error');

    Http::assertSentCount(1);

    $notification = PushNotification::query()->sole();

    expect($notification->status)->toBe(PushNotificationStatus::Failed)
        ->and($notification->http_status)->toBe(401)
        ->and($notification->error_message)->toContain('API key ditolak')
        ->and($notification->error_message)->toContain('Invalid API key');

    expect(SystemActivityLog::query()->where('event', 'push_notification_failed')->exists())->toBeTrue();
});

test('a 404 response does not assume a device token is missing', function () {
    Http::fake([
        MINI_MICRO_TEST_URL => Http::response([
            'headers' => ['message' => 'Route not found'],
        ], 404),
    ]);

    $this->actingAs(User::factory()->superadmin()->create())
        ->post(route('push-notifications.store'), validBroadcast())
        ->assertSessionHas('status_type', 'error');

    $notification = PushNotification::query()->sole();

    expect($notification->status)->toBe(PushNotificationStatus::Failed)
        ->and($notification->http_status)->toBe(404)
        ->and($notification->error_message)->toContain('endpoint notifikasi UAT')
        ->and($notification->error_message)->toContain('Route not found')
        ->and($notification->error_message)->not->toContain('Device token');

    Http::assertSentCount(1);
});

test('an application error inside an HTTP 200 response is marked as failed', function () {
    Http::fake([
        MINI_MICRO_TEST_URL => Http::response([
            'headers' => ['statusCode' => 404, 'message' => 'Not Found.'],
        ], 200),
    ]);

    $this->actingAs(User::factory()->superadmin()->create())
        ->post(route('push-notifications.store'), validBroadcast())
        ->assertSessionHas('status_type', 'error');

    $notification = PushNotification::query()->sole();

    expect($notification->status)->toBe(PushNotificationStatus::Failed)
        ->and($notification->http_status)->toBe(200)
        ->and($notification->error_message)->toContain('404')
        ->and($notification->error_message)->toContain('Not Found.');
});

test('a missing api key fails without calling the server', function () {
    config(['services.mini_micro.api_key' => null]);
    Http::fake();

    $this->actingAs(User::factory()->superadmin()->create())
        ->post(route('push-notifications.store'), validBroadcast());

    Http::assertNothingSent();

    expect(PushNotification::query()->sole())
        ->status->toBe(PushNotificationStatus::Failed)
        ->error_message->toContain('MINI_MICRO_API_KEY');
});

test('a failed notification can be retried', function () {
    Http::fakeSequence(MINI_MICRO_TEST_URL)
        ->push(['headers' => ['statusCode' => 502, 'message' => 'FCM error']], 502)
        ->push(['headers' => ['statusCode' => 200], 'data' => ['fcmTopic' => 'Announcement_UAT']]);

    $this->actingAs(User::factory()->superadmin()->create());

    $this->post(route('push-notifications.store'), validBroadcast());
    $notification = PushNotification::query()->sole();

    expect($notification->status)->toBe(PushNotificationStatus::Failed)
        ->and($notification->error_message)->toContain('502');

    $this->post(route('push-notifications.retry', $notification))
        ->assertRedirect(route('push-notifications.index'));

    expect($notification->refresh()->status)->toBe(PushNotificationStatus::DryRun)
        ->and($notification->attempts)->toBe(2)
        ->and($notification->error_message)->toBeNull();
});

test('only failed notifications can be retried', function () {
    Http::fake();
    $notification = PushNotification::query()->create([
        'topic' => 'Announcement',
        'title' => 'Sudah terkirim',
        'description' => 'Isi',
        'payload' => ['topic' => 'Announcement'],
        'status' => 'sent',
        'dry_run' => false,
    ]);

    $this->actingAs(User::factory()->superadmin()->create())
        ->post(route('push-notifications.retry', $notification))
        ->assertSessionHas('status_type', 'error');

    Http::assertNothingSent();
});

test('broadcast buttons appear on berita and banner pages for superadmin only', function () {
    $berita = pushBerita();
    $banner = Banner::factory()->create(['is_notification' => true]);
    $createUrl = route('push-notifications.create', ['source_type' => 'berita', 'source_id' => $berita->id]);

    $this->actingAs(User::factory()->superadmin()->create());

    $this->get(route('berita.show', ['beritaCategory' => $berita->category, 'berita' => $berita]))
        ->assertSuccessful()
        ->assertSee('Broadcast Notifikasi')
        ->assertSee($createUrl);

    $this->get(route('banner.index'))
        ->assertSuccessful()
        ->assertSee(route('push-notifications.create', ['source_type' => 'banner', 'source_id' => $banner->id]));

    $this->actingAs(User::factory()->create())
        ->get(route('berita.show', ['beritaCategory' => $berita->category, 'berita' => $berita]))
        ->assertSuccessful()
        ->assertDontSee('Broadcast Notifikasi');
});

test('truncate keeps text within the limit and breaks on words', function () {
    expect(PushNotificationService::truncate('<p>Halo&nbsp;dunia</p>', 40))->toBe('Halo dunia')
        ->and(PushNotificationService::truncate(str_repeat('kata ', 20), 40))
        ->toEndWith('…')
        ->and(mb_strlen(PushNotificationService::truncate(str_repeat('kata ', 20), 40)))->toBeLessThanOrEqual(40);
});
