<?php

namespace App\Http\Requests\WakilPialangCategory;

use App\Models\WakilPialangCategory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreWakilPialangCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama_kategori' => ['required', 'string', 'max:150'],
            'slug' => ['nullable', 'string', 'max:180', Rule::unique('wakil_pialang_categories', 'slug')],
            'alamat_kantor_cabang' => ['required', 'string', 'max:1000'],
            'telp' => ['required', 'string', 'max:30', 'regex:/^[0-9+\-\s().]+$/'],
            'link_google_maps' => ['required', 'url', 'max:2000'],
        ];
    }

    public function messages(): array
    {
        return [
            'telp.regex' => 'Format telp tidak valid.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'slug' => WakilPialangCategory::generateSlug($this->string('nama_kategori')->toString()),
        ]);
    }
}
