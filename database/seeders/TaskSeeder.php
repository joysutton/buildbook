<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a user and project first
        $user = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $user->id]);

        // Create 10 tasks for the project
        Task::factory(10)->create(['project_id' => $project->id]);

        // Create additional tasks for other projects
        User::factory(3)->create()->each(function ($user) {
            Project::factory(2)->create(['user_id' => $user->id])->each(function ($project) {
                Task::factory(rand(3, 8))->create(['project_id' => $project->id]);
            });
        });
    }
}
