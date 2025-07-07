<?php

use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Volt\Component;

new class extends Component {
    public $search = '';
    public $filter = 'all'; // all, active, completed
    public $sortBy = 'created_at';
    public $sortDirection = 'desc';
    
    public function mount()
    {
        // Initialize component
    }
    
    public function updatedSearch()
    {
        $this->resetPage();
    }
    
    public function updatedFilter()
    {
        $this->resetPage();
    }
    
    public function sortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
    }
    
    public function getProjectsProperty()
    {
        $query = Project::where('user_id', Auth::id())
            ->with(['tasks', 'materials', 'notes'])
            ->withCount(['tasks', 'materials', 'notes']);
            
        // Apply search
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%')
                  ->orWhere('series', 'like', '%' . $this->search . '%');
            });
        }
        
        // Apply filters
        switch ($this->filter) {
            case 'active':
                $query->whereHas('tasks', function ($q) {
                    $q->whereNull('completion_date');
                });
                break;
            case 'completed':
                $query->whereDoesntHave('tasks', function ($q) {
                    $q->whereNull('completion_date');
                })->whereHas('tasks');
                break;
        }
        
        // Apply sorting
        $query->orderBy($this->sortBy, $this->sortDirection);
        
        return $query->paginate(12);
    }
    
    public function deleteProject($projectId)
    {
        $project = Project::where('user_id', Auth::id())->findOrFail($projectId);
        $project->delete();
        
        session()->flash('message', 'Project deleted successfully.');
    }
}; ?>

<Layouts.App title="Projects">
        <div class="p-6">
        <div class="mb-6 flex justify-between items-start">
            <div>
                <h1 class="text-2xl font-bold text-gray-100">All Projects</h1>
                <p class="text-gray-400 mt-2">Manage your sewing projects</p>
            </div>
            <a href="{{ route('projects.create') }}" 
               class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                New Project
            </a>
        </div>

        @if($projects->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($projects as $project)
                    <x-project-card :project="$project" />
                @endforeach
            </div>

            <!-- Pagination -->
            @if($projects->hasPages())
                <div class="mt-8">
                    {{ $projects->links() }}
                </div>
            @endif
        @else
            <div class="text-center py-12">
                <div class="text-gray-400 mb-4">
                    <svg class="mx-auto h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-300 mb-2">No projects yet</h3>
                <p class="text-gray-500 mb-6">Get started by creating your first sewing project.</p>
                <a href="{{ route('projects.create') }}" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Create Project
                </a>
            </div>
        @endif
    </div>


</Layouts.App> 