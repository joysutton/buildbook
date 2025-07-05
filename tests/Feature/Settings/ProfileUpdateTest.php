<?php

use App\Models\User;
use Illuminate\Support\Facades\Http;
use Livewire\Volt\Volt;

test('profile page is displayed', function () {
    $this->actingAs($user = User::factory()->create());

    $this->get('/settings/profile')->assertOk();
});

test('profile information can be updated', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    // Mock the HTTP request to return a successful response
    Http::fake([
        url('/api/profile') => Http::response([
            'message' => 'Profile updated successfully',
            'user' => [
                'id' => $user->id,
                'username' => 'TestUser',
                'email' => 'testuser@example.com',
                'handle' => 'test_handle',
                'bio' => 'Test bio',
                'created_at' => $user->created_at,
            ]
        ], 200)
    ]);

    $response = Volt::test('settings.profile')
        ->set('username', 'TestUser')
        ->set('email', 'testuser@example.com')
        ->set('handle', 'test_handle')
        ->set('bio', 'Test bio')
        ->call('updateProfileInformation');

    $response->assertHasNoErrors();
});

test('email verification status is unchanged when email address is unchanged', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $response = Volt::test('settings.profile')
        ->set('username', 'TestUser')
        ->set('email', $user->email)
        ->call('updateProfileInformation');

    $response->assertHasNoErrors();

    expect($user->refresh()->email_verified_at)->not->toBeNull();
});

test('user can delete their account', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $response = Volt::test('settings.delete-user-form')
        ->set('password', 'password')
        ->call('deleteUser');

    $response
        ->assertHasNoErrors()
        ->assertRedirect('/');

    expect($user->fresh())->toBeNull();
    expect(auth()->check())->toBeFalse();
});

test('correct password must be provided to delete account', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $response = Volt::test('settings.delete-user-form')
        ->set('password', 'wrong-password')
        ->call('deleteUser');

    $response->assertHasErrors(['password']);

    expect($user->fresh())->not->toBeNull();
});

test('user can update their profile with only required fields', function () {
    $user = \App\Models\User::factory()->create();
    $this->actingAs($user);

    // Mock the HTTP request to return a successful response
    Http::fake([
        url('/api/profile') => Http::response([
            'message' => 'Profile updated successfully',
            'user' => [
                'id' => $user->id,
                'username' => 'TestUser',
                'email' => 'testuser@example.com',
                'handle' => $user->handle,
                'bio' => $user->bio,
                'created_at' => $user->created_at,
            ]
        ], 200)
    ]);

    \Livewire\Volt\Volt::test('settings.profile')
        ->set('username', 'TestUser')
        ->set('email', 'testuser@example.com')
        ->call('updateProfileInformation')
        ->assertHasNoErrors();
});