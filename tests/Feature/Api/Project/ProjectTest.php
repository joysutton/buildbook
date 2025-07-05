<?php

use App\Models\Project;
use App\Models\User;
use App\Models\Task;
use App\Models\Material;
use App\Models\Note;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

describe('Project API', function () {
    it('can list user projects', function () {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        
        Project::factory(3)->create(['user_id' => $user->id]);
        Project::factory(2)->create(); // Other user's projects

        $response = $this->getJson('/api/projects');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'series',
                        'version',
                        'description',
                        'share',
                        'created_at',
                    ]
                ]
            ]);
    });

    it('can create a project', function () {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        
        $projectData = [
            'name' => 'New Project',
            'series' => 'Test Series',
            'version' => '1.0',
            'description' => 'A new test project',
            'share' => true,
        ];

        $response = $this->postJson('/api/projects', $projectData);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'Project created successfully',
                'project' => [
                    'name' => 'New Project',
                    'series' => 'Test Series',
                    'version' => '1.0',
                    'description' => 'A new test project',
                    'share' => true,
                ]
            ]);

        $this->assertDatabaseHas('projects', [
            'user_id' => $user->id,
            'name' => 'New Project',
        ]);
    });

    it('can show a project', function () {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        
        $project = Project::factory()->create(['user_id' => $user->id]);

        $response = $this->getJson("/api/projects/{$project->id}");

        $response->assertStatus(200)
            ->assertJson([
                'project' => [
                    'id' => $project->id,
                    'name' => $project->name,
                ]
            ]);
    });

    it('can update a project', function () {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        
        $project = Project::factory()->create(['user_id' => $user->id]);

        $updateData = [
            'name' => 'Updated Project',
            'series' => 'Updated Series',
            'description' => 'Updated description',
        ];

        $response = $this->putJson("/api/projects/{$project->id}", $updateData);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Project updated successfully',
                'project' => [
                    'name' => 'Updated Project',
                    'series' => 'Updated Series',
                    'description' => 'Updated description',
                ]
            ]);
    });

    it('can delete a project', function () {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        
        $project = Project::factory()->create(['user_id' => $user->id]);

        $response = $this->deleteJson("/api/projects/{$project->id}");

        $response->assertStatus(200)
            ->assertJson(['message' => 'Project deleted successfully']);

        $this->assertDatabaseMissing('projects', ['id' => $project->id]);
    });

    it('cannot access other users projects', function () {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        
        $otherUser = User::factory()->create();
        $otherProject = Project::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->getJson("/api/projects/{$otherProject->id}");

        $response->assertStatus(404);
    });

    it('requires authentication', function () {
        $response = $this->getJson('/api/projects');

        $response->assertStatus(401);
    });

    it('validates required fields when creating', function () {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        
        $response = $this->postJson('/api/projects', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    });

    it('prevents duplicate project names', function () {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        
        Project::factory()->create(['name' => 'Existing Project']);

        $response = $this->postJson('/api/projects', [
            'name' => 'Existing Project',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    });

    it('returns project with associated tasks, materials, and notes', function () {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        
        $project = Project::factory()->create(['user_id' => $user->id]);
        
        // Create tasks with notes
        $tasks = Task::factory(2)->for($project)->create();
        foreach ($tasks as $task) {
            Note::factory(2)->for($task, 'noteable')->create();
        }
        
        // Create materials with notes
        $materials = Material::factory(2)->for($project)->create();
        foreach ($materials as $material) {
            Note::factory(2)->for($material, 'noteable')->create();
        }
        
        // Create project notes
        Note::factory(2)->for($project, 'noteable')->create();

        $response = $this->getJson("/api/projects/{$project->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'project' => [
                    'id',
                    'name',
                    'series',
                    'version',
                    'description',
                    'share',
                    'created_at',
                    'updated_at',
                    'tasks' => [
                        '*' => [
                            'id',
                            'title',
                            'description',
                            'share',
                            'due_date',
                            'completion_date',
                            'created_at',
                            'updated_at',
                            'notes' => [
                                '*' => [
                                    'id',
                                    'content',
                                    'created_at',
                                    'updated_at',
                                ]
                            ]
                        ]
                    ],
                    'materials' => [
                        '*' => [
                            'id',
                            'name',
                            'description',
                            'amount',
                            'est_cost',
                            'actual_cost',
                            'source',
                            'acquired',
                            'share',
                            'created_at',
                            'updated_at',
                            'notes' => [
                                '*' => [
                                    'id',
                                    'content',
                                    'created_at',
                                    'updated_at',
                                ]
                            ]
                        ]
                    ],
                    'notes' => [
                        '*' => [
                            'id',
                            'content',
                            'created_at',
                            'updated_at',
                        ]
                    ]
                ]
            ]);

        // Verify the counts
        $responseData = $response->json('project');
        $this->assertCount(2, $responseData['tasks']);
        $this->assertCount(2, $responseData['materials']);
        $this->assertCount(2, $responseData['notes']);
        
        // Verify each task has notes
        foreach ($responseData['tasks'] as $task) {
            $this->assertCount(2, $task['notes']);
        }
        
        // Verify each material has notes
        foreach ($responseData['materials'] as $material) {
            $this->assertCount(2, $material['notes']);
        }
    });

    it('returns projects list with associated models', function () {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        
        $project = Project::factory()->create(['user_id' => $user->id]);
        
        // Create some associated data
        Task::factory(1)->for($project)->create();
        Material::factory(1)->for($project)->create();
        Note::factory(1)->for($project, 'noteable')->create();

        $response = $this->getJson('/api/projects');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'series',
                        'version',
                        'description',
                        'share',
                        'created_at',
                        'updated_at',
                        'tasks',
                        'materials',
                        'notes',
                    ]
                ]
            ]);

        $responseData = $response->json('data.0');
        $this->assertArrayHasKey('tasks', $responseData);
        $this->assertArrayHasKey('materials', $responseData);
        $this->assertArrayHasKey('notes', $responseData);
    });
});
