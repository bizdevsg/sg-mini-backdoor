<?php

namespace Database\Factories;

use App\Models\Informasi;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Informasi>
 */
class InformasiFactory extends Factory
{
    protected $model = Informasi::class;

    /**
     * @return array<string, string|null>
     */
    public function definition(): array
    {
        $title = fake()->unique()->sentence(4);

        return [
            'title' => $title,
            'slug' => Informasi::generateSlug($title),
            'content' => fake()->paragraphs(4, true),
            'image' => 'https://picsum.photos/seed/' . fake()->unique()->slug() . '/1200/800',
        ];
    }
}
