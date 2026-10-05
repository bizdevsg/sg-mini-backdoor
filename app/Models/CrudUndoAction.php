<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'user_id',
    'token_hash',
    'route_name',
    'action',
    'model_type',
    'model_key',
    'before_snapshot',
    'after_snapshot',
    'files',
    'redirect_url',
    'expires_at',
    'retention_until',
    'used_at',
])]
class CrudUndoAction extends Model
{
    protected function casts(): array
    {
        return [
            'before_snapshot' => 'encrypted:array',
            'after_snapshot' => 'encrypted:array',
            'files' => 'encrypted:array',
            'expires_at' => 'datetime',
            'retention_until' => 'datetime',
            'used_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
