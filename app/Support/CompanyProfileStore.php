<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;

class CompanyProfileStore
{
    private const PATH = 'company-profile.json';

    /**
     * @return array{
     *     company_name: string,
     *     description: string,
     *     description_en: string,
     *     mission: array<int, string>,
     *     mission_en: array<int, string>,
     *     vision: array<int, string>,
     *     vision_en: array<int, string>,
     *     address: string,
     *     maps_embed_url: string,
     *     phone: string,
     *     email: string,
     *     fax: string,
     *     complaint_link: string
     * }
     */
    public function get(): array
    {
        $defaults = [
            'company_name' => '',
            'description' => '',
            'description_en' => '',
            'mission' => [],
            'mission_en' => [],
            'vision' => [],
            'vision_en' => [],
            'address' => '',
            'maps_embed_url' => '',
            'phone' => '',
            'email' => '',
            'fax' => '',
            'complaint_link' => '',
            ...config('company-profile', []),
        ];

        if (! Storage::disk('local')->exists(self::PATH)) {
            return $this->normalize($defaults);
        }

        $data = json_decode((string) Storage::disk('local')->get(self::PATH), true);

        if (! is_array($data)) {
            return $this->normalize($defaults);
        }

        return $this->normalize([
            ...$defaults,
            ...$data,
        ]);
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public function put(array $payload): void
    {
        $profile = $this->normalize([
            ...$this->get(),
            ...$payload,
        ]);

        Storage::disk('local')->put(
            self::PATH,
            json_encode($profile, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
        );
    }

    /**
     * @param  array<string, mixed>  $profile
     * @return array{
     *     company_name: string,
     *     description: string,
     *     description_en: string,
     *     mission: array<int, string>,
     *     mission_en: array<int, string>,
     *     vision: array<int, string>,
     *     vision_en: array<int, string>,
     *     address: string,
     *     maps_embed_url: string,
     *     phone: string,
     *     email: string,
     *     fax: string,
     *     complaint_link: string
     * }
     */
    private function normalize(array $profile): array
    {
        return [
            'company_name' => trim((string) ($profile['company_name'] ?? '')),
            'description' => trim((string) ($profile['description'] ?? '')),
            'description_en' => trim((string) ($profile['description_en'] ?? '')),
            'mission' => $this->normalizeList($profile['mission'] ?? []),
            'mission_en' => $this->normalizeList($profile['mission_en'] ?? []),
            'vision' => $this->normalizeList($profile['vision'] ?? []),
            'vision_en' => $this->normalizeList($profile['vision_en'] ?? []),
            'address' => trim((string) ($profile['address'] ?? '')),
            'maps_embed_url' => trim((string) ($profile['maps_embed_url'] ?? '')),
            'phone' => trim((string) ($profile['phone'] ?? '')),
            'email' => trim((string) ($profile['email'] ?? '')),
            'fax' => trim((string) ($profile['fax'] ?? '')),
            'complaint_link' => trim((string) ($profile['complaint_link'] ?? '')),
        ];
    }

    /**
     * @param  array<int, mixed>|string  $items
     * @return array<int, string>
     */
    private function normalizeList(array|string $items): array
    {
        if (is_string($items)) {
            $items = preg_split('/\r\n|\r|\n/', $items) ?: [];
        }

        return array_values(array_filter(array_map(
            static fn (mixed $item) => trim((string) $item),
            $items
        )));
    }
}
