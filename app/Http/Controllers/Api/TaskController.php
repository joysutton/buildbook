<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Task\StoreTaskRequest;
use App\Http\Requests\Task\UpdateTaskRequest;
use App\Models\Task;
use Illuminate\Http\JsonResponse;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $tasks = Task::whereHas('project', function ($query) {
            $query->where('user_id', auth()->id());
        })->with(['project', 'notes'])->get();

        return response()->json($tasks);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request): JsonResponse
    {
        $task = Task::create($request->validated());
        $task->load(['project', 'notes']);

        return response()->json($task, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task): JsonResponse
    {
        // Ensure the task belongs to the authenticated user's project
        if ($task->project->user_id !== auth()->id()) {
            abort(404);
        }

        $task->load(['project', 'notes']);

        return response()->json($task);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task): JsonResponse
    {
        // Ensure the task belongs to the authenticated user's project
        if ($task->project->user_id !== auth()->id()) {
            abort(404);
        }

        $task->update($request->validated());
        $task->load(['project', 'notes']);

        return response()->json($task);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task): JsonResponse
    {
        // Ensure the task belongs to the authenticated user's project
        if ($task->project->user_id !== auth()->id()) {
            abort(404);
        }

        $task->delete();

        return response()->json(null, 204);
    }
}
