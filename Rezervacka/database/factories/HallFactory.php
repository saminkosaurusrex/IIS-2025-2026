<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Hall>
 */
class HallFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    public function definition(): array
    {
        $names = ["Hall A", "Hall B", "Hall C", "Big Hall", "Conference Room 1", "Conference Room 2", "Main Hall"];
        return [
            'name' => $this->faker->unique()->randomElement($names),
            'description' => $this->faker->sentence(30),
            'address' => $this->faker->address(),
            'rows' => $this->faker->numberBetween(6, 10),
            'columns' => $this->faker->numberBetween(6, 10),
        ];
    }
}
