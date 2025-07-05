<?php

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('Task Model', function () {
    it('can create a task with required fields', function () {
        $user = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $user->id]);
        
        $task = Task::factory()->create([
            'project_id' => $project->id,
            'title' => 'Test Task',
            'description' => 'This is a test task',
            'share' => true,
            'due_date' => now()->addDays(7),
            'completion_date' => null,
        ]);

        expect($task->title)->toBe('Test Task');
        expect($task->description)->toBe('This is a test task');
        expect($task->share)->toBe(true);
        expect($task->due_date)->toBeInstanceOf(\Carbon\Carbon::class);
        expect($task->completion_date)->toBeNull();
        expect($task->project_id)->toBe($project->id);
    });

    it('belongs to a project', function () {
        $user = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $user->id]);
        $task = Task::factory()->create(['project_id' => $project->id]);

        expect($task->project)->toBeInstanceOf(Project::class);
        expect($task->project->id)->toBe($project->id);
    });

    it('can have optional fields as null', function () {
        $user = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $user->id]);
        
        $task = Task::factory()->create([
            'project_id' => $project->id,
            'title' => 'Test Task',
            'description' => null,
            'share' => false,
            'due_date' => null,
            'completion_date' => null,
        ]);

        expect($task->description)->toBeNull();
        expect($task->share)->toBe(false);
        expect($task->due_date)->toBeNull();
        expect($task->completion_date)->toBeNull();
    });

    it('requires a project_id', function () {
        expect(fn() => Task::factory()->create(['project_id' => null]))
            ->toThrow(Illuminate\Database\QueryException::class);
    });

    it('requires a title', function () {
        $user = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $user->id]);
        
        expect(fn() => Task::factory()->create([
            'project_id' => $project->id,
            'title' => null,
        ]))->toThrow(Illuminate\Database\QueryException::class);
    });

    it('can have multiple tasks per project', function () {
        $user = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $user->id]);
        
        $task1 = Task::factory()->create(['project_id' => $project->id]);
        $task2 = Task::factory()->create(['project_id' => $project->id]);

        expect($project->tasks)->toHaveCount(2);
        expect($project->tasks->pluck('id')->toArray())->toContain($task1->id);
        expect($project->tasks->pluck('id')->toArray())->toContain($task2->id);
    });

    it('can be marked as completed', function () {
        $user = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $user->id]);
        
        $task = Task::factory()->create([
            'project_id' => $project->id,
            'completion_date' => now(),
        ]);

        expect($task->completion_date)->toBeInstanceOf(\Carbon\Carbon::class);
    });
}); 