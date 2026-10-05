<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

#[Fillable([
    'slug',
    'nama_kategori',
    'alamat_kantor_cabang',
    'telp',
    'link_google_maps',
])]
class WakilPialangCategory extends Model
{
    protected $table = 'wakil_pialang_categories';

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public static function generateSlug(string $name, ?self $ignore = null): string
    {
        $baseSlug = Str::slug($name);

        if ($baseSlug === '') {
            $baseSlug = 'kategori-wakil-pialang';
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

    public function wakilPialangs(): HasMany
    {
        return $this->hasMany(WakilPialang::class, 'id_kategori');
    }
}
