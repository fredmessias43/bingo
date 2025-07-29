<?php

namespace Database\Factories;

use App\Enums\GameStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Game>
 */
class GameFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(),
            'status' => $this->faker->randomElement(GameStatus::values()),
            'max_players' => $this->faker->numberBetween(2, 100),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
