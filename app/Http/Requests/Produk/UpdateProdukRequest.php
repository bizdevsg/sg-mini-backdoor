<?php

namespace App\Http\Requests\Produk;

use App\Models\Produk;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProdukRequest extends FormRequest
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
            'nama_produk' => ['required', 'string', 'max:100'],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('produks', 'slug')->ignore($this->route('produk')),
            ],
            'deskripsi_produk' => ['required', 'string'],
            'specs' => ['required', 'string'],
            'image' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,avif', 'max:5120'],
            'kategori' => ['required', Rule::in(['SPA', 'JFX'])],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'slug' => Produk::generateSlug(
                $this->string('nama_produk')->toString(),
                $this->route('produk')
            ),
        ]);
    }
}
