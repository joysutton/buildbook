<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Material>
 */
class MaterialFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $materialTypes = [
            'Cotton Fabric' => ['amount' => fake()->numberBetween(1, 5) . ' yards', 'est_cost' => fake()->numberBetween(800, 2500)],
            'Silk Fabric' => ['amount' => fake()->numberBetween(1, 3) . ' yards', 'est_cost' => fake()->numberBetween(1500, 4000)],
            'Thread' => ['amount' => fake()->numberBetween(1, 10) . ' spools', 'est_cost' => fake()->numberBetween(200, 800)],
            'Zipper' => ['amount' => fake()->numberBetween(1, 5) . ' pieces', 'est_cost' => fake()->numberBetween(300, 1200)],
            'Buttons' => ['amount' => fake()->numberBetween(4, 20) . ' pieces', 'est_cost' => fake()->numberBetween(400, 1500)],
            'Interfacing' => ['amount' => fake()->numberBetween(1, 3) . ' yards', 'est_cost' => fake()->numberBetween(500, 1500)],
            'Lining' => ['amount' => fake()->numberBetween(1, 4) . ' yards', 'est_cost' => fake()->numberBetween(600, 2000)],
            'Bias Tape' => ['amount' => fake()->numberBetween(1, 5) . ' yards', 'est_cost' => fake()->numberBetween(300, 1000)],
            'Elastic' => ['amount' => fake()->numberBetween(1, 3) . ' yards', 'est_cost' => fake()->numberBetween(400, 1200)],
            'Velcro' => ['amount' => fake()->numberBetween(1, 2) . ' yards', 'est_cost' => fake()->numberBetween(500, 1500)],
            'Ribbon' => ['amount' => fake()->numberBetween(1, 4) . ' yards', 'est_cost' => fake()->numberBetween(200, 800)],
            'Piping' => ['amount' => fake()->numberBetween(1, 3) . ' yards', 'est_cost' => fake()->numberBetween(600, 1800)],
        ];

        $materialType = fake()->randomElement(array_keys($materialTypes));
        $materialData = $materialTypes[$materialType];

        return [
            'name' => $materialType,
            'description' => fake()->optional()->sentence(),
            'amount' => $materialData['amount'],
            'est_cost' => $materialData['est_cost'],
            'actual_cost' => fake()->optional()->numberBetween($materialData['est_cost'] * 0.8, $materialData['est_cost'] * 1.2),
            'source' => fake()->optional()->randomElement(['Local Fabric Store', 'Online Store', 'Craft Fair', 'Thrift Store', 'Garage Sale']),
            'acquired' => fake()->boolean(70), // 70% chance of being acquired
            'share' => fake()->boolean(80), // 80% chance of being shared
            'project_id' => Project::factory(),
        ];
    }

    /**
     * Indicate that the material has been acquired.
     */
    public function acquired(): static
    {
        return $this->state(fn (array $attributes) => [
            'acquired' => true,
        ]);
    }

    /**
     * Indicate that the material is shared.
     */
    public function shared(): static
    {
        return $this->state(fn (array $attributes) => [
            'share' => true,
        ]);
    }

    /**
     * Indicate that the material has actual cost data.
     */
    public function withActualCost(): static
    {
        return $this->state(fn (array $attributes) => [
            'actual_cost' => fake()->numberBetween($attributes['est_cost'] * 0.8, $attributes['est_cost'] * 1.2),
        ]);
    }
}
