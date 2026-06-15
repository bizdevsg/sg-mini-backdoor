<?php

namespace Database\Factories;

use App\Models\Produk;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Produk>
 */
class ProdukFactory extends Factory
{
    protected $model = Produk::class;

    /**
     * @return array<string, string>
     */
    public function definition(): array
    {
        $namaProduk = fake()->unique()->words(3, true);

        return [
            'nama_produk' => Str::title($namaProduk),
            'slug' => Str::slug($namaProduk).'-'.fake()->unique()->numberBetween(100, 999),
            'deskripsi_produk' => fake()->paragraphs(3, true),
            'specs' => fake()->sentences(4, true),
            'image' => 'https://picsum.photos/seed/'.fake()->unique()->slug().'/1200/800',
            'kategori' => fake()->randomElement(['SPA', 'JFX']),
        ];
    }
}
