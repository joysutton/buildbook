<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Note\StoreNoteRequest;
use App\Http\Requests\Note\UpdateNoteRequest;
use App\Models\Material;
use App\Models\Note;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\JsonResponse;

class NoteController extends Controller
{
    /**
     * Display a listing of notes for a project.
     */
    public function indexForProject(Project $project): JsonResponse
    {
        if ($project->user_id !== auth()->id()) {
            return response()->json(['message' => 'Access denied'], 403);
        }

        $notes = $project->notes()->get();

        return response()->json([
            'data' => $notes,
        ]);
    }

    /**
     * Display a listing of notes for a material.
     */
    public function indexForMaterial(Material $material): JsonResponse
    {
        if ($material->project->user_id !== auth()->id()) {
            return response()->json(['message' => 'Access denied'], 403);
        }

        $notes = $material->notes()->get();

        return response()->json([
            'data' => $notes,
        ]);
    }

    /**
     * Display a listing of notes for a task.
     */
    public function indexForTask(Task $task): JsonResponse
    {
        if ($task->project->user_id !== auth()->id()) {
            return response()->json(['message' => 'Access denied'], 403);
        }

        $notes = $task->notes()->get();

        return response()->json([
            'data' => $notes,
        ]);
    }

    /**
     * Store a newly created note for a project.
     */
    public function storeForProject(StoreNoteRequest $request, Project $project): JsonResponse
    {
        if ($project->user_id !== auth()->id()) {
            return response()->json(['message' => 'Access denied'], 403);
        }

        $note = $project->notes()->create($request->validated());

        return response()->json([
            'message' => 'Note created successfully',
            'data' => $note,
        ], 201);
    }

    /**
     * Store a newly created note for a material.
     */
    public function storeForMaterial(StoreNoteRequest $request, Material $material): JsonResponse
    {
        if ($material->project->user_id !== auth()->id()) {
            return response()->json(['message' => 'Access denied'], 403);
        }

        $note = $material->notes()->create($request->validated());

        return response()->json([
            'message' => 'Note created successfully',
            'data' => $note,
        ], 201);
    }

    /**
     * Store a newly created note for a task.
     */
    public function storeForTask(StoreNoteRequest $request, Task $task): JsonResponse
    {
        if ($task->project->user_id !== auth()->id()) {
            return response()->json(['message' => 'Access denied'], 403);
        }

        $note = $task->notes()->create($request->validated());

        return response()->json([
            'message' => 'Note created successfully',
            'data' => $note,
        ], 201);
    }

    /**
     * Display the specified note.
     */
    public function show(Note $note): JsonResponse
    {
        $parent = $note->noteable;
        
        // Check authorization based on parent type
        if ($parent instanceof Project) {
            if ($parent->user_id !== auth()->id()) {
                return response()->json(['message' => 'Access denied'], 403);
            }
        } elseif ($parent instanceof Material || $parent instanceof Task) {
            if ($parent->project->user_id !== auth()->id()) {
                return response()->json(['message' => 'Access denied'], 403);
            }
        }

        return response()->json([
            'data' => $note,
        ]);
    }

    /**
     * Update the specified note.
     */
    public function update(UpdateNoteRequest $request, Note $note): JsonResponse
    {
        $parent = $note->noteable;
        
        // Check authorization based on parent type
        if ($parent instanceof Project) {
            if ($parent->user_id !== auth()->id()) {
                return response()->json(['message' => 'Access denied'], 403);
            }
        } elseif ($parent instanceof Material || $parent instanceof Task) {
            if ($parent->project->user_id !== auth()->id()) {
                return response()->json(['message' => 'Access denied'], 403);
            }
        }

        $note->update($request->validated());

        return response()->json([
            'message' => 'Note updated successfully',
            'data' => $note,
        ]);
    }

    /**
     * Remove the specified note.
     */
    public function destroy(Note $note): JsonResponse
    {
        $parent = $note->noteable;
        
        // Check authorization based on parent type
        if ($parent instanceof Project) {
            if ($parent->user_id !== auth()->id()) {
                return response()->json(['message' => 'Access denied'], 403);
            }
        } elseif ($parent instanceof Material || $parent instanceof Task) {
            if ($parent->project->user_id !== auth()->id()) {
                return response()->json(['message' => 'Access denied'], 403);
            }
        }

        $note->delete();

        return response()->json([
            'message' => 'Note deleted successfully',
        ]);
    }
} 