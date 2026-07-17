<?php

namespace Database\Seeders;

use App\Models\EbookCategory;
use App\Support\ApiJsonCacheService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class EbookCategorySeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * @var list<string>
     */
    private array $categories = [
        'Commodity',
        'Forex',
        'Crypto',
        'Stocks',
        'Financial Literacy',
    ];

    public function run(): void
    {
        foreach ($this->categories as $name) {
            EbookCategory::query()->updateOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name],
            );
        }

        app(ApiJsonCacheService::class)->refreshEbookCategories();
    }
}
