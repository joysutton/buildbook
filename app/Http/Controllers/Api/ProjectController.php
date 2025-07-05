<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Project\StoreProjectRequest;
use App\Http\Requests\Project\UpdateProjectRequest;
use App\Models\Project;
use Illuminate\Http\JsonResponse;

class ProjectController extends Controller
{
    public function index(): JsonResponse
    {
        $projects = auth()->user()->projects()->with(['tasks.notes', 'materials.notes', 'notes'])->latest()->get();

        return response()->json([
            'data' => $projects
        ]);
    }

    public function store(StoreProjectRequest $request): JsonResponse
    {
        $project = auth()->user()->projects()->create($request->validated());

        return response()->json([
            'message' => 'Project created successfully',
            'project' => $project
        ], 201);
    }

    public function show(Project $project): JsonResponse
    {
        if ($project->user_id !== auth()->id()) {
            abort(404);
        }

        $project->load(['tasks.notes', 'materials.notes', 'notes']);

        return response()->json([
            'project' => $project
        ]);
    }

    public function update(UpdateProjectRequest $request, Project $project): JsonResponse
    {
        if ($project->user_id !== auth()->id()) {
            abort(404);
        }

        $project->update($request->validated());

        return response()->json([
            'message' => 'Project updated successfully',
            'project' => $project
        ]);
    }

    public function destroy(Project $project): JsonResponse
    {
        if ($project->user_id !== auth()->id()) {
            abort(404);
        }

        $project->delete();

        return response()->json([
            'message' => 'Project deleted successfully'
        ]);
    }
} 