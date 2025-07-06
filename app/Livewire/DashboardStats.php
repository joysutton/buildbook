<?php

namespace App\Livewire;

use App\Models\Project;
use App\Models\Task;
use App\Models\Material;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class DashboardStats extends Component
{
    public function render()
    {
        $user = Auth::user();

        // Get total projects
        $totalProjects = $user->projects()->count();

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

        return view('livewire.dashboard-stats', [
            'totalProjects' => $totalProjects,
            'activeTasks' => $activeTasks,
            'materialsNeeded' => $materialsNeeded,
            'completedThisWeek' => $completedThisWeek,
        ]);
    }
}
