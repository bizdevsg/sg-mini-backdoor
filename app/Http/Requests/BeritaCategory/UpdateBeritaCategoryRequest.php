<?php

namespace App\Http\Requests\BeritaCategory;

use App\Models\BeritaCategory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBeritaCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'slug' => [
                'nullable',
                'string',
                'max:120',
                Rule::unique('berita_categories', 'slug')->ignore($this->route('beritaCategory')),
            ],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'slug' => BeritaCategory::generateSlug(
                $this->string('name')->toString(),
                $this->route('beritaCategory')
            ),
        ]);
    }
}
