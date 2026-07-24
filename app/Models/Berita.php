<?php

namespace App\Models;

use App\Support\ImagePath;
use DOMDocument;
use DOMElement;
use DOMNode;
use DOMXPath;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;

#[Fillable([
    'berita_category_id',
    'author',
    'source',
    'title_id',
    'title_en',
    'slug',
    'content_id',
    'content_en',
    'image',
])]
class Berita extends Model
{
    use HasFactory;

    protected static array $columnExists = [];

    protected $table = 'beritas';

    protected $appends = [
        'kategori',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public static function generateSlug(string $title, ?self $ignore = null): string
    {
        $baseSlug = Str::slug($title);

        if ($baseSlug === '') {
            $baseSlug = 'berita';
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
        return $this->belongsTo(BeritaCategory::class, 'berita_category_id');
    }

    public function scopeApplySearch(Builder $query, string $search): Builder
    {
        if (trim($search) === '') {
            return $query;
        }

        $columns = static::searchableColumns();

        return $query->where(function (Builder $subQuery) use ($search, $columns) {
            foreach ($columns as $index => $column) {
                $method = $index === 0 ? 'where' : 'orWhere';
                $subQuery->{$method}($column, 'like', "%{$search}%");
            }
        });
    }

    public function scopeOrderForListing(Builder $query): Builder
    {
        return $query->latest();
    }

    public static function searchableColumns(): array
    {
        $columns = [
            static::hasDatabaseColumn('title_id') ? 'title_id' : 'title',
            'slug',
        ];

        if (static::hasDatabaseColumn('title_en')) {
            $columns[] = 'title_en';
        }

        if (static::hasDatabaseColumn('author')) {
            $columns[] = 'author';
        }

        if (static::hasDatabaseColumn('source')) {
            $columns[] = 'source';
        }

        $columns[] = static::hasDatabaseColumn('content_id') ? 'content_id' : 'content';

        if (static::hasDatabaseColumn('content_en')) {
            $columns[] = 'content_en';
        }

        return array_values(array_unique(array_filter($columns)));
    }

    /**
     * @param  array<string, mixed>  $attributes
     * @return array<string, mixed>
     */
    public static function prepareForPersistence(array $attributes): array
    {
        $payload = [];

        foreach ($attributes as $key => $value) {
            if (static::hasDatabaseColumn($key)) {
                $payload[$key] = $value;
            }
        }

        if (static::hasDatabaseColumn('title') && array_key_exists('title_id', $attributes)) {
            $payload['title'] = $attributes['title_id'];
        }

        if (static::hasDatabaseColumn('content') && array_key_exists('content_id', $attributes)) {
            $payload['content'] = $attributes['content_id'];
        }

        return $payload;
    }

    public static function hasDatabaseColumn(string $column): bool
    {
        $key = static::class . ':' . $column;

        return static::$columnExists[$key] ??= Schema::hasColumn((new static)->getTable(), $column);
    }

    protected function kategori(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->category?->name,
        );
    }

    protected function title(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value, array $attributes) => $attributes['title_id'] ?? $value,
        );
    }

    protected function titleId(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value, array $attributes) => $value ?? $attributes['title'] ?? null,
        );
    }

    protected function titleEn(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value, array $attributes) => $value ?? $attributes['title_id'] ?? $attributes['title'] ?? null,
        );
    }

    protected function content(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value, array $attributes) => $attributes['content_id'] ?? $value,
        );
    }

    protected function contentId(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value, array $attributes) => $value ?? $attributes['content'] ?? null,
        );
    }

    protected function contentEn(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value, array $attributes) => $value ?? $attributes['content_id'] ?? $attributes['content'] ?? null,
        );
    }

    protected function author(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value) => $value ?? '-',
        );
    }

    protected function source(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value) => $value ?? '-',
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

                if (Str::startsWith($image, ['http://', 'https://', '/'])) {
                    return $image;
                }

                return asset('storage/' . ltrim((string) ImagePath::normalize($image), '/'));
            },
        );
    }

    protected function contentForDisplay(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->cleanEditorHtml($this->content_id),
        );
    }

    protected function contentEnForDisplay(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->cleanEditorHtml($this->content_en),
        );
    }

    private function cleanEditorHtml(?string $content): string
    {
        $html = trim((string) $content);

        if ($html === '') {
            return '<p>Tidak ada konten.</p>';
        }

        $previousState = libxml_use_internal_errors(true);

        $document = new DOMDocument('1.0', 'UTF-8');
        $document->loadHTML(
            '<?xml encoding="utf-8" ?><div id="content-root">' . $html . '</div>',
            LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
        );

        $xpath = new DOMXPath($document);

        foreach ($xpath->query('//temporary | //span[contains(@class, "ql-ui")]') as $node) {
            $node?->parentNode?->removeChild($node);
        }

        foreach ($xpath->query('//*[@data-row or @data-cell or @contenteditable or @height]') as $element) {
            if (! $element instanceof DOMElement) {
                continue;
            }

            $element->removeAttribute('data-row');
            $element->removeAttribute('data-cell');
            $element->removeAttribute('contenteditable');
            $element->removeAttribute('height');
        }

        foreach ($xpath->query('//*[@class]') as $element) {
            if (! $element instanceof DOMElement) {
                continue;
            }

            $classes = collect(preg_split('/\s+/', trim((string) $element->getAttribute('class'))))
                ->filter()
                ->reject(fn (string $class) => in_array($class, [
                    'ql-cell-focused',
                    'ql-cell-selected',
                    'ql-cell-selected-after',
                    'ql-table-block',
                    'table-list',
                    'table-list-container',
                ], true))
                ->map(fn (string $class) => $class === 'ql-table-better' ? 'informasi-content-table' : $class)
                ->values()
                ->all();

            if ($classes === []) {
                $element->removeAttribute('class');
                continue;
            }

            $element->setAttribute('class', implode(' ', $classes));
        }

        $root = $document->getElementById('content-root');

        if (! $root instanceof DOMElement) {
            libxml_clear_errors();
            libxml_use_internal_errors($previousState);

            return $html;
        }

        $output = '';

        foreach ($root->childNodes as $childNode) {
            if (! $childNode instanceof DOMNode) {
                continue;
            }

            $output .= $document->saveHTML($childNode);
        }

        libxml_clear_errors();
        libxml_use_internal_errors($previousState);

        return $output !== '' ? $output : '<p>Tidak ada konten.</p>';
    }
}
