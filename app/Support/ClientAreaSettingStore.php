<?php

namespace App\Support;

use App\Models\ClientAreaSetting;
use Illuminate\Support\Facades\Schema;

class ClientAreaSettingStore
{
    /**
     * @return array{
     *     client_area_dev: bool,
     *     client_area_prod: bool,
     *     tawk_to_dev: bool,
     *     tawk_to_prod: bool,
     *     api_enabled: bool,
     *     api_key_rotation_notice: string|null,
     *     allowed_origin_frontend: string|null
     * }
     */
    public function get(): array
    {
        $supportsLegacyTawkSetting = Schema::hasTable('client_area_settings')
            && Schema::hasColumn('client_area_settings', 'tawk_to_enabled');
        $supportsSplitTawkSettings = Schema::hasTable('client_area_settings')
            && Schema::hasColumn('client_area_settings', 'tawk_to_dev')
            && Schema::hasColumn('client_area_settings', 'tawk_to_prod');

        $defaults = [
            'client_area_dev' => false,
            'client_area_prod' => false,
            'tawk_to_dev' => false,
            'tawk_to_prod' => false,
            'api_enabled' => true,
            'api_key_rotation_notice' => null,
            'allowed_origin_frontend' => null,
            ...config('client-area', []),
        ];

        if (! Schema::hasTable('client_area_settings')) {
            return $this->normalize($defaults);
        }

        $settings = ClientAreaSetting::query()->first();

        if (! $settings instanceof ClientAreaSetting) {
            return $this->normalize($defaults);
        }

        return $this->normalize([
            ...$defaults,
            ...$settings->only([
                'client_area_dev',
                'client_area_prod',
                ...($supportsLegacyTawkSetting ? ['tawk_to_enabled'] : []),
                ...($supportsSplitTawkSettings ? ['tawk_to_dev', 'tawk_to_prod'] : []),
                'api_enabled',
                'api_key_rotation_notice',
                'allowed_origin_frontend',
            ]),
        ]);
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public function put(array $payload): void
    {
        if (! Schema::hasTable('client_area_settings')) {
            return;
        }

        $supportsLegacyTawkSetting = Schema::hasColumn('client_area_settings', 'tawk_to_enabled');
        $supportsSplitTawkSettings = Schema::hasColumn('client_area_settings', 'tawk_to_dev')
            && Schema::hasColumn('client_area_settings', 'tawk_to_prod');
        $supportsApiSecuritySettings = Schema::hasColumn('client_area_settings', 'api_enabled')
            && Schema::hasColumn('client_area_settings', 'api_key_rotation_notice')
            && Schema::hasColumn('client_area_settings', 'allowed_origin_frontend');
        $settings = $this->normalize([
            ...$this->get(),
            ...$payload,
        ]);

        $record = ClientAreaSetting::query()->first() ?? new ClientAreaSetting();

        $fillable = [
            'client_area_dev' => $settings['client_area_dev'],
            'client_area_prod' => $settings['client_area_prod'],
        ];

        if ($supportsApiSecuritySettings) {
            $fillable['api_enabled'] = $settings['api_enabled'];
            $fillable['api_key_rotation_notice'] = $settings['api_key_rotation_notice'];
            $fillable['allowed_origin_frontend'] = $settings['allowed_origin_frontend'];
        }

        if ($supportsLegacyTawkSetting) {
            $fillable['tawk_to_enabled'] = $settings['tawk_to_dev'] || $settings['tawk_to_prod'];
        }

        if ($supportsSplitTawkSettings) {
            $fillable['tawk_to_dev'] = $settings['tawk_to_dev'];
            $fillable['tawk_to_prod'] = $settings['tawk_to_prod'];
        }

        $record->fill($fillable);
        $record->save();
    }

    public function set(string $target, bool $enabled): void
    {
        $field = match ($target) {
            'prod' => 'client_area_prod',
            'tawk_to_dev' => 'tawk_to_dev',
            'tawk_to_prod' => 'tawk_to_prod',
            default => 'client_area_dev',
        };

        $this->put([
            $field => $enabled,
        ]);
    }

    /**
     * @param  array<string, mixed>  $settings
     * @return array{
     *     client_area_dev: bool,
     *     client_area_prod: bool,
     *     tawk_to_dev: bool,
     *     tawk_to_prod: bool,
     *     api_enabled: bool,
     *     api_key_rotation_notice: string|null,
     *     allowed_origin_frontend: string|null
     * }
     */
    private function normalize(array $settings): array
    {
        $legacyTawkEnabled = (bool) ($settings['tawk_to_enabled'] ?? false);
        $apiKeyRotationNotice = $this->normalizeNullableString($settings['api_key_rotation_notice'] ?? null);
        $allowedOriginFrontend = $this->normalizeNullableString($settings['allowed_origin_frontend'] ?? null);

        return [
            'client_area_dev' => (bool) ($settings['client_area_dev'] ?? false),
            'client_area_prod' => (bool) ($settings['client_area_prod'] ?? false),
            'tawk_to_dev' => array_key_exists('tawk_to_dev', $settings) && $settings['tawk_to_dev'] !== null
                ? (bool) $settings['tawk_to_dev']
                : $legacyTawkEnabled,
            'tawk_to_prod' => array_key_exists('tawk_to_prod', $settings) && $settings['tawk_to_prod'] !== null
                ? (bool) $settings['tawk_to_prod']
                : $legacyTawkEnabled,
            'api_enabled' => (bool) ($settings['api_enabled'] ?? true),
            'api_key_rotation_notice' => $apiKeyRotationNotice,
            'allowed_origin_frontend' => $allowedOriginFrontend,
        ];
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
