<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Project;
use App\Models\Task;
use App\Models\Material;
use App\Models\Note;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // Create a main test user
        $user = User::factory()->create([
            'username' => 'testuser',
            'email' => 'test@example.com',
        ]);

        // Create additional users
        User::factory(4)->create();

        // For each user, create projects with tasks, materials, and notes
        User::all()->each(function ($user) {
            $projects = Project::factory(3)->for($user)->create();
            foreach ($projects as $project) {
                // Add notes to project
                Note::factory(2)->for($project, 'noteable')->create();
                // Add tasks to project
                $tasks = Task::factory(3)->for($project)->create();
                foreach ($tasks as $task) {
                    // Add notes to task
                    Note::factory(2)->for($task, 'noteable')->create();
                }
                // Add materials to project
                $materials = Material::factory(3)->for($project)->create();
                foreach ($materials as $material) {
                    // Add notes to material
                    Note::factory(2)->for($material, 'noteable')->create();
                }
            }
        });
    }
}
