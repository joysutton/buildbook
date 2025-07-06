<div class="space-y-1">
    @if($projects->count() > 0)
        @foreach($projects as $project)
            <a href="{{ route('projects.show', $project) }}" 
               class="block px-2 py-1.5 text-sm text-gray-300 hover:text-white hover:bg-gray-700 rounded transition-colors {{ request()->routeIs('projects.show') && request()->route('project') && request()->route('project')->id === $project->id ? 'bg-gray-700 text-white' : '' }}"
               wire:navigate>
                <div class="flex items-center gap-2">
                    <div class="w-2 h-2 rounded-full bg-blue-400 flex-shrink-0"></div>
                    <span class="truncate">{{ $project->name }}</span>
                </div>
            </a>
        @endforeach
    @else
        <div class="text-center py-2">
            <p class="text-xs text-gray-500">No projects yet</p>
        </div>
    @endif
</div>
