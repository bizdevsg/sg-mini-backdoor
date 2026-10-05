<?php

namespace App\Http\Middleware;

use App\Models\CrudUndoAction;
use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class CaptureCrudUndo
{
    public function handle(Request $request, Closure $next): Response
    {
        $context = $this->captureContext($request);

        if ($context === null) {
            return $next($request);
        }

        try {
            $response = $next($request);
        } catch (Throwable $exception) {
            $this->discardBackups($context['files']);

            throw $exception;
        }

        if ($response->getStatusCode() >= 400) {
            $this->discardBackups($context['files']);

            return $response;
        }

        $afterModel = $this->findAfterModel($context);
        $after = $afterModel?->getRawOriginal();

        if ($context['before'] === $after) {
            $this->discardBackups($context['files']);

            return $response;
        }

        $token = Str::random(64);
        $ttl = max(1, (int) config('crud-undo.ttl_seconds', 10));
        $label = (string) data_get(config('crud-undo.models'), $context['model_type'].'.label', 'Data');

        CrudUndoAction::query()->create([
            'user_id' => $request->user()->getAuthIdentifier(),
            'token_hash' => hash('sha256', $token),
            'route_name' => $context['route_name'],
            'action' => $context['action'],
            'model_type' => $context['model_type'],
            'model_key' => (string) ($context['model_key'] ?? $afterModel?->getKey()),
            'before_snapshot' => $context['before'],
            'after_snapshot' => $after,
            'files' => $context['files'],
            'redirect_url' => $response instanceof RedirectResponse
                ? $response->getTargetUrl()
                : $request->headers->get('referer', route('dashboard')),
            'expires_at' => now()->addSeconds($ttl),
            'retention_until' => now()->addHours(max(1, (int) config('crud-undo.retention_hours', 24))),
        ]);

        $request->session()->flash('undo_notification', [
            'token' => $token,
            'message' => session('status') ?: "{$label} berhasil {$this->actionLabel($context['action'])}.",
            'expires_in' => $ttl,
        ]);

        return $response;
    }

    /**
     * @return array<string, mixed>|null
     */
    private function captureContext(Request $request): ?array
    {
        if (! Schema::hasTable('crud_undo_actions') || $request->user() === null) {
            return null;
        }

        $routeName = (string) $request->route()?->getName();
        $action = match (true) {
            str_ends_with($routeName, '.update') => 'update',
            str_ends_with($routeName, '.destroy') => 'delete',
            default => null,
        };

        if ($action === null) {
            return null;
        }

        $model = $this->routeModel($request);

        if (! $model instanceof Model) {
            $modelClass = config("crud-undo.singletons.{$routeName}");

            if (! is_string($modelClass) || ! array_key_exists($modelClass, config('crud-undo.models', []))) {
                return null;
            }

            $model = new $modelClass();

            if (! Schema::hasTable($model->getTable())) {
                return null;
            }

            $model = $model->newQuery()->first() ?? $model;
        }

        $modelType = $model::class;

        if (! array_key_exists($modelType, config('crud-undo.models', []))) {
            return null;
        }

        $before = $model->exists ? $model->getRawOriginal() : null;
        $files = $before === null ? [] : $this->backupFiles($modelType, $before);

        return [
            'route_name' => $routeName,
            'action' => $action,
            'model_type' => $modelType,
            'model_key' => $model->exists ? $model->getKey() : null,
            'before' => $before,
            'files' => $files,
        ];
    }

    private function routeModel(Request $request): ?Model
    {
        $models = array_values(array_filter(
            $request->route()?->parameters() ?? [],
            fn (mixed $parameter): bool => $parameter instanceof Model
        ));

        $model = end($models);

        return $model instanceof Model ? $model : null;
    }

    /**
     * @param  array<string, mixed>  $context
     */
    private function findAfterModel(array $context): ?Model
    {
        $modelClass = $context['model_type'];
        $model = new $modelClass();

        if ($context['model_key'] !== null) {
            return $model->newQuery()->find($context['model_key']);
        }

        return $model->newQuery()->first();
    }

    /**
     * @param  array<string, mixed>  $snapshot
     * @return array<int, array{field: string, original_path: string, backup_path: string}>
     */
    private function backupFiles(string $modelType, array $snapshot): array
    {
        $backups = [];

        foreach (data_get(config('crud-undo.models'), $modelType.'.files', []) as $field) {
            $path = $snapshot[$field] ?? null;

            if (! is_string($path) || $path === '' || Str::startsWith($path, ['http://', 'https://', '/'])) {
                continue;
            }

            if (! Storage::disk('public')->exists($path)) {
                continue;
            }

            $backupPath = 'crud-undo/'.Str::uuid().'/'.basename($path);
            Storage::disk('local')->put($backupPath, Storage::disk('public')->get($path));

            $backups[] = [
                'field' => $field,
                'original_path' => $path,
                'backup_path' => $backupPath,
            ];
        }

        return $backups;
    }

    /** @param array<int, array<string, string>> $files */
    private function discardBackups(array $files): void
    {
        Storage::disk('local')->delete(array_column($files, 'backup_path'));
    }

    private function actionLabel(string $action): string
    {
        return $action === 'delete' ? 'dihapus' : 'diperbarui';
    }
}
