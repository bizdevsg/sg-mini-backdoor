<?php

namespace App\Http\Requests\Ebook;

use App\Models\Ebook;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;
use Illuminate\Validation\Rule;

class StoreEbookRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, \Illuminate\Contracts\Validation\ValidationRule|string>>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:150'],
            'slug' => ['nullable', 'string', 'max:500', Rule::unique('ebooks', 'slug')],
            'description' => ['required', 'string'],
            'image' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,avif', 'max:5120'],
            'file' => ['required', File::types(['pdf'])->max('20mb')],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'slug' => Ebook::generateSlug($this->string('title')->toString()),
        ]);
    }
}
