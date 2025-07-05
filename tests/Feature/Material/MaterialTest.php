<?php

use App\Models\Material;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('Material Model', function () {
    it('can create a material', function () {
        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create();
        
        $material = Material::factory()->for($project)->create([
            'name' => 'Test Material',
            'description' => 'A test material',
            'amount' => '2 yards',
            'est_cost' => 1500, // $15.00 in cents
            'actual_cost' => 1800, // $18.00 in cents
            'source' => 'Local Fabric Store',
            'acquired' => true,
            'share' => true,
        ]);

        expect($material->name)->toBe('Test Material');
        expect($material->description)->toBe('A test material');
        expect($material->amount)->toBe('2 yards');
        expect($material->est_cost)->toBe(1500);
        expect($material->actual_cost)->toBe(1800);
        expect($material->source)->toBe('Local Fabric Store');
        expect($material->acquired)->toBe(true);
        expect($material->share)->toBe(true);
        expect($material->project_id)->toBe($project->id);
    });

    it('belongs to a project', function () {
        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create();
        $material = Material::factory()->for($project)->create();

        expect($material->project)->toBeInstanceOf(Project::class);
        expect($material->project->id)->toBe($project->id);
    });

    it('can have nullable fields', function () {
        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create();
        
        $material = Material::factory()->for($project)->create([
            'description' => null,
            'amount' => null,
            'est_cost' => null,
            'actual_cost' => null,
            'source' => null,
            'acquired' => false,
            'share' => false,
        ]);

        expect($material->description)->toBeNull();
        expect($material->amount)->toBeNull();
        expect($material->est_cost)->toBeNull();
        expect($material->actual_cost)->toBeNull();
        expect($material->source)->toBeNull();
        expect($material->acquired)->toBe(false);
        expect($material->share)->toBe(false);
    });
}); 