<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('User Model', function () {
    it('can create a user with required fields', function () {
        $user = User::factory()->create([
            'username' => 'testuser',
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        expect($user->username)->toBe('testuser');
        expect($user->email)->toBe('test@example.com');
        expect($user->password)->not->toBe('password123'); // Should be hashed
    });

    it('requires username to be unique', function () {
        User::factory()->create(['username' => 'testuser']);

        expect(fn() => User::factory()->create(['username' => 'testuser']))
            ->toThrow(Illuminate\Database\QueryException::class);
    });

    it('requires email to be unique', function () {
        User::factory()->create(['email' => 'test@example.com']);

        expect(fn() => User::factory()->create(['email' => 'test@example.com']))
            ->toThrow(Illuminate\Database\QueryException::class);
    });

    it('can have optional handle and bio fields', function () {
        $user = User::factory()->create([
            'handle' => '@testuser',
            'bio' => 'This is my bio',
        ]);

        expect($user->handle)->toBe('@testuser');
        expect($user->bio)->toBe('This is my bio');
    });

    it('can have null handle and bio fields', function () {
        $user = User::factory()->create([
            'handle' => null,
            'bio' => null,
        ]);

        expect($user->handle)->toBeNull();
        expect($user->bio)->toBeNull();
    });
});

test('user has a username', function () {
    $user = User::factory()->create(['username' => 'testuser']);
    expect($user->username)->toBe('testuser');
});

test('username is unique', function () {
    User::factory()->create(['username' => 'testuser']);
    expect(fn() => User::factory()->create(['username' => 'testuser']))
        ->toThrow(\Illuminate\Database\QueryException::class);
}); 