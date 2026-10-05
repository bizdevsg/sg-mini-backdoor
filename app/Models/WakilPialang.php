<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

#[Fillable([
    'slug',
    'nama',
    'no_identitas',
    'id_kategori',
    'status',
])]
class WakilPialang extends Model
{
    public const STATUS_OPTIONS = [
        'aktif' => 'Aktif',
        'tidak_aktif' => 'Tidak Aktif',
    ];

    protected $table = 'wakil_pialangs';

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public static function generateSlug(string $name, ?self $ignore = null): string
    {
        $baseSlug = Str::slug($name);

        if ($baseSlug === '') {
            $baseSlug = 'wakil-pialang';
        }

        $slug = $baseSlug;
        $counter = 2;

        while (static::query()
            ->when($ignore, fn ($query) => $query->whereKeyNot($ignore->getKey()))
            ->where('slug', $slug)
            ->exists()) {
            $slug = $baseSlug.'-'.$counter;
            $counter++;
        }

        return $slug;
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(WakilPialangCategory::class, 'id_kategori');
    }
}
