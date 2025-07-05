<?php

use App\Models\Material;
use App\Models\Project;
use App\Models\User;
use App\Models\Note;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

describe('Material API', function () {
    it('can list materials for a project', function () {
        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create();
        $materials = Material::factory()->count(3)->for($project)->create();
        
        Sanctum::actingAs($user);

        $response = $this->getJson("/api/projects/{$project->id}/materials");

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data')
            ->assertJsonStructure([
                'data' => [
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
                    ]
                ]
            ]);
    });

    it('can create a material', function () {
        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create();
        
        Sanctum::actingAs($user);

        $materialData = [
            'name' => 'Test Material',
            'description' => 'A test material for the project',
            'amount' => '2 yards',
            'est_cost' => 1500,
            'actual_cost' => 1800,
            'source' => 'Local Fabric Store',
            'acquired' => true,
            'share' => true,
        ];

        $response = $this->postJson("/api/projects/{$project->id}/materials", $materialData);

        $response->assertStatus(201)
            ->assertJsonFragment([
                'name' => 'Test Material',
                'description' => 'A test material for the project',
                'amount' => '2 yards',
                'est_cost' => 1500,
                'actual_cost' => 1800,
                'source' => 'Local Fabric Store',
                'acquired' => true,
                'share' => true,
            ]);

        $this->assertDatabaseHas('materials', [
            'project_id' => $project->id,
            'name' => 'Test Material',
        ]);
    });

    it('can show a material', function () {
        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create();
        $material = Material::factory()->for($project)->create();
        
        Sanctum::actingAs($user);

        $response = $this->getJson("/api/materials/{$material->id}");

        $response->assertStatus(200)
            ->assertJsonFragment([
                'id' => $material->id,
                'name' => $material->name,
            ]);
    });

    it('can update a material', function () {
        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create();
        $material = Material::factory()->for($project)->create();
        
        Sanctum::actingAs($user);

        $updateData = [
            'name' => 'Updated Material',
            'description' => 'Updated description',
            'amount' => '3 yards',
            'est_cost' => 2000,
            'actual_cost' => 2200,
            'source' => 'Online Store',
            'acquired' => false,
            'share' => false,
        ];

        $response = $this->putJson("/api/materials/{$material->id}", $updateData);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'name' => 'Updated Material',
                'description' => 'Updated description',
                'amount' => '3 yards',
                'est_cost' => 2000,
                'actual_cost' => 2200,
                'source' => 'Online Store',
                'acquired' => false,
                'share' => false,
            ]);

        $this->assertDatabaseHas('materials', [
            'id' => $material->id,
            'name' => 'Updated Material',
        ]);
    });

    it('can delete a material', function () {
        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create();
        $material = Material::factory()->for($project)->create();
        
        Sanctum::actingAs($user);

        $response = $this->deleteJson("/api/materials/{$material->id}");

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Material deleted successfully',
            ]);

        $this->assertDatabaseMissing('materials', [
            'id' => $material->id,
        ]);
    });

    it('validates required fields when creating', function () {
        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create();
        
        Sanctum::actingAs($user);

        $response = $this->postJson("/api/projects/{$project->id}/materials", []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    });

    it('allows multiple materials with same name in a project', function () {
        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create();
        
        Material::factory()->for($project)->create(['name' => 'Cotton Fabric']);
        
        Sanctum::actingAs($user);

        $response = $this->postJson("/api/projects/{$project->id}/materials", [
            'name' => 'Cotton Fabric',
            'description' => 'Different color cotton fabric',
        ]);

        $response->assertStatus(201);
    });

    it('requires authentication to access materials', function () {
        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create();

        $response = $this->getJson("/api/projects/{$project->id}/materials");

        $response->assertStatus(401);
    });

    it('prevents access to materials from other users projects', function () {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $project = Project::factory()->for($user1)->create();
        $material = Material::factory()->for($project)->create();
        
        Sanctum::actingAs($user2);

        $response = $this->getJson("/api/materials/{$material->id}");

        $response->assertStatus(403);
    });

    it('prevents creating materials in other users projects', function () {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $project = Project::factory()->for($user1)->create();
        
        Sanctum::actingAs($user2);

        $response = $this->postJson("/api/projects/{$project->id}/materials", [
            'name' => 'Unauthorized Material',
        ]);

        $response->assertStatus(403);
    });

    it('prevents updating materials from other users projects', function () {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $project = Project::factory()->for($user1)->create();
        $material = Material::factory()->for($project)->create();
        
        Sanctum::actingAs($user2);

        $response = $this->putJson("/api/materials/{$material->id}", [
            'name' => 'Unauthorized Update',
        ]);

        $response->assertStatus(403);
    });

    it('prevents deleting materials from other users projects', function () {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $project = Project::factory()->for($user1)->create();
        $material = Material::factory()->for($project)->create();
        
        Sanctum::actingAs($user2);

        $response = $this->deleteJson("/api/materials/{$material->id}");

        $response->assertStatus(403);
    });

    it('returns materials with associated notes', function () {
        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create();
        $material = Material::factory()->for($project)->create();
        Note::factory(3)->for($material, 'noteable')->create();
        
        Sanctum::actingAs($user);

        $response = $this->getJson("/api/projects/{$project->id}/materials");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
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
                ]
            ]);

        $responseData = $response->json('data.0');
        $this->assertArrayHasKey('notes', $responseData);
        $this->assertCount(3, $responseData['notes']);
    });

    it('returns created material with notes structure', function () {
        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create();
        
        Sanctum::actingAs($user);

        $materialData = [
            'name' => 'Test Material',
            'description' => 'A test material',
        ];

        $response = $this->postJson("/api/projects/{$project->id}/materials", $materialData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'data' => [
                    'id',
                    'name',
                    'description',
                    'project_id',
                    'created_at',
                    'updated_at',
                    'notes',
                ]
            ]);

        $responseData = $response->json('data');
        $this->assertArrayHasKey('notes', $responseData);
        $this->assertIsArray($responseData['notes']);
    });

    it('returns material with associated notes when showing', function () {
        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create();
        $material = Material::factory()->for($project)->create();
        Note::factory(2)->for($material, 'noteable')->create();
        
        Sanctum::actingAs($user);

        $response = $this->getJson("/api/materials/{$material->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
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
            ]);

        $responseData = $response->json('data');
        $this->assertArrayHasKey('notes', $responseData);
        $this->assertCount(2, $responseData['notes']);
    });

    it('returns updated material with notes structure', function () {
        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create();
        $material = Material::factory()->for($project)->create();
        Note::factory(1)->for($material, 'noteable')->create();
        
        Sanctum::actingAs($user);

        $updateData = [
            'name' => 'Updated Material',
            'description' => 'Updated description',
        ];

        $response = $this->putJson("/api/materials/{$material->id}", $updateData);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'data' => [
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
                    'notes',
                ]
            ]);

        $responseData = $response->json('data');
        $this->assertArrayHasKey('notes', $responseData);
    });
}); 