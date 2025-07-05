<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Material\StoreMaterialRequest;
use App\Http\Requests\Material\UpdateMaterialRequest;
use App\Models\Material;
use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    /**
     * Display a listing of materials for a project.
     */
    public function index(Project $project): JsonResponse
    {
        // Check if user owns the project
        if ($project->user_id !== auth()->id()) {
            return response()->json(['message' => 'Access denied'], 403);
        }

        $materials = $project->materials()->with('notes')->get();

        return response()->json([
            'data' => $materials,
        ]);
    }

    /**
     * Store a newly created material in storage.
     */
    public function store(StoreMaterialRequest $request, Project $project): JsonResponse
    {
        // Check if user owns the project
        if ($project->user_id !== auth()->id()) {
            return response()->json(['message' => 'Access denied'], 403);
        }

        $material = $project->materials()->create($request->validated());
        $material->load('notes');

        return response()->json([
            'message' => 'Material created successfully',
            'data' => $material,
        ], 201);
    }

    /**
     * Display the specified material.
     */
    public function show(Material $material): JsonResponse
    {
        // Check if user owns the project that contains this material
        if ($material->project->user_id !== auth()->id()) {
            return response()->json(['message' => 'Access denied'], 403);
        }

        $material->load('notes');

        return response()->json([
            'data' => $material,
        ]);
    }

    /**
     * Update the specified material in storage.
     */
    public function update(UpdateMaterialRequest $request, Material $material): JsonResponse
    {
        // Check if user owns the project that contains this material
        if ($material->project->user_id !== auth()->id()) {
            return response()->json(['message' => 'Access denied'], 403);
        }

        $material->update($request->validated());
        $material->load('notes');

        return response()->json([
            'message' => 'Material updated successfully',
            'data' => $material,
        ]);
    }

    /**
     * Remove the specified material from storage.
     */
    public function destroy(Material $material): JsonResponse
    {
        // Check if user owns the project that contains this material
        if ($material->project->user_id !== auth()->id()) {
            return response()->json(['message' => 'Access denied'], 403);
        }

        $material->delete();

        return response()->json([
            'message' => 'Material deleted successfully',
        ]);
    }
} 