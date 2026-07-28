<?php

namespace App\Http\Middleware;

use App\Support\SystemActivityLogger;
use App\Support\SystemActivitySubjectCatalog;
use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

class LogAdminDataActivity
{
    private const EXCLUDED_ROUTE_NAMES = [
        'logout',
        'client-area.update',
        'client-area.update-api-security',
    ];

    public function __construct(
        private readonly SystemActivityLogger $systemActivityLogger,
    ) {
    }

    public function handle(Request $request, Closure $next): Response
    {
        if (! $this->shouldTrack($request)) {
            return $next($request);
        }

        $startedAt = microtime(true);

        try {
            $response = $next($request);
        } catch (AuthorizationException $exception) {
            throw $exception;
        } catch (ValidationException $exception) {
            throw $exception;
        } catch (Throwable $exception) {
            if ($exception instanceof HttpExceptionInterface && $exception->getStatusCode() < 500) {
                throw $exception;
            }

            $this->logRequest($request, 500, $startedAt);

            throw $exception;
        }

        $this->logRequest($request, $response->getStatusCode(), $startedAt);

        return $response;
    }

    private function shouldTrack(Request $request): bool
    {
        $method = strtoupper($request->method());
        $routeName = (string) $request->route()?->getName();

        return in_array($method, ['POST', 'PUT', 'PATCH', 'DELETE'], true)
            && $routeName !== ''
            && ! in_array($routeName, self::EXCLUDED_ROUTE_NAMES, true);
    }

    private function logRequest(Request $request, int $statusCode, float $startedAt): void
    {
        $routeName = (string) $request->route()?->getName();
        $subject = $this->resolveSubject($routeName);
        $moduleLabel = SystemActivitySubjectCatalog::data()[$subject] ?? 'Unknown';
        $action = $this->resolveAction($routeName, $request->method(), $subject);
        $actionLabel = $this->resolveActionLabel($action);

        $this->systemActivityLogger->log(
            category: 'data',
            event: "data_{$action}",
            description: "Aksi {$actionLabel} pada {$moduleLabel} dari backdoor.",
            subject: $subject,
            user: $request->user(),
            request: $request,
            context: [
                'module' => $subject,
                'action' => $action,
                'action_label' => $actionLabel,
                'method' => strtoupper($request->method()),
                'path' => '/'.$request->path(),
                'route_name' => $routeName,
                'status_code' => $statusCode,
                'duration_ms' => (int) round((microtime(true) - $startedAt) * 1000),
                'route_parameters' => $this->normalizeRouteParameters($request),
                'payload_keys' => array_values(array_keys($request->except([
                    '_token',
                    '_method',
                    'password',
                    'password_confirmation',
                    'current_password',
                ]))),
            ],
        );
    }

    private function resolveSubject(string $routeName): string
    {
        if ($routeName === 'tinymce.images.store') {
            return 'tinymce-images';
        }

        $subject = explode('.', $routeName)[0] ?? 'unknown';

        return array_key_exists($subject, SystemActivitySubjectCatalog::data())
            ? $subject
            : 'unknown';
    }

    private function resolveAction(string $routeName, string $method, string $subject): string
    {
        if ($subject === 'tinymce-images') {
            return 'upload';
        }

        $suffix = explode('.', $routeName);
        $suffix = end($suffix) ?: '';

        return match ($suffix) {
            'store' => 'create',
            'update' => 'update',
            'destroy' => 'delete',
            default => match (strtoupper($method)) {
                'POST' => 'create',
                'PUT', 'PATCH' => 'update',
                'DELETE' => 'delete',
                default => 'update',
            },
        };
    }

    private function resolveActionLabel(string $action): string
    {
        return match ($action) {
            'create' => 'tambah',
            'update' => 'ubah',
            'delete' => 'hapus',
            'upload' => 'upload',
            default => 'ubah',
        };
    }

    /**
     * @return array<string, mixed>
     */
    private function normalizeRouteParameters(Request $request): array
    {
        $parameters = $request->route()?->parameters() ?? [];
        $normalized = [];

        foreach ($parameters as $key => $value) {
            if ($value instanceof Model) {
                $normalized[$key] = $this->normalizeModelParameter($value);

                continue;
            }

            $normalized[$key] = is_scalar($value) ? $value : (string) $value;
        }

        return $normalized;
    }

    /**
     * @return array<string, int|string>
     */
    private function normalizeModelParameter(Model $model): array
    {
        $normalized = [
            'id' => $model->getKey(),
        ];

        foreach (['name', 'title', 'judul', 'slug', 'email'] as $attribute) {
            $value = $model->getAttribute($attribute);

            if (is_string($value) && $value !== '') {
                $normalized[$attribute] = $value;

                break;
            }
        }

        return $normalized;
    }
}
