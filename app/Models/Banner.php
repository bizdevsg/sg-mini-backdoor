<?php

namespace App\Models;

use App\Support\ImagePath;
use Database\Factories\BannerFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

#[Fillable([
    'title',
    'slug',
    'image',
    'terms_and_conditions',
    'is_active',
    'sort_order',
])]
class Banner extends Model
{
    /** @use HasFactory<BannerFactory> */
    use HasFactory;

    public function getRouteKeyName(): string
    {
        return $this->usesSlugRouteKey() ? 'slug' : $this->getKeyName();
    }

    public function getRouteKey(): mixed
    {
        if ($this->usesSlugRouteKey() && filled($this->slug)) {
            return $this->slug;
        }

        return $this->getKey();
    }

    public static function generateSlug(string $title, ?self $ignore = null): string
    {
        $baseSlug = Str::slug($title);

        if ($baseSlug === '') {
            $baseSlug = 'banner';
        }

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

    public function resolveRouteBindingQuery($query, $value, $field = null): Builder
    {
        if ($field !== null) {
            return parent::resolveRouteBindingQuery($query, $value, $field);
        }

        if (! $this->usesSlugRouteKey()) {
            return $query->whereKey($value);
        }

        return $query->where(function (Builder $builder) use ($value): void {
            $builder->where('slug', $value);

            if (is_numeric($value)) {
                $builder->orWhereKey($value);
            }
        });
    }

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
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

                return asset('storage/'.ltrim((string) ImagePath::normalize($image), '/'));
            },
        );
    }

    private function usesSlugRouteKey(): bool
    {
        return Schema::hasColumn($this->getTable(), 'slug');
    }
}
