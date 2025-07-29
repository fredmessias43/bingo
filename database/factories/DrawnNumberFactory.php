<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DrawnNumber>
 */
class DrawnNumberFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'game_id' => \App\Models\Game::factory(),
            'number' => $this->faker->unique()->numberBetween(1, 75),
            'drawn_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
        ];
    }
}
