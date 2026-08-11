<?php

namespace App\Models;

use App\Support\ImagePath;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

#[Fillable([
    'category_id',
    'slug',
    'potensi',
    'timeframe',
    'confident',
    'entry',
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

    public const TIMEFRAME_EXPIRATION_MINUTES = [
        '1M' => 15,
        '5M' => 60,
        '15M' => 180,
        '30M' => 240,
        '1H' => 300,
        '4H' => 1080,
        '1D' => 1440,
        '1W' => 5760,
    ];

    protected $table = 'signals';

    protected $attributes = [
        'potensi' => 'buy',
        'timeframe' => '15M',
        'confident' => '',
        'entry' => '',
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
                'entry',
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

    public function expirationMinutes(): ?int
    {
        return static::TIMEFRAME_EXPIRATION_MINUTES[$this->timeframe] ?? null;
    }

    public function signalExpiresAt(): ?Carbon
    {
        $expirationMinutes = $this->expirationMinutes();

        if ($this->created_at === null || $expirationMinutes === null) {
            return null;
        }

        return $this->created_at->copy()->addMinutes($expirationMinutes);
    }

    public function signalRemainingSeconds(?Carbon $reference = null): ?int
    {
        $expiresAt = $this->signalExpiresAt();

        if ($expiresAt === null) {
            return null;
        }

        $reference ??= now();

        return max(0, $reference->diffInSeconds($expiresAt, false));
    }

    public function signalHasExpired(?Carbon $reference = null): bool
    {
        $expiresAt = $this->signalExpiresAt();

        if ($expiresAt === null) {
            return false;
        }

        $reference ??= now();

        return $reference->greaterThanOrEqualTo($expiresAt);
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
