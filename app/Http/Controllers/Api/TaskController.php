<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Task\StoreTaskRequest;
use App\Http\Requests\Task\UpdateTaskRequest;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $limit = request()->get('limit');
        
        $query = Task::whereHas('project', function ($query) {
            $query->where('user_id', auth()->id());
        })->with(['project', 'notes']);
        
        if ($limit) {
            $query->limit($limit);
        }
        
        $tasks = $query->get();

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

    // Media Management Methods

    public function uploadImage(Request $request, Task $task): JsonResponse
    {
        if ($task->project->user_id !== auth()->id()) {
            return response()->json(['message' => 'Access denied'], 403);
        }

        $request->validate([
            'image' => 'required|file|mimes:jpg,jpeg,png,webp,gif,svg|max:10240', // 10MB max
        ]);

        $media = $task->addMediaFromRequest('image')
            ->toMediaCollection('progress_image');

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

    public function listImages(Task $task): JsonResponse
    {
        if ($task->project->user_id !== auth()->id()) {
            return response()->json(['message' => 'Access denied'], 403);
        }

        $media = $task->getMedia('progress_image');

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

    public function deleteImage(Task $task, int $mediaId): JsonResponse
    {
        if ($task->project->user_id !== auth()->id()) {
            return response()->json(['message' => 'Access denied'], 403);
        }

        $media = $task->media()->find($mediaId);

        if (!$media) {
            return response()->json(['message' => 'Image not found'], 404);
        }

        $media->delete();

        return response()->json([
            'message' => 'Image deleted successfully'
        ]);
    }

    public function showImage(Task $task, int $mediaId): JsonResponse
    {
        if ($task->project->user_id !== auth()->id()) {
            return response()->json(['message' => 'Access denied'], 403);
        }

        $media = $task->media()->find($mediaId);

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
