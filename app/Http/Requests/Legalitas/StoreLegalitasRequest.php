<?php

namespace App\Http\Requests\Legalitas;

use App\Models\Legalitas;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreLegalitasRequest extends FormRequest
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
            'nomor' => ['required', 'string', 'max:150'],
            'description' => ['required', 'string', 'max:5000'],
            'slug' => ['nullable', 'string', 'max:500', Rule::unique('legalitas', 'slug')],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'slug' => Legalitas::generateSlug($this->string('title')->toString()),
        ]);
    }
}
