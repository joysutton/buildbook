<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'project_id' => Project::factory(),
            'title' => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'share' => fake()->boolean(),
            'due_date' => fake()->optional()->dateTimeBetween('now', '+2 months'),
            'completion_date' => fake()->optional()->dateTimeBetween('-1 month', 'now'),
        ];
    }
}
