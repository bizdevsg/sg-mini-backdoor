<?php

use App\Http\Middleware\AuthenticateApiWithKey;
use Illuminate\Http\Request;
use Tests\TestCase;

uses(TestCase::class);

test('middleware accepts an api key matching the configured sha256 hash', function () {
    config()->set('api-auth.header', 'X-API-Key');
    config()->set('api-auth.key_hash', hash('sha256', 'valid-api-key'));

    $request = Request::create('/api/v1/banner', server: [
        'HTTP_X_API_KEY' => 'valid-api-key',
    ]);

    $response = (new AuthenticateApiWithKey)->handle(
        $request,
        fn () => response()->json(['ok' => true]),
    );

    expect($response->getStatusCode())->toBe(200);
});

test('middleware rejects an api key not matching the configured sha256 hash', function () {
    config()->set('api-auth.header', 'X-API-Key');
    config()->set('api-auth.key_hash', hash('sha256', 'valid-api-key'));

    $request = Request::create('/api/v1/banner', server: [
        'HTTP_X_API_KEY' => 'invalid-api-key',
    ]);

    $response = (new AuthenticateApiWithKey)->handle(
        $request,
        fn () => response()->json(['ok' => true]),
    );

    expect($response->getStatusCode())->toBe(403)
        ->and($response->getData(true)['message'])->toBe('API key tidak valid.');
});

test('middleware rejects malformed api key hash configuration', function () {
    config()->set('api-auth.header', 'X-API-Key');
    config()->set('api-auth.key_hash', 'not-a-sha256-hash');

    $request = Request::create('/api/v1/banner', server: [
        'HTTP_X_API_KEY' => 'any-api-key',
    ]);

    $response = (new AuthenticateApiWithKey)->handle(
        $request,
        fn () => response()->json(['ok' => true]),
    );

    expect($response->getStatusCode())->toBe(403)
        ->and($response->getData(true)['message'])->toBe('API key belum dikonfigurasi.');
});
