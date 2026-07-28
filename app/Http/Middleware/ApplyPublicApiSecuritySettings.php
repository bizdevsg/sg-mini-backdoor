<?php

namespace App\Http\Middleware;

use App\Support\ClientAreaSettingStore;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApplyPublicApiSecuritySettings
{
    public function __construct(
        private readonly ClientAreaSettingStore $clientAreaSettingStore,
    ) {
    }

    public function handle(Request $request, Closure $next): Response
    {
        $settings = $this->clientAreaSettingStore->get();
        $allowedOrigins = $this->normalizeOrigins($settings['allowed_origin_frontend']);

        if (! $settings['api_enabled']) {
            return $this->jsonError('Public API sedang dinonaktifkan.', 503);
        }

        $origin = trim((string) $request->headers->get('Origin', ''));

        if ($allowedOrigins !== [] && $origin === '') {
            return $this->jsonError('Header Origin wajib dikirim untuk API ini.', 403);
        }

        if ($origin !== '' && ! $this->isOriginAllowed($origin, $allowedOrigins)) {
            return $this->jsonError('Origin frontend tidak diizinkan.', 403);
        }

        $response = $next($request);

        if ($origin !== '' && $this->shouldExposeOrigin($origin, $allowedOrigins)) {
            $response->headers->set('Access-Control-Allow-Origin', $origin);
            $response->headers->set('Vary', 'Origin');
        }

        if ($settings['api_key_rotation_notice'] !== null) {
            $response->headers->set('X-API-Key-Rotation-Notice', $settings['api_key_rotation_notice']);
        }

        return $response;
    }

    /**
     * @param  list<string>  $allowedOrigins
     */
    private function isOriginAllowed(string $origin, array $allowedOrigins): bool
    {
        if ($allowedOrigins === [] || in_array('*', $allowedOrigins, true)) {
            return true;
        }

        return in_array($origin, $allowedOrigins, true);
    }

    /**
     * @param  list<string>  $allowedOrigins
     */
    private function shouldExposeOrigin(string $origin, array $allowedOrigins): bool
    {
        return $allowedOrigins === [] || in_array('*', $allowedOrigins, true) || in_array($origin, $allowedOrigins, true);
    }

    /**
     * @return list<string>
     */
    private function normalizeOrigins(?string $allowedOrigins): array
    {
        if ($allowedOrigins === null || trim($allowedOrigins) === '') {
            return [];
        }

        $segments = preg_split('/[\r\n,]+/', $allowedOrigins) ?: [];

        return array_values(array_filter(array_map(
            static fn (string $value): string => trim($value),
            $segments,
        )));
    }

    private function jsonError(string $message, int $status): JsonResponse
    {
        return response()->json([
            'message' => $message,
        ], $status);
    }
}
