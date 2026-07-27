<?php

namespace App\Support;

use App\Models\ClientAreaSetting;
use Illuminate\Support\Facades\Schema;

class ClientAreaSettingStore
{
    /**
     * @return array{
     *     client_area_dev: bool,
     *     client_area_prod: bool
     * }
     */
    public function get(): array
    {
        $defaults = [
            'client_area_dev' => false,
            'client_area_prod' => false,
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
            ]),
        ]);
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public function put(array $payload): void
    {
        $settings = $this->normalize([
            ...$this->get(),
            ...$payload,
        ]);

        if (! Schema::hasTable('client_area_settings')) {
            return;
        }

        $record = ClientAreaSetting::query()->first() ?? new ClientAreaSetting();
        $record->fill($settings);
        $record->save();
    }

    public function set(string $target, bool $enabled): void
    {
        $field = $target === 'prod' ? 'client_area_prod' : 'client_area_dev';

        $this->put([
            $field => $enabled,
        ]);
    }

    /**
     * @param  array<string, mixed>  $settings
     * @return array{
     *     client_area_dev: bool,
     *     client_area_prod: bool
     * }
     */
    private function normalize(array $settings): array
    {
        return [
            'client_area_dev' => (bool) ($settings['client_area_dev'] ?? false),
            'client_area_prod' => (bool) ($settings['client_area_prod'] ?? false),
        ];
    }
}
