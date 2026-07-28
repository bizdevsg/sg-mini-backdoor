<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClientArea\UpdateClientAreaSettingRequest;
use App\Http\Requests\ClientArea\UpdateApiSecuritySettingRequest;
use App\Support\ClientAreaSettingStore;
use App\Support\SystemActivityLogger;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class ClientAreaSettingController extends Controller
{
    public function __construct(
        private readonly ClientAreaSettingStore $clientAreaSettingStore,
        private readonly SystemActivityLogger $systemActivityLogger,
    ) {
    }

    public function show(): View
    {
        return view('client-area.show', [
            'settings' => $this->clientAreaSettingStore->get(),
            'apiBaseUrl' => url('/api/v1/client-area'),
            'apiKeyHeader' => (string) config('api-auth.header', 'X-API-Key'),
        ]);
    }

    public function update(UpdateClientAreaSettingRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $target = (string) $validated['target'];
        $enabled = (bool) $validated['enabled'];
        $beforeSettings = $this->clientAreaSettingStore->get();
        $settingMeta = $this->settingMeta($target);

        $this->clientAreaSettingStore->set($target, $enabled);

        $statusLabel = $enabled ? 'diaktifkan' : 'dinonaktifkan';
        $field = (string) $settingMeta['field'];
        $previousStatus = (bool) ($beforeSettings[$field] ?? false);

        $this->systemActivityLogger->log(
            category: 'data',
            event: (string) $settingMeta['event'],
            description: "{$settingMeta['label']} {$statusLabel}.",
            subject: 'client-area',
            user: $request->user(),
            request: $request,
            context: [
                'target' => $target,
                'previous_status' => $previousStatus,
                'new_status' => $enabled,
            ],
        );

        return redirect()
            ->route('client-area.show')
            ->with('status', "{$settingMeta['label']} berhasil {$statusLabel}.");
    }

    public function updateApiSecurity(UpdateApiSecuritySettingRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $beforeSettings = $this->clientAreaSettingStore->get();

        $payload = [
            'api_enabled' => (bool) $validated['api_enabled'],
            'api_key_rotation_notice' => $this->normalizeNullableString($validated['api_key_rotation_notice'] ?? null),
            'allowed_origin_frontend' => $this->normalizeNullableString($validated['allowed_origin_frontend'] ?? null),
        ];

        $this->clientAreaSettingStore->put($payload);

        $this->systemActivityLogger->log(
            category: 'data',
            event: 'api_security_update',
            description: 'Pengaturan API & Security diperbarui.',
            subject: 'client-area',
            user: $request->user(),
            request: $request,
            context: [
                'previous' => [
                    'api_enabled' => $beforeSettings['api_enabled'],
                    'api_key_rotation_notice' => $beforeSettings['api_key_rotation_notice'],
                    'allowed_origin_frontend' => $beforeSettings['allowed_origin_frontend'],
                ],
                'current' => $payload,
            ],
        );

        return redirect()
            ->route('client-area.show')
            ->with('status', 'Pengaturan API & Security berhasil diperbarui.');
    }

    /**
     * @return array{field: string, label: string, event: string}
     */
    private function settingMeta(string $target): array
    {
        return match ($target) {
            'prod' => [
                'field' => 'client_area_prod',
                'label' => 'Client Area Production',
                'event' => 'client_area_toggle',
            ],
            'tawk_to_dev' => [
                'field' => 'tawk_to_dev',
                'label' => 'Tawk.to Development',
                'event' => 'tawk_to_toggle',
            ],
            'tawk_to_prod' => [
                'field' => 'tawk_to_prod',
                'label' => 'Tawk.to Production',
                'event' => 'tawk_to_toggle',
            ],
            default => [
                'field' => 'client_area_dev',
                'label' => 'Client Area Development',
                'event' => 'client_area_toggle',
            ],
        };
    }

    private function normalizeNullableString(mixed $value): ?string
    {
        if (! is_string($value)) {
            return null;
        }

        $trimmed = trim($value);

        return $trimmed === '' ? null : $trimmed;
    }
}
