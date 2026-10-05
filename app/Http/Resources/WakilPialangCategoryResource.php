<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\WakilPialangCategory */
class WakilPialangCategoryResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'nama_kategori' => $this->nama_kategori,
            'alamat_kantor_cabang' => $this->alamat_kantor_cabang,
            'telp' => $this->telp,
            'link_google_maps' => $this->link_google_maps,
            'wakil_pialangs_count' => $this->wakil_pialangs_count,
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
