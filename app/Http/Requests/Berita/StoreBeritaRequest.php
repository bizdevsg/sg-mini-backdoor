<?php

namespace App\Http\Requests\Berita;

use App\Models\Berita;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBeritaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'author' => ['required', 'string', 'max:100'],
            'source' => ['required', 'string', 'max:150'],
            'title_id' => ['required', 'string', 'max:150'],
            'title_en' => ['required', 'string', 'max:150'],
            'slug' => ['nullable', 'string', 'max:160', Rule::unique('beritas', 'slug')],
            'content_id' => ['required', 'string'],
            'content_en' => ['required', 'string'],
            'image' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,avif', 'max:5120'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'slug' => Berita::generateSlug($this->string('title_id')->toString()),
        ]);
    }
}
