<?php

namespace App\Http\Resources;

use App\Models\WakilPialang;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\WakilPialang */
class WakilPialangResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'nama' => $this->nama,
            'no_identitas' => $this->no_identitas,
            'status' => $this->status,
            'status_label' => WakilPialang::STATUS_OPTIONS[$this->status] ?? $this->status,
            'category' => $this->whenLoaded('category', fn () => [
                'id' => $this->category->id,
                'slug' => $this->category->slug,
                'nama_kategori' => $this->category->nama_kategori,
                'alamat_kantor_cabang' => $this->category->alamat_kantor_cabang,
                'telp' => $this->category->telp,
                'link_google_maps' => $this->category->link_google_maps,
            ]),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
