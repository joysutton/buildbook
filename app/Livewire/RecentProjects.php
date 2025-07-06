<?php

namespace App\Livewire;

use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class RecentProjects extends Component
{
    public function render()
    {
        $projects = Auth::user()->projects()
            ->select('id', 'name', 'created_at')
            ->latest()
            ->limit(5)
            ->get();

        return view('livewire.recent-projects', [
            'projects' => $projects
        ]);
    }
}
