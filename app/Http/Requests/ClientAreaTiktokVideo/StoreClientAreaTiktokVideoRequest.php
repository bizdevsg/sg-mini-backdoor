<?php

namespace App\Http\Requests\ClientAreaTiktokVideo;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreClientAreaTiktokVideoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('manage-user-management') ?? false;
    }

    /**
     * @return array<string, array<int, \Closure|ValidationRule|string>>
     */
    public function rules(): array
    {
        return [
            'video_id' => ['required', 'string', 'max:30', 'regex:/^[0-9]+$/'],
            'video_url' => [
                'required',
                'string',
                'max:500',
                'url:http,https',
                function (string $attribute, mixed $value, \Closure $fail): void {
                    if (! is_string($value) || self::extractVideoId($value) === null) {
                        $fail('URL video harus menggunakan format TikTok /@username/video/{video_id}.');
                    }
                },
            ],
            'title' => ['required', 'string', 'max:150'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $submittedVideoUrl = $this->input('video_url');
        $videoUrl = is_string($submittedVideoUrl) ? trim($submittedVideoUrl) : '';

        $this->merge([
            'video_url' => $videoUrl,
            'video_id' => self::extractVideoId($videoUrl),
            'is_active' => $this->boolean('is_active'),
        ]);
    }

    private static function extractVideoId(string $videoUrl): ?string
    {
        if (preg_match('~^https?://(?:www\.|m\.)?tiktok\.com/@[^/]+/video/([0-9]{1,30})(?:[/?#].*)?$~i', $videoUrl, $matches) !== 1) {
            return null;
        }

        return $matches[1];
    }
}
