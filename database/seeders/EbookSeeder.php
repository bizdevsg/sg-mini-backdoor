<?php

namespace Database\Seeders;

use App\Models\Ebook;
use App\Models\EbookCategory;
use App\Support\ApiJsonCacheService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EbookSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        EbookCategory::query()
            ->withCount('ebooks')
            ->get()
            ->each(function (EbookCategory $category): void {
                $missingEbooks = max(0, 5 - $category->ebooks_count);

                if ($missingEbooks === 0) {
                    return;
                }

                Ebook::factory()
                    ->count($missingEbooks)
                    ->create([
                        'ebook_category_id' => $category->id,
                    ]);
            });

        app(ApiJsonCacheService::class)->refreshEbook();
        app(ApiJsonCacheService::class)->refreshEbookCategories();
    }
}
