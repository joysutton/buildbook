<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Project\StoreProjectRequest;
use App\Http\Requests\Project\UpdateProjectRequest;
use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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

    // Media Management Methods

    public function uploadImage(Request $request, Project $project, string $collection): JsonResponse
    {
        if ($project->user_id !== auth()->id()) {
            return response()->json(['message' => 'Access denied'], 403);
        }

        if (!in_array($collection, ['main', 'references'])) {
            return response()->json(['message' => 'Invalid collection'], 400);
        }

        $request->validate([
            'image' => 'required|file|mimes:jpg,jpeg,png,webp,gif,svg|max:10240', // 10MB max
        ]);

        $media = $project->addMediaFromRequest('image')
            ->toMediaCollection($collection);

        return response()->json([
            'message' => 'Image uploaded successfully',
            'data' => [
                'id' => $media->id,
                'name' => $media->name,
                'file_name' => $media->file_name,
                'collection' => $media->collection_name,
                'url' => $media->getUrl(),
                'thumb_url' => $media->getUrl('thumb'),
                'size' => $media->size,
                'mime_type' => $media->mime_type,
                'created_at' => $media->created_at,
            ]
        ], 201);
    }

    public function listImages(Project $project, string $collection): JsonResponse
    {
        if ($project->user_id !== auth()->id()) {
            return response()->json(['message' => 'Access denied'], 403);
        }

        if (!in_array($collection, ['main', 'references'])) {
            return response()->json(['message' => 'Invalid collection'], 400);
        }

        $media = $project->getMedia($collection);

        $images = $media->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'file_name' => $item->file_name,
                'collection' => $item->collection_name,
                'url' => $item->getUrl(),
                'thumb_url' => $item->getUrl('thumb'),
                'size' => $item->size,
                'mime_type' => $item->mime_type,
                'created_at' => $item->created_at,
            ];
        });

        return response()->json([
            'data' => $images
        ]);
    }

    public function deleteImage(Project $project, int $mediaId): JsonResponse
    {
        if ($project->user_id !== auth()->id()) {
            return response()->json(['message' => 'Access denied'], 403);
        }

        $media = $project->media()->find($mediaId);

        if (!$media) {
            return response()->json(['message' => 'Image not found'], 404);
        }

        $media->delete();

        return response()->json([
            'message' => 'Image deleted successfully'
        ]);
    }

    public function showImage(Project $project, int $mediaId): JsonResponse
    {
        if ($project->user_id !== auth()->id()) {
            return response()->json(['message' => 'Access denied'], 403);
        }

        $media = $project->media()->find($mediaId);

        if (!$media) {
            return response()->json(['message' => 'Image not found'], 404);
        }

        return response()->json([
            'data' => [
                'id' => $media->id,
                'name' => $media->name,
                'file_name' => $media->file_name,
                'collection' => $media->collection_name,
                'url' => $media->getUrl(),
                'thumb_url' => $media->getUrl('thumb'),
                'size' => $media->size,
                'mime_type' => $media->mime_type,
                'created_at' => $media->created_at,
            ]
        ]);
    }
} 