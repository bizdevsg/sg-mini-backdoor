<?php

namespace Database\Factories;

use App\Models\EbookCategory;
use App\Models\Ebook;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Ebook>
 */
class EbookFactory extends Factory
{
    protected $model = Ebook::class;

    /**
     * @return array<string, string|null>
     */
    public function definition(): array
    {
        $title = fake()->unique()->sentence(4);

        return [
            'title' => $title,
            'ebook_category_id' => EbookCategory::factory(),
            'slug' => Ebook::generateSlug($title),
            'description' => fake()->paragraphs(3, true),
            'image' => 'https://picsum.photos/seed/' . fake()->unique()->slug() . '/1200/800',
            'file' => 'ebook-files/' . fake()->unique()->slug() . '.pdf',
        ];
    }
}
