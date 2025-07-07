@props(['project'])

<div class="bg-gray-800 rounded-lg border border-gray-600 overflow-hidden hover:border-gray-500 transition-colors">
    <!-- Project image -->
    @if($project->getFirstMediaUrl('main'))
        <div class="relative h-48 overflow-hidden">
            <img src="{{ $project->getFirstMediaUrl('main') }}" 
                 alt="{{ $project->name }}"
                 class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-gray-900/60 to-transparent"></div>
        </div>
    @else
        <div class="h-48 bg-gray-700 flex items-center justify-center">
            <svg class="w-12 h-12 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
        </div>
    @endif
    
    <!-- Project content -->
    <div class="p-6">
        <div class="flex items-start justify-between mb-2">
            <h3 class="text-lg font-semibold text-gray-100 truncate">{{ $project->name }}</h3>
            @if($project->share)
                <span class="flex-shrink-0 ml-2 px-2 py-1 text-xs bg-green-600 text-white rounded-full">Shared</span>
            @endif
        </div>
        
        @if($project->series || $project->version)
            <div class="flex items-center space-x-4 mb-3 text-sm text-gray-400">
                @if($project->series)
                    <span>Series: {{ $project->series }}</span>
                @endif
                @if($project->version)
                    <span>Version: {{ $project->version }}</span>
                @endif
            </div>
        @endif
        
        @if($project->description)
            <p class="text-gray-400 text-sm mb-4 line-clamp-2">{{ $project->description }}</p>
        @endif
        
        <!-- Project stats -->
        <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
            <div class="flex items-center space-x-4">
                <span>{{ $project->tasks->count() }} tasks</span>
                <span>{{ $project->materials->count() }} materials</span>
                <span>{{ $project->getMedia('references')->count() }} images</span>
            </div>
            <span>{{ $project->updated_at->diffForHumans() }}</span>
        </div>
        
        <!-- Action buttons -->
        <div class="flex items-center justify-between">
            <a href="{{ route('projects.show', $project) }}" 
               class="px-4 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 transition-colors">
                View Project
            </a>
            
            @if($project->share)
                <a href="{{ route('projects.share', $project) }}" 
                   target="_blank"
                   class="px-3 py-2 text-gray-400 hover:text-gray-300 transition-colors"
                   title="View shared page">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                    </svg>
                </a>
            @endif
        </div>
    </div>
</div> 