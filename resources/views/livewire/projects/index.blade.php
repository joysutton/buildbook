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
            <div class="flex flex-wrap -mx-3">
                @foreach($projects as $project)
                    <div class="w-full md:w-1/2 lg:w-1/3 px-3 mb-6">
                        <a href="{{ route('projects.show', $project) }}" class="block group">
                            <div class="relative overflow-hidden rounded-lg bg-gray-800 border border-gray-700 hover:border-gray-600 transition-all duration-200 h-[320px]">
                                <!-- Background image with fade effect -->
                                @if($project->getFirstMediaUrl('main'))
                                    <div class="absolute inset-0 bg-cover bg-center bg-no-repeat opacity-20 group-hover:opacity-30 transition-opacity duration-200"
                                         style="background-image: url('{{ $project->getFirstMediaUrl('main') }}')">
                                    </div>
                                @endif
                                
                                <!-- Content overlay -->
                                <div class="relative h-full flex flex-col justify-between p-4">
                                    <!-- Top section - empty for spacing -->
                                    <div class="flex-1"></div>
                                    
                                    <!-- Bottom section with project info -->
                                    <div class="mt-auto">
                                        <h3 class="text-lg font-semibold text-gray-100 mb-1 group-hover:text-blue-400 transition-colors">{{ $project->name }}</h3>
                                        @if($project->series)
                                            <p class="text-sm text-gray-400 mb-1">Series: {{ $project->series }}</p>
                                        @endif
                                        @if($project->version)
                                            <p class="text-sm text-gray-400">Version: {{ $project->version }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
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