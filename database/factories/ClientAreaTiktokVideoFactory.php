<?php

namespace Database\Factories;

use App\Models\ClientAreaTiktokVideo;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ClientAreaTiktokVideo>
 */
class ClientAreaTiktokVideoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $videoId = fake()->unique()->numerify('###################');

        return [
            'video_id' => $videoId,
            'video_url' => "https://www.tiktok.com/@solid.prime/video/{$videoId}",
            'title' => fake()->sentence(4),
            'is_active' => false,
        ];
    }

    public function active(): static
    {
        return $this->state(fn (): array => [
            'is_active' => true,
        ]);
    }
}
