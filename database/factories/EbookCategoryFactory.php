<?php

namespace Database\Factories;

use App\Models\EbookCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<EbookCategory>
 */
class EbookCategoryFactory extends Factory
{
    protected $model = EbookCategory::class;

    /**
     * @return array<string, string>
     */
    public function definition(): array
    {
        $name = fake()->unique()->randomElement([
            'Trading Dasar',
            'Analisis Pasar',
            'Psikologi Trading',
            'Strategi Investasi',
            'Edukasi',
        ]);

        return [
            'name' => $name,
            'slug' => EbookCategory::generateSlug($name),
        ];
    }
}
