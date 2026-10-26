<?php

namespace App\Models;

use Database\Factories\ClientAreaTiktokVideoFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'video_id',
    'video_url',
    'title',
    'is_active',
])]
class ClientAreaTiktokVideo extends Model
{
    /** @use HasFactory<ClientAreaTiktokVideoFactory> */
    use HasFactory;

    protected $table = 'client_area_tiktok_videos';

    protected $attributes = [
        'is_active' => false,
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }
}
