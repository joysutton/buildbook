<?php

namespace App\Livewire\Projects;

use App\Models\Project;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public function render()
    {
        $projects = Project::where('user_id', auth()->id())
            ->with(['media'])
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('livewire.projects.index', [
            'projects' => $projects
        ]);
    }
} 