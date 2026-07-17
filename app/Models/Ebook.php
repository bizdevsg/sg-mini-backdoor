<?php

namespace App\Models;

use App\Support\ImagePath;
use Database\Factories\EbookFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

#[Fillable([
    'title',
    'ebook_category_id',
    'slug',
    'description',
    'image',
    'file',
])]
class Ebook extends Model
{
    /** @use HasFactory<EbookFactory> */
    use HasFactory;

    protected $table = 'ebooks';

    protected $appends = [
        'kategori',
    ];

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

    public function category(): BelongsTo
    {
        return $this->belongsTo(EbookCategory::class, 'ebook_category_id');
    }

    protected function kategori(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->category?->name,
        );
    }

    protected function imageUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->assetUrl(ImagePath::normalize($this->image)),
        );
    }

    protected function fileUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->assetUrl($this->file),
        );
    }

    private function assetUrl(?string $path): ?string
    {
        $path = trim((string) $path);

        if ($path === '') {
            return null;
        }

        if (Str::startsWith($path, ['http://', 'https://', '/'])) {
            return $path;
        }

        return asset('storage/' . ltrim($path, '/'));
    }
}
