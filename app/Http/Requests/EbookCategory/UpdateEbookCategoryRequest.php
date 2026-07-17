<?php

namespace App\Http\Requests\EbookCategory;

use App\Models\EbookCategory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEbookCategoryRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:100'],
            'slug' => [
                'nullable',
                'string',
                'max:120',
                Rule::unique('ebook_categories', 'slug')->ignore($this->route('ebookCategory')),
            ],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'slug' => EbookCategory::generateSlug(
                $this->string('name')->toString(),
                $this->route('ebookCategory')
            ),
        ]);
    }
}
