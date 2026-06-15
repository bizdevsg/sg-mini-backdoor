<?php

namespace Database\Factories;

use App\Models\Penghargaan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Penghargaan>
 */
class PenghargaanFactory extends Factory
{
    protected $model = Penghargaan::class;

    /**
     * @return array<string, string|null>
     */
    public function definition(): array
    {
        $title = fake()->unique()->sentence(4);

        return [
            'title' => $title,
            'subtitle' => fake()->sentence(10),
            'slug' => Penghargaan::generateSlug($title),
            'image' => 'https://picsum.photos/seed/' . fake()->unique()->slug() . '/1200/800',
        ];
    }
}
