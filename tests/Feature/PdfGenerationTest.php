<?php

use App\Models\Project;
use App\Models\User;

test('authenticated user can download PDF of shared project', function () {
    $user = User::factory()->create();
    $project = Project::factory()->create([
        'user_id' => $user->id,
        'share' => true,
    ]);

    $response = $this->actingAs($user)
        ->get(route('projects.pdf', $project));

    $response->assertStatus(200);
    $response->assertHeader('Content-Type', 'application/pdf');
    $response->assertHeader('Content-Disposition', 'attachment; filename="' . $project->name . '-buildbook.pdf"');
});

test('unauthenticated user cannot download PDF', function () {
    $project = Project::factory()->create(['share' => true]);

    $response = $this->get(route('projects.pdf', $project));

    $response->assertRedirect(route('login'));
});

test('user cannot download PDF of another users project', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    $project = Project::factory()->create([
        'user_id' => $user1->id,
        'share' => true,
    ]);

    $response = $this->actingAs($user2)
        ->get(route('projects.pdf', $project));

    $response->assertStatus(404);
});

test('user cannot download PDF of unshared project', function () {
    $user = User::factory()->create();
    $project = Project::factory()->create([
        'user_id' => $user->id,
        'share' => false,
    ]);

    $response = $this->actingAs($user)
        ->get(route('projects.pdf', $project));

    $response->assertStatus(400);
}); 