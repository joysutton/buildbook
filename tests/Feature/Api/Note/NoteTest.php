<?php

use App\Models\User;
use App\Models\Project;
use App\Models\Material;
use App\Models\Task;
use App\Models\Note;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

describe('Notes API', function () {
    // Helper to create parent model
    function createParent($type, $user) {
        return match($type) {
            'project' => Project::factory()->for($user)->create(),
            'material' => Material::factory()->for(Project::factory()->for($user))->create(),
            'task' => Task::factory()->for(Project::factory()->for($user))->create(),
        };
    }

    // Helper to get API route prefix
    function routePrefix($type, $parent) {
        return match($type) {
            'project' => "/api/projects/{$parent->id}/notes",
            'material' => "/api/materials/{$parent->id}/notes",
            'task' => "/api/tasks/{$parent->id}/notes",
        };
    }

    // Test all parent types
    foreach (['project', 'material', 'task'] as $type) {
        it("can list notes for a $type", function () use ($type) {
            $user = User::factory()->create();
            $parent = createParent($type, $user);
            $notes = Note::factory()->count(3)->for($parent, 'noteable')->create();
            Sanctum::actingAs($user);

            $response = $this->getJson(routePrefix($type, $parent));
            $response->assertStatus(200)
                ->assertJsonCount(3, 'data')
                ->assertJsonStructure(['data' => [['id', 'content', 'created_at']]]);
        });

        it("can create a note for a $type", function () use ($type) {
            $user = User::factory()->create();
            $parent = createParent($type, $user);
            Sanctum::actingAs($user);

            $data = ['content' => 'This is a note.'];
            $response = $this->postJson(routePrefix($type, $parent), $data);
            $response->assertStatus(201)
                ->assertJsonFragment(['content' => 'This is a note.']);
            $this->assertDatabaseHas('notes', [
                'content' => 'This is a note.',
                'noteable_id' => $parent->id,
                'noteable_type' => $parent::class,
            ]);
        });

        it("can show a note for a $type", function () use ($type) {
            $user = User::factory()->create();
            $parent = createParent($type, $user);
            $note = Note::factory()->for($parent, 'noteable')->create();
            Sanctum::actingAs($user);

            $response = $this->getJson("/api/notes/{$note->id}");
            $response->assertStatus(200)
                ->assertJsonFragment(['id' => $note->id, 'content' => $note->content]);
        });

        it("can update a note for a $type", function () use ($type) {
            $user = User::factory()->create();
            $parent = createParent($type, $user);
            $note = Note::factory()->for($parent, 'noteable')->create();
            Sanctum::actingAs($user);

            $data = ['content' => 'Updated note content.'];
            $response = $this->putJson("/api/notes/{$note->id}", $data);
            $response->assertStatus(200)
                ->assertJsonFragment(['content' => 'Updated note content.']);
            $this->assertDatabaseHas('notes', [
                'id' => $note->id,
                'content' => 'Updated note content.',
            ]);
        });

        it("can delete a note for a $type", function () use ($type) {
            $user = User::factory()->create();
            $parent = createParent($type, $user);
            $note = Note::factory()->for($parent, 'noteable')->create();
            Sanctum::actingAs($user);

            $response = $this->deleteJson("/api/notes/{$note->id}");
            $response->assertStatus(200)
                ->assertJson(['message' => 'Note deleted successfully']);
            $this->assertDatabaseMissing('notes', [
                'id' => $note->id,
            ]);
        });

        it("validates required content when creating a note for a $type", function () use ($type) {
            $user = User::factory()->create();
            $parent = createParent($type, $user);
            Sanctum::actingAs($user);

            $response = $this->postJson(routePrefix($type, $parent), []);
            $response->assertStatus(422)
                ->assertJsonValidationErrors(['content']);
        });

        it("prevents non-owners from managing notes for a $type", function () use ($type) {
            $user1 = User::factory()->create();
            $user2 = User::factory()->create();
            $parent = createParent($type, $user1);
            $note = Note::factory()->for($parent, 'noteable')->create();
            Sanctum::actingAs($user2);

            $this->getJson(routePrefix($type, $parent))->assertStatus(403);
            $this->postJson(routePrefix($type, $parent), ['content' => 'X'])->assertStatus(403);
            $this->getJson("/api/notes/{$note->id}")->assertStatus(403);
            $this->putJson("/api/notes/{$note->id}", ['content' => 'Y'])->assertStatus(403);
            $this->deleteJson("/api/notes/{$note->id}")->assertStatus(403);
        });
    }
}); 