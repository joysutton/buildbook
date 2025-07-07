@props(['project'])

<div class="relative h-64 md:h-80 lg:h-96">
    <!-- Background gradient -->
    <div class="absolute inset-0 bg-gradient-to-r from-gray-800 to-gray-700"></div>
    
    <!-- Main image (50% width) -->
    @if($project->getFirstMediaUrl('main'))
        <div class="absolute right-0 top-0 bottom-0 w-1/2 overflow-hidden">
            <img src="{{ $project->getFirstMediaUrl('main') }}" 
                 alt="Main project image"
                 class="w-full h-full object-cover">
            <!-- Fade overlay from left to right -->
            <div class="absolute inset-0 bg-gradient-to-r from-gray-900 via-gray-900/50 to-transparent"></div>
        </div>
    @endif
    
    <!-- Project info overlay -->
    <div class="relative h-full flex items-center p-6 md:p-8 lg:p-12">
        <div class="text-white">
            <div class="flex items-center justify-between mb-2">
                <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold">{{ $project->name }}</h1>
                <div class="flex items-center space-x-2">
                    <!-- Share URL button -->
                    @if($project->share)
                        <button wire:click="copyShareUrl" 
                                class="p-2 text-gray-300 hover:text-white hover:bg-gray-700 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-gray-800"
                                title="Copy share URL"
                                aria-label="Copy share URL for project {{ $project->name }}">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"></path>
                            </svg>
                        </button>
                    @endif
                    
                    <!-- Main image upload button -->
                    <button wire:click="openMainImageModal" 
                            class="p-2 text-gray-300 hover:text-white hover:bg-gray-700 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-gray-800"
                            title="Upload main project image"
                            aria-label="Upload main project image for {{ $project->name }}">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </button>
                    
                    <!-- Edit project button -->
                    <button wire:click="openProjectEditModal" 
                            class="p-2 text-gray-300 hover:text-white hover:bg-gray-700 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-gray-800"
                            title="Edit project"
                            aria-label="Edit project {{ $project->name }}">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </button>
                </div>
            </div>
            @if($project->series)
                <p class="text-xl md:text-2xl text-gray-300 mb-1">Series: {{ $project->series }}</p>
            @endif
            @if($project->version)
                <p class="text-xl md:text-2xl text-gray-300 mb-4">Version: {{ $project->version }}</p>
            @endif
            
            <!-- Share checkbox -->
            <label class="flex items-center space-x-2 cursor-pointer">
                <input type="checkbox" 
                       id="share-project-{{ $project->id }}"
                       {{ $project->share ? 'checked' : '' }}
                       wire:click="toggleProjectShare"
                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                       aria-label="Share project {{ $project->name }} in public view">
                <span class="text-gray-300">Share Project</span>
            </label>
        </div>
    </div>
</div> 