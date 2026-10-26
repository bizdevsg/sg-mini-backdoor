<?php

namespace App\Http\Resources;

use App\Models\ClientAreaTiktokVideo;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin ClientAreaTiktokVideo */
class ClientAreaTiktokVideoResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'video_id' => $this->video_id,
            'video_url' => $this->video_url,
            'embed_url' => "https://www.tiktok.com/player/v1/{$this->video_id}",
            'title' => $this->title,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
