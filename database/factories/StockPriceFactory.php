<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\StockPrice>
 */
class StockPriceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'company_id' => 1,
            'price' => $this->faker->randomFloat(2, 1, 1000),
            'open' => $this->faker->randomFloat(2, 1, 1000),
            'high' => $this->faker->randomFloat(2, 1, 1000),
            'low' => $this->faker->randomFloat(2, 1, 1000),
            'volume' => $this->faker->randomNumber(6),
            'previous_close' => $this->faker->randomFloat(2, 1, 1000),
            'change' => $this->faker->randomFloat(2, 1, 1000),
            'change_percent' => $this->faker->randomFloat(2, 1, 1000),
            'symbol' => $this->faker->regexify('[A-Z]{3,5}'),
            'date' => $this->faker->date(),
        ];
    }
}
