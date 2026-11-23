<?php

namespace App\Http\Requests\ClientAreaTiktokVideo;

use App\Models\ClientAreaTiktokVideo;
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
            'video_id' => ['required', 'string', 'size:11', 'regex:/^[A-Za-z0-9_-]+$/'],
            'video_url' => [
                'required',
                'string',
                'max:500',
                'url:http,https',
                function (string $attribute, mixed $value, \Closure $fail): void {
                    if ($this->input('video_id') === null) {
                        $fail('Embed YouTube tidak valid. Tempel URL embed (https://www.youtube.com/embed/ID) atau kode iframe dari YouTube.');
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
        $videoId = is_string($submitted) ? ClientAreaTiktokVideo::extractYoutubeId($submitted) : null;

        $this->merge([
            'video_url' => $videoId !== null
                ? ClientAreaTiktokVideo::youtubeEmbedUrl($videoId)
                : (is_string($submitted) ? trim($submitted) : ''),
            'video_id' => $videoId,
            'is_active' => $this->boolean('is_active'),
        ]);
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'video_id.required' => 'Embed YouTube tidak valid. Tempel URL embed (https://www.youtube.com/embed/ID) atau kode iframe dari YouTube.',
            'video_id.size' => 'Embed YouTube tidak valid. Tempel URL embed (https://www.youtube.com/embed/ID) atau kode iframe dari YouTube.',
            'video_id.regex' => 'Embed YouTube tidak valid. Tempel URL embed (https://www.youtube.com/embed/ID) atau kode iframe dari YouTube.',
            'video_url.url' => 'Embed YouTube tidak valid. Tempel URL embed (https://www.youtube.com/embed/ID) atau kode iframe dari YouTube.',
        ];
    }
}
