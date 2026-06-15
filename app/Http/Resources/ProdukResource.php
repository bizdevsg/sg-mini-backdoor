<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Produk */
class ProdukResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nama_produk' => $this->nama_produk,
            'slug' => $this->slug,
            'deskripsi_produk' => $this->deskripsi_produk,
            'specs' => $this->specs_for_display,
            'image' => $this->image,
            'image_url' => $this->image_url,
            'kategori' => $this->kategori,
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
