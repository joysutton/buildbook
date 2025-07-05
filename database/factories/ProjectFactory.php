<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'name' => $this->faker->unique()->words(3, true),
            'series' => $this->faker->optional()->word(),
            'version' => $this->faker->optional()->randomElement(['1.0', '2.0', 'A', 'B']),
            'description' => $this->faker->optional()->paragraph(),
            'share' => $this->faker->boolean(),
        ];
    }
}
