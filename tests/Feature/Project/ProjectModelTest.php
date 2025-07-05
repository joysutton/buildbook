<?php

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('Project Model', function () {
    it('can create a project with required fields', function () {
        $user = User::factory()->create();
        
        $project = Project::factory()->create([
            'user_id' => $user->id,
            'name' => 'Test Project',
            'series' => 'Test Series',
            'version' => '1.0',
            'description' => 'This is a test project',
            'share' => true,
        ]);

        expect($project->name)->toBe('Test Project');
        expect($project->series)->toBe('Test Series');
        expect($project->version)->toBe('1.0');
        expect($project->description)->toBe('This is a test project');
        expect($project->share)->toBe(true);
        expect($project->user_id)->toBe($user->id);
    });

    it('belongs to a user', function () {
        $user = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $user->id]);

        expect($project->user)->toBeInstanceOf(User::class);
        expect($project->user->id)->toBe($user->id);
    });

    it('can have optional fields as null', function () {
        $user = User::factory()->create();
        
        $project = Project::factory()->create([
            'user_id' => $user->id,
            'name' => 'Test Project',
            'series' => null,
            'version' => null,
            'description' => null,
            'share' => false,
        ]);

        expect($project->series)->toBeNull();
        expect($project->version)->toBeNull();
        expect($project->description)->toBeNull();
        expect($project->share)->toBe(false);
    });

    it('requires a user_id', function () {
        expect(fn() => Project::factory()->create(['user_id' => null]))
            ->toThrow(Illuminate\Database\QueryException::class);
    });

    it('requires a name', function () {
        $user = User::factory()->create();
        
        expect(fn() => Project::factory()->create([
            'user_id' => $user->id,
            'name' => null,
        ]))->toThrow(Illuminate\Database\QueryException::class);
    });

    it('can have multiple projects per user', function () {
        $user = User::factory()->create();
        
        $project1 = Project::factory()->create(['user_id' => $user->id]);
        $project2 = Project::factory()->create(['user_id' => $user->id]);

        expect($user->projects)->toHaveCount(2);
        expect($user->projects->pluck('id')->toArray())->toContain($project1->id);
        expect($user->projects->pluck('id')->toArray())->toContain($project2->id);
    });
}); 