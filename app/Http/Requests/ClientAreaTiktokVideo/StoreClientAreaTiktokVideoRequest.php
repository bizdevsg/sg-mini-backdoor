<?php

namespace App\Http\Requests\ClientAreaTiktokVideo;

use App\Models\ClientAreaTiktokVideo;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'platform' => ['required', Rule::in(ClientAreaTiktokVideo::PLATFORMS)],
            'video_id' => ['required', 'string', 'max:30', 'regex:/^[A-Za-z0-9_-]+$/'],
            'video_url' => [
                'required',
                'string',
                'max:500',
                'url:http,https',
                function (string $attribute, mixed $value, \Closure $fail): void {
                    if ($this->input('video_id') === null) {
                        $fail('Link video tidak valid. Tempel URL/embed YouTube (https://www.youtube.com/embed/ID) atau URL video TikTok (https://www.tiktok.com/@username/video/ID).');
                    }
                },
            ],
            'title' => ['required', 'string', 'max:150'],
            'video_file' => ['nullable', 'file', 'mimes:mp4', 'extensions:mp4', 'max:102400'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $submitted = $this->input('video_url');
        $detected = is_string($submitted) ? ClientAreaTiktokVideo::detect($submitted) : null;

        $this->merge([
            'platform' => $detected['platform'] ?? null,
            'video_url' => $detected['video_url'] ?? (is_string($submitted) ? trim($submitted) : ''),
            'video_id' => $detected['video_id'] ?? null,
            'is_active' => $this->boolean('is_active'),
        ]);
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'video_id.required' => 'Link video tidak valid. Tempel URL/embed YouTube (https://www.youtube.com/embed/ID) atau URL video TikTok (https://www.tiktok.com/@username/video/ID).',
            'platform.required' => 'Link video tidak valid. Tempel URL/embed YouTube (https://www.youtube.com/embed/ID) atau URL video TikTok (https://www.tiktok.com/@username/video/ID).',
            'video_id.regex' => 'Link video tidak valid. Tempel URL/embed YouTube (https://www.youtube.com/embed/ID) atau URL video TikTok (https://www.tiktok.com/@username/video/ID).',
            'video_url.url' => 'Link video tidak valid. Tempel URL/embed YouTube (https://www.youtube.com/embed/ID) atau URL video TikTok (https://www.tiktok.com/@username/video/ID).',
        ];
    }
}
