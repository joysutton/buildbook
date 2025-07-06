<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Task;
use App\Models\Material;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function stats(): JsonResponse
    {
        $user = Auth::user();

        // Get total projects
        $totalProjects = Project::where('user_id', $user->id)->count();

        // Get active tasks (not completed)
        $activeTasks = Task::whereHas('project', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->whereNull('completion_date')->count();

        // Get materials that haven't been acquired
        $materialsNeeded = Material::whereHas('project', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->where('acquired', false)->count();

        // Get tasks completed this week
        $completedThisWeek = Task::whereHas('project', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->whereNotNull('completion_date')
          ->where('completion_date', '>=', now()->startOfWeek())
          ->where('completion_date', '<=', now()->endOfWeek())
          ->count();

        return response()->json([
            'total_projects' => $totalProjects,
            'active_tasks' => $activeTasks,
            'materials_needed' => $materialsNeeded,
            'completed_this_week' => $completedThisWeek,
        ]);
    }
} 