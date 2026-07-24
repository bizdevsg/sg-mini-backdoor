<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Berita */
class BeritaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title_id,
            'title_id' => $this->title_id,
            'title_en' => $this->title_en,
            'author' => $this->author,
            'source' => $this->source,
            'kategori' => $this->category?->name,
            'category' => $this->category ? [
                'id' => $this->category->id,
                'name' => $this->category->name,
                'slug' => $this->category->slug,
            ] : null,
            'slug' => $this->slug,
            'content' => $this->content_for_display,
            'content_id' => $this->content_for_display,
            'content_en' => $this->content_en_for_display,
            'image' => $this->image,
            'image_url' => $this->image_url,
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
