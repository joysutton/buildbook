@props(['show' => false, 'projectName' => '', 'projectSeries' => '', 'projectVersion' => '', 'projectDescription' => '', 'wireClose' => 'closeProjectEditModal', 'wireSave' => 'updateProject'])

<x-modal :show="$show" title="Edit Project" :wire-close="$wireClose">
    <div class="space-y-4">
        <div>
            <label for="projectName" class="block text-sm font-medium text-gray-300 mb-2">Project Name</label>
            <input 
                wire:model="projectName"
                type="text" 
                id="projectName"
                class="w-full bg-gray-700 border border-gray-600 rounded-md px-3 py-2 text-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                placeholder="Enter project name">
            @error('projectName') 
                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
        
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="projectSeries" class="block text-sm font-medium text-gray-300 mb-2">Series</label>
                <input 
                    wire:model="projectSeries"
                    type="text" 
                    id="projectSeries"
                    class="w-full bg-gray-700 border border-gray-600 rounded-md px-3 py-2 text-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="Enter series name">
                @error('projectSeries') 
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="projectVersion" class="block text-sm font-medium text-gray-300 mb-2">Version</label>
                <input 
                    wire:model="projectVersion"
                    type="text" 
                    id="projectVersion"
                    class="w-full bg-gray-700 border border-gray-600 rounded-md px-3 py-2 text-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="e.g., v1.0">
                @error('projectVersion') 
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>
        
        <div>
            <label for="projectDescription" class="block text-sm font-medium text-gray-300 mb-2">Description</label>
            <textarea 
                wire:model="projectDescription"
                id="projectDescription"
                rows="4"
                class="w-full bg-gray-700 border border-gray-600 rounded-md px-3 py-2 text-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                placeholder="Enter project description"></textarea>
            @error('projectDescription') 
                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
    </div>
    
    <x-slot name="footer">
        <button 
            wire:click="{{ $wireSave }}"
            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors"
        >
            Update Project
        </button>
    </x-slot>
</x-modal> 