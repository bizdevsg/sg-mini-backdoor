<?php

namespace App\Http\Middleware;

use App\Support\SystemActivityLogger;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

class LogApiActivity
{
    private const MODULE_LABELS = [
        'client-area' => 'Client Area',
        'banner' => 'Banner',
        'produk' => 'Produk',
        'pengumuman' => 'Pengumuman',
        'ebook' => 'Ebook',
        'signal' => 'Signal',
        'berita' => 'Berita',
        'penghargaan' => 'Penghargaan',
        'legalitas' => 'Legalitas',
        'company-profile' => 'Profil Perusahaan',
        'terms-and-conditions' => 'Syarat dan Ketentuan',
        'privacy-policy' => 'Kebijakan Privasi',
        'massages' => 'Massages',
        'unknown' => 'Unknown',
    ];

    public function __construct(
        private readonly SystemActivityLogger $systemActivityLogger,
    ) {
    }

    public function handle(Request $request, Closure $next): Response
    {
        $startedAt = microtime(true);

        try {
            $response = $next($request);

            $this->logRequest($request, $response->getStatusCode(), $startedAt);

            return $response;
        } catch (Throwable $exception) {
            $statusCode = $exception instanceof HttpExceptionInterface
                ? $exception->getStatusCode()
                : 500;

            $this->logRequest($request, $statusCode, $startedAt);

            throw $exception;
        }
    }

    private function logRequest(Request $request, int $statusCode, float $startedAt): void
    {
        $module = $this->resolveModule($request);
        $moduleLabel = self::MODULE_LABELS[$module] ?? self::MODULE_LABELS['unknown'];

        $this->systemActivityLogger->log(
            category: 'api',
            event: 'api_request',
            description: "{$request->method()} {$moduleLabel} API diakses.",
            subject: $module,
            request: $request,
            context: [
                'module' => $module,
                'method' => $request->method(),
                'path' => '/'.$request->path(),
                'status_code' => $statusCode,
                'duration_ms' => (int) round((microtime(true) - $startedAt) * 1000),
                'query' => $request->query(),
            ],
        );
    }

    private function resolveModule(Request $request): string
    {
        $module = (string) $request->segment(3);

        return array_key_exists($module, self::MODULE_LABELS)
            ? $module
            : 'unknown';
    }
}
