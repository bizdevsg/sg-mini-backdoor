<?php

namespace Database\Factories;

use App\Models\Banner;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Banner>
 */
class BannerFactory extends Factory
{
    protected $model = Banner::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->unique()->sentence(3);

        return [
            'title' => $title,
            'slug' => Banner::generateSlug($title),
            'image' => 'https://picsum.photos/seed/' . fake()->unique()->slug() . '/1600/900',
            'terms_and_conditions' => '<ul><li>Promo berlaku untuk nasabah terverifikasi.</li><li>Periode promo mengikuti informasi resmi perusahaan.</li><li>Syarat dan ketentuan dapat berubah sewaktu-waktu.</li></ul>',
            'is_active' => true,
            'sort_order' => fake()->numberBetween(0, 20),
        ];
    }
}
