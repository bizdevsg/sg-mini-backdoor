<?php

namespace App\Models;

use App\Support\ImagePath;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

#[Fillable([
    'category_id',
    'slug',
    'potensi',
    'timeframe',
    'confident',
    'taking_profit',
    'stop_loss',
    'sumber',
    'image',
])]
class Signal extends Model
{
    use HasFactory;

    public const POTENSI_OPTIONS = ['buy', 'sell'];

    public const TIMEFRAME_OPTIONS = ['15M', '30M', '1H', '4H', '1D'];

    protected $table = 'signals';

    protected $attributes = [
        'potensi' => 'buy',
        'timeframe' => '15M',
        'confident' => '',
        'taking_profit' => '',
        'stop_loss' => '',
        'sumber' => '',
    ];

    protected $appends = [
        'kategori',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public static function generateSlug(string $categoryName, string $potensi, string $timeframe, ?self $ignore = null): string
    {
        $baseSlug = Str::slug($categoryName . '-' . $potensi . '-' . $timeframe);

        if ($baseSlug === '') {
            $baseSlug = 'signal';
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

    public function category(): BelongsTo
    {
        return $this->belongsTo(SignalCategory::class, 'category_id');
    }

    public function scopeApplySearch(Builder $query, string $search): Builder
    {
        if (trim($search) === '') {
            return $query;
        }

        return $query->where(function (Builder $subQuery) use ($search) {
            foreach ([
                'potensi',
                'timeframe',
                'confident',
                'taking_profit',
                'stop_loss',
                'sumber',
            ] as $index => $column) {
                $method = $index === 0 ? 'where' : 'orWhere';
                $subQuery->{$method}($column, 'like', "%{$search}%");
            }

            $subQuery->orWhereHas('category', function (Builder $categoryQuery) use ($search) {
                $categoryQuery->where('name', 'like', "%{$search}%");
            });
        });
    }

    public function scopeOrderForListing(Builder $query): Builder
    {
        return $query->latest();
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
            get: function () {
                $image = trim((string) $this->image);

                if ($image === '') {
                    return null;
                }

                if (str_starts_with($image, 'http://') || str_starts_with($image, 'https://') || str_starts_with($image, '/')) {
                    return $image;
                }

                return asset('storage/' . ltrim((string) ImagePath::normalize($image), '/'));
            },
        );
    }
}
