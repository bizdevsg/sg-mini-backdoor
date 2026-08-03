<?php

namespace Database\Factories;

use App\Models\TradingviewSymbol;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TradingviewSymbol>
 */
class TradingviewSymbolFactory extends Factory
{
    protected $model = TradingviewSymbol::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $code = fake()->unique()->currencyCode() . fake()->numberBetween(1, 999);

        return [
            'name' => fake()->words(2, true),
            'symbol_ws' => $code,
            'symbol_tv' => 'IDX:' . $code,
        ];
    }
}
