<?php

namespace App\Models;

use App\Support\ImagePath;
use Database\Factories\PenghargaanFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

#[Fillable([
    'title',
    'subtitle',
    'slug',
    'image',
])]
class Penghargaan extends Model
{
    /** @use HasFactory<PenghargaanFactory> */
    use HasFactory;

    protected $table = 'penghargaans';

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public static function generateSlug(string $title, ?self $ignore = null): string
    {
        $baseSlug = now()->format('dmYHi') . '-' . Str::slug($title);
        $slug = $baseSlug;
        $counter = 2;

        while (static::query()
            ->when($ignore, fn ($query) => $query->whereKeyNot($ignore->getKey()))
            ->where('slug', $slug)
            ->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    protected function imageUrl(): Attribute
    {
        return Attribute::make(
            get: function () {
                $image = trim((string) $this->image);

                if ($image === '') {
                    return null;
                }

                if (Str::startsWith($image, ['http://', 'https://', '/'])) {
                    return $image;
                }

                return asset('storage/' . ltrim((string) ImagePath::normalize($image), '/'));
            },
        );
    }
}
