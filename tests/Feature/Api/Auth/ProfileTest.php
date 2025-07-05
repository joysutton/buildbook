<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

describe('User Profile API', function () {
    it('can get user profile', function () {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->getJson('/api/profile');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'user' => [
                    'id',
                    'username',
                    'email',
                    'handle',
                    'bio',
                    'created_at',
                ],
            ]);
    });

    it('can update user profile', function () {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $updateData = [
            'username' => 'updatedusername',
            'email' => 'updated@example.com',
            'handle' => 'updated_handle',
            'bio' => 'Updated bio',
        ];

        $response = $this->patchJson('/api/profile', $updateData);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'username' => 'updatedusername',
                'email' => 'updated@example.com',
                'handle' => 'updated_handle',
                'bio' => 'Updated bio',
            ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'username' => 'updatedusername',
            'email' => 'updated@example.com',
        ]);
    });

    it('cannot update username to existing one', function () {
        $user1 = User::factory()->create(['username' => 'user1']);
        $user2 = User::factory()->create(['username' => 'user2']);
        Sanctum::actingAs($user2);

        $updateData = [
            'username' => 'user1',
            'email' => $user2->email,
        ];

        $response = $this->patchJson('/api/profile', $updateData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['username']);
    });

    it('cannot update email to existing one', function () {
        $user1 = User::factory()->create(['email' => 'user1@example.com']);
        $user2 = User::factory()->create(['email' => 'user2@example.com']);
        Sanctum::actingAs($user2);

        $updateData = [
            'email' => 'user1@example.com',
        ];

        $response = $this->patchJson('/api/profile', $updateData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    });

    it('can delete user account', function () {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->deleteJson('/api/profile');

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Account deleted successfully',
            ]);

        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
        ]);

        $this->assertDatabaseMissing('personal_access_tokens', [
            'tokenable_id' => $user->id,
        ]);
    });

    it('requires authentication to access profile', function () {
        $response = $this->getJson('/api/profile');

        $response->assertStatus(401);
    });

    it('requires authentication to update profile', function () {
        $response = $this->patchJson('/api/profile', []);

        $response->assertStatus(401);
    });

    it('requires authentication to delete profile', function () {
        $response = $this->deleteJson('/api/profile');

        $response->assertStatus(401);
    });
}); 