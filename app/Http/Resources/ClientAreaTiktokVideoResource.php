<?php

namespace App\Http\Resources;

use App\Models\ClientAreaTiktokVideo;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

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
            'platform' => $this->platform,
            'video_id' => $this->video_id,
            'video_url' => $this->video_url,
            'embed_url' => ClientAreaTiktokVideo::embedUrl($this->platform, $this->video_id),
            'autoplay_embed_url' => ClientAreaTiktokVideo::autoplayEmbedUrl($this->platform, $this->video_id),
            'file_url' => $this->file_path ? Storage::disk('public')->url($this->file_path) : null,
            'title' => $this->title,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
