<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;

uses(RefreshDatabase::class);

describe('User Registration API', function () {
    it('can register a new user with valid data', function () {
        $userData = [
            'username' => 'johndoe',
            'email' => 'john@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'handle' => '@johndoe',
            'bio' => 'This is my bio',
        ];

        $response = $this->postJson('/api/register', $userData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'user' => [
                    'id',
                    'username',
                    'email',
                    'handle',
                    'bio',
                    'created_at',
                    'updated_at'
                ],
                'token',
            ]);

        $this->assertDatabaseHas('users', [
            'username' => 'johndoe',
            'email' => 'john@example.com',
            'handle' => '@johndoe',
            'bio' => 'This is my bio',
        ]);

        $this->assertDatabaseHas('personal_access_tokens', [
            'name' => 'auth_token',
        ]);
    });

    it('requires all required fields', function () {
        $response = $this->postJson('/api/register', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['username', 'email', 'password']);
    });

    it('requires unique username', function () {
        User::factory()->create(['username' => 'johndoe']);

        $userData = [
            'username' => 'johndoe',
            'email' => 'john@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        $response = $this->postJson('/api/register', $userData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['username']);
    });

    it('requires unique email', function () {
        User::factory()->create(['email' => 'john@example.com']);

        $userData = [
            'username' => 'johndoe',
            'email' => 'john@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        $response = $this->postJson('/api/register', $userData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    });

    it('requires password confirmation', function () {
        $userData = [
            'username' => 'johndoe',
            'email' => 'john@example.com',
            'password' => 'password',
        ];

        $response = $this->postJson('/api/register', $userData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['password']);
    });

    it('requires minimum password length', function () {
        $userData = [
            'username' => 'johndoe',
            'email' => 'john@example.com',
            'password' => '123',
            'password_confirmation' => '123',
        ];

        $response = $this->postJson('/api/register', $userData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['password']);
    });
}); 