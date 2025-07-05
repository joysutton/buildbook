<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

describe('User Login API', function () {
    it('can login with email', function () {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $loginData = [
            'login' => 'test@example.com',
            'password' => 'password123',
        ];

        $response = $this->postJson('/api/login', $loginData);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'user' => [
                    'id',
                    'username',
                    'email',
                    'handle',
                    'bio',
                ],
                'token',
            ]);

        $this->assertDatabaseHas('personal_access_tokens', [
            'name' => 'auth_token',
            'tokenable_id' => $user->id,
        ]);
    });

    it('can login with username', function () {
        $user = User::factory()->create([
            'username' => 'testuser',
            'password' => Hash::make('password123'),
        ]);

        $loginData = [
            'login' => 'testuser',
            'password' => 'password123',
        ];

        $response = $this->postJson('/api/login', $loginData);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'user' => [
                    'id',
                    'username',
                    'email',
                    'handle',
                    'bio',
                ],
                'token',
            ]);
    });

    it('requires login field', function () {
        $response = $this->postJson('/api/login', [
            'password' => 'password123',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['login']);
    });

    it('requires password field', function () {
        $response = $this->postJson('/api/login', [
            'login' => 'test@example.com',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['password']);
    });

    it('fails with incorrect email', function () {
        User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $loginData = [
            'login' => 'wrong@example.com',
            'password' => 'password123',
        ];

        $response = $this->postJson('/api/login', $loginData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['login']);
    });

    it('fails with incorrect password', function () {
        User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $loginData = [
            'login' => 'test@example.com',
            'password' => 'wrongpassword',
        ];

        $response = $this->postJson('/api/login', $loginData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['login']);
    });

    it('fails with non-existent username', function () {
        $loginData = [
            'login' => 'nonexistent',
            'password' => 'password123',
        ];

        $response = $this->postJson('/api/login', $loginData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['login']);
    });
});

test('user can login with username', function () {
    $user = User::factory()->create([
        'username' => 'testuser',
        'password' => bcrypt('password'),
    ]);

    $response = $this->postJson('/api/login', [
        'login' => $user->username,
        'password' => 'password',
    ]);

    $response->assertStatus(200)
        ->assertJsonStructure([
            'user' => [
                'id', 'username', 'email', 'handle', 'bio', 'created_at', 'updated_at'
            ],
            'token',
        ]);
});

test('user cannot login with wrong username', function () {
    $response = $this->postJson('/api/login', [
        'login' => 'wronguser',
        'password' => 'password',
    ]);
    $response->assertStatus(422)
        ->assertJsonValidationErrors(['login']);
}); 