<Layouts.App :title="$project->name">
    <div class="min-h-screen bg-gray-900">
        <!-- Header Area -->
        <x-project-header :project="$project" />

        <!-- Info Area -->
        <div class="p-6 md:p-8 lg:p-12 bg-gray-800">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Description -->
                <div>
                    <h2 class="text-xl font-semibold text-gray-100 mb-4">Description</h2>
                    <div class="bg-gray-700 rounded-lg p-4">
                        @if($project->description)
                            <p class="text-gray-300 whitespace-pre-wrap">{{ $project->description }}</p>
                        @else
                            <p class="text-gray-500 italic">No description provided.</p>
                        @endif
                    </div>
                </div>

                <!-- Notes -->
                <div>
                    <x-section-header 
                        title="Project Notes" 
                        :action="['wire' => 'openNoteModal(\'project\')', 'label' => 'Add Note']" 
                    />
                    <div class="bg-gray-700 rounded-lg p-4 max-h-64 overflow-y-auto">
                        @if($project->notes->count() > 0)
                            <div class="space-y-3">
                                @foreach($project->notes as $note)
                                    <x-note :note="$note" />
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 italic">No notes yet.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Section Tabs -->
        <x-tab-navigation 
            :tabs="[
                ['key' => 'tasks', 'label' => 'Tasks'],
                ['key' => 'materials', 'label' => 'Materials'],
                ['key' => 'references', 'label' => 'Reference Images']
            ]"
            :active-tab="$activeTab"
        />

        <!-- Content Area -->
        <div class="bg-gray-700 min-h-96">
            @if($activeTab === 'tasks')
                <div class="p-6 md:p-8 lg:p-12">
                    <x-section-header 
                        title="Project Tasks" 
                        :action="['wire' => 'openTaskModal()', 'label' => 'Add New Task']" 
                    />
                    
                    @if($project->tasks->count() > 0)
                        <div class="space-y-4">
                            @foreach($project->tasks as $task)
                                <x-task-card :task="$task" :expanded-task-id="$expandedTaskId" />
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <p class="text-gray-400">No tasks created yet.</p>
                        </div>
                    @endif
                </div>
            @elseif($activeTab === 'materials')
                <div class="p-6 md:p-8 lg:p-12">
                    <x-section-header 
                        title="Project Materials" 
                        :action="['wire' => 'openMaterialModal()', 'label' => 'Add Material']" 
                    />
                    
                    @if($project->materials->count() > 0)
                        <x-material-table :materials="$project->materials" :expanded-material-id="$expandedMaterialId" />
                        
                        <!-- Totals Row -->
                        <div class="bg-gray-900 rounded-lg mt-4 p-4">
                            <div class="grid grid-cols-3 gap-4 text-sm">
                                <div class="text-gray-300">
                                    <span class="font-medium">Est. Total:</span> ${{ number_format($project->materials->sum('est_cost') / 100, 2) }}
                                </div>
                                <div class="text-gray-300">
                                    <span class="font-medium">Actual Total:</span> ${{ number_format($project->materials->sum('actual_cost') / 100, 2) }}
                                </div>
                                <div class="text-gray-300">
                                    <span class="font-medium">Acquired:</span> {{ $project->materials->where('acquired', true)->count() }} / {{ $project->materials->count() }}
                                    ({{ $project->materials->count() > 0 ? round(($project->materials->where('acquired', true)->count() / $project->materials->count()) * 100) : 0 }}%)
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-12">
                            <p class="text-gray-400">No materials added yet.</p>
                        </div>
                    @endif
                </div>
            @elseif($activeTab === 'references')
                <div class="p-6 md:p-8 lg:p-12">
                    <x-section-header 
                        title="Reference Images" 
                        :action="['wire' => 'openImageUploadModal()', 'label' => 'Add Image']" 
                    />
                    
                    @if($project->getMedia('references')->count() > 0)
                        <div class="flex flex-wrap gap-4">
                            @foreach($project->getMedia('references') as $media)
                                <x-reference-image :media="$media" />
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <p class="text-gray-400">No reference images uploaded yet.</p>
                        </div>
                    @endif
                </div>
            @endif
        </div>

        <!-- Buttons Area -->
        <div class="bg-gray-800 border-t border-gray-700 p-6 md:p-8 lg:p-12">
            <div class="flex justify-center space-x-4">
                @if($project->share)
                    <a href="{{ route('projects.share', $project) }}" 
                       target="_blank"
                       class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                        View Web Page
                    </a>
                @else
                    <button disabled class="px-6 py-3 bg-gray-600 text-gray-400 rounded-lg cursor-not-allowed">
                        View Web Page (Enable sharing first)
                    </button>
                @endif
                @if($project->share)
                    <a href="{{ route('projects.pdf', $project) }}" 
                       target="_blank"
                       class="px-6 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                        Download PDF
                    </a>
                @else
                    <button disabled class="px-6 py-3 bg-gray-600 text-gray-400 rounded-lg cursor-not-allowed">
                        Download PDF (Enable sharing first)
                    </button>
                @endif
            </div>
        </div>
    </div>

    <!-- Note Modal -->
    @if($showNoteModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-gray-800 rounded-lg p-6 w-full max-w-md mx-4">
                <h3 class="text-lg font-semibold text-gray-100 mb-4">
                    {{ $editingNoteId ? 'Edit Note' : 'Add Note' }}
                </h3>
                
                <div class="mb-4">
                    <label for="noteContent" class="block text-sm font-medium text-gray-300 mb-2">Note Content</label>
                    <textarea 
                        wire:model="noteContent"
                        id="noteContent"
                        rows="4"
                        class="w-full bg-gray-700 border border-gray-600 rounded-md px-3 py-2 text-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Enter your note here..."
                    ></textarea>
                    @error('noteContent') 
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button 
                        wire:click="closeNoteModal"
                        class="px-4 py-2 text-gray-300 hover:text-gray-100 transition-colors"
                    >
                        Cancel
                    </button>
                    <button 
                        wire:click="{{ $editingNoteId ? 'updateNote' : 'saveNote' }}"
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors"
                    >
                        {{ $editingNoteId ? 'Update Note' : 'Save Note' }}
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Task Modal -->
    @if($showTaskModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-gray-800 rounded-lg p-6 w-full max-w-md mx-4">
                <h3 class="text-lg font-semibold text-gray-100 mb-4">
                    {{ $editingTask ? 'Edit Task' : 'Add New Task' }}
                </h3>
                
                <div class="space-y-4">
                    <div>
                        <label for="taskTitle" class="block text-sm font-medium text-gray-300 mb-2">Title</label>
                        <input 
                            wire:model="taskTitle"
                            type="text"
                            id="taskTitle"
                            class="w-full bg-gray-700 border border-gray-600 rounded-md px-3 py-2 text-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Enter task title..."
                        >
                        @error('taskTitle') 
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="taskDescription" class="block text-sm font-medium text-gray-300 mb-2">Description</label>
                        <textarea 
                            wire:model="taskDescription"
                            id="taskDescription"
                            rows="3"
                            class="w-full bg-gray-700 border border-gray-600 rounded-md px-3 py-2 text-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Enter task description..."
                        ></textarea>
                        @error('taskDescription') 
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="taskDueDate" class="block text-sm font-medium text-gray-300 mb-2">Due Date</label>
                        <input 
                            wire:model="taskDueDate"
                            type="date"
                            id="taskDueDate"
                            class="w-full bg-gray-700 border border-gray-600 rounded-md px-3 py-2 text-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        >
                        @error('taskDueDate') 
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label class="flex items-center space-x-2">
                            <input 
                                wire:model="taskShare"
                                type="checkbox"
                                class="rounded border-gray-600 text-blue-600"
                            >
                            <span class="text-sm text-gray-300">Share this task</span>
                        </label>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3 mt-6">
                    <button 
                        wire:click="closeTaskModal"
                        class="px-4 py-2 text-gray-300 hover:text-gray-100 transition-colors"
                    >
                        Cancel
                    </button>
                    <button 
                        wire:click="saveTask"
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors"
                    >
                        {{ $editingTask ? 'Update Task' : 'Create Task' }}
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Material Modal -->
    @if($showMaterialModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-gray-800 rounded-lg p-6 w-full max-w-md mx-4 max-h-[90vh] overflow-y-auto">
                <h3 class="text-lg font-semibold text-gray-100 mb-4">
                    {{ $editingMaterial ? 'Edit Material' : 'Add New Material' }}
                </h3>
                
                <div class="space-y-4">
                    <div>
                        <label for="materialName" class="block text-sm font-medium text-gray-300 mb-2">Name</label>
                        <input 
                            wire:model="materialName"
                            type="text"
                            id="materialName"
                            class="w-full bg-gray-700 border border-gray-600 rounded-md px-3 py-2 text-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Enter material name..."
                        >
                        @error('materialName') 
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="materialDescription" class="block text-sm font-medium text-gray-300 mb-2">Description</label>
                        <textarea 
                            wire:model="materialDescription"
                            id="materialDescription"
                            rows="3"
                            class="w-full bg-gray-700 border border-gray-600 rounded-md px-3 py-2 text-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Enter material description..."
                        ></textarea>
                        @error('materialDescription') 
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="materialAmount" class="block text-sm font-medium text-gray-300 mb-2">Amount</label>
                            <input 
                                wire:model="materialAmount"
                                type="text"
                                id="materialAmount"
                                class="w-full bg-gray-700 border border-gray-600 rounded-md px-3 py-2 text-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="e.g., 2 yards"
                            >
                            @error('materialAmount') 
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="materialSource" class="block text-sm font-medium text-gray-300 mb-2">Source</label>
                            <input 
                                wire:model="materialSource"
                                type="text"
                                id="materialSource"
                                class="w-full bg-gray-700 border border-gray-600 rounded-md px-3 py-2 text-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="e.g., Fine Fabrics"
                            >
                            @error('materialSource') 
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="materialEstCostDisplay" class="block text-sm font-medium text-gray-300 mb-2">Estimated Cost ($)</label>
                            <input 
                                wire:model="materialEstCostDisplay"
                                type="text"
                                id="materialEstCostDisplay"
                                class="w-full bg-gray-700 border border-gray-600 rounded-md px-3 py-2 text-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="0.00"
                            >
                            @error('materialEstCostDisplay') 
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="materialActualCostDisplay" class="block text-sm font-medium text-gray-300 mb-2">Actual Cost ($)</label>
                            <input 
                                wire:model="materialActualCostDisplay"
                                type="text"
                                id="materialActualCostDisplay"
                                class="w-full bg-gray-700 border border-gray-600 rounded-md px-3 py-2 text-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="0.00"
                            >
                            @error('materialActualCostDisplay') 
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="space-y-2">
                        <label class="flex items-center space-x-2">
                            <input 
                                wire:model="materialAcquired"
                                type="checkbox"
                                class="rounded border-gray-600 text-blue-600"
                            >
                            <span class="text-sm text-gray-300">Acquired</span>
                        </label>
                        
                        <label class="flex items-center space-x-2">
                            <input 
                                wire:model="materialShare"
                                type="checkbox"
                                class="rounded border-gray-600 text-blue-600"
                            >
                            <span class="text-sm text-gray-300">Share this material</span>
                        </label>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3 mt-6">
                    <button 
                        wire:click="closeMaterialModal"
                        class="px-4 py-2 text-gray-300 hover:text-gray-100 transition-colors"
                    >
                        Cancel
                    </button>
                    <button 
                        wire:click="saveMaterial"
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors"
                    >
                        {{ $editingMaterial ? 'Update Material' : 'Create Material' }}
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Image View Modal -->
    @if($showImageModal)
        <div class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50" wire:click="closeImageModal">
            <div class="relative max-w-4xl max-h-[90vh] mx-4">
                <img src="{{ $selectedImageUrl }}" 
                     alt="Full size image" 
                     class="max-w-full max-h-full object-contain">
                <button wire:click="closeImageModal" 
                        class="absolute top-4 right-4 p-2 bg-gray-800 text-white rounded-full hover:bg-gray-700 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
    @endif

    <!-- Image Upload Modal -->
    @if($showImageUploadModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-gray-800 rounded-lg p-6 w-full max-w-md mx-4">
                <h3 class="text-lg font-semibold text-gray-100 mb-4">Upload Reference Image</h3>
                
                <div class="space-y-4">
                    <div>
                        <label for="imageFile" class="block text-sm font-medium text-gray-300 mb-2">Select Image</label>
                        <input 
                            wire:model="imageFile"
                            type="file"
                            id="imageFile"
                            accept="image/*"
                            class="w-full bg-gray-700 border border-gray-600 rounded-md px-3 py-2 text-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        >
                        @error('imageFile') 
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label class="flex items-center space-x-2">
                            <input 
                                wire:model="imageShare"
                                type="checkbox"
                                class="rounded border-gray-600 text-blue-600"
                            >
                            <span class="text-sm text-gray-300">Share this image</span>
                        </label>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3 mt-6">
                    <button 
                        wire:click="closeImageUploadModal"
                        class="px-4 py-2 text-gray-300 hover:text-gray-100 transition-colors"
                    >
                        Cancel
                    </button>
                    <button 
                        wire:click="uploadImage"
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors"
                    >
                        Upload Image
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Project Edit Modal -->
    @if($showProjectEditModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-gray-800 rounded-lg p-6 w-full max-w-md mx-4 max-h-[90vh] overflow-y-auto">
                <h3 class="text-lg font-semibold text-gray-100 mb-4">Edit Project</h3>
                
                <div class="space-y-4">
                    <div>
                        <label for="projectName" class="block text-sm font-medium text-gray-300 mb-2">Project Name</label>
                        <input 
                            wire:model="projectName"
                            type="text"
                            id="projectName"
                            class="w-full bg-gray-700 border border-gray-600 rounded-md px-3 py-2 text-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Enter project name..."
                        >
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
                                placeholder="e.g., Summer Collection"
                            >
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
                                placeholder="e.g., 1.0"
                            >
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
                            placeholder="Enter project description..."
                        ></textarea>
                        @error('projectDescription') 
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3 mt-6">
                    <button 
                        wire:click="closeProjectEditModal"
                        class="px-4 py-2 text-gray-300 hover:text-gray-100 transition-colors"
                    >
                        Cancel
                    </button>
                    <button 
                        wire:click="saveProject"
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors"
                    >
                        Save Changes
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Task Progress Image Modal -->
    @if($showTaskImageModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-gray-800 rounded-lg p-6 w-full max-w-md mx-4">
                <h3 class="text-lg font-semibold text-gray-100 mb-4">Upload Progress Image</h3>
                
                <div class="space-y-4">
                    <div>
                        <label for="taskImageFile" class="block text-sm font-medium text-gray-300 mb-2">Select Progress Image</label>
                        <input 
                            wire:model="taskImageFile"
                            type="file"
                            id="taskImageFile"
                            accept="image/*"
                            class="w-full bg-gray-700 border border-gray-600 rounded-md px-3 py-2 text-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        >
                        <div wire:loading wire:target="taskImageFile" class="text-blue-400 text-sm mt-1">
                            Uploading file...
                        </div>
                        @error('taskImageFile') 
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <p class="text-sm text-gray-400">
                        This will replace any existing progress image for this task.
                    </p>
                </div>
                
                <div class="flex justify-end space-x-3 mt-6">
                    <button 
                        wire:click="closeTaskImageModal"
                        class="px-4 py-2 text-gray-300 hover:text-gray-100 transition-colors"
                    >
                        Cancel
                    </button>
                    <button 
                        wire:click="uploadTaskImage"
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                        {{ !$taskImageFileSelected ? 'disabled' : '' }}
                    >
                        Upload Image
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Task Image View Modal -->
    @if($showTaskImageViewModal)
        <div class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50" wire:click="closeTaskImageViewModal">
            <div class="relative max-w-4xl max-h-[90vh] mx-4">
                <div class="bg-gray-800 rounded-lg p-4 mb-4">
                    <h3 class="text-lg font-semibold text-gray-100">{{ $taskImageViewTitle }} - Progress Image</h3>
                </div>
                <img src="{{ $taskImageViewUrl }}" 
                     alt="Task progress image" 
                     class="max-w-full max-h-full object-contain">
                <button wire:click="closeTaskImageViewModal" 
                        class="absolute top-4 right-4 p-2 bg-gray-800 text-white rounded-full hover:bg-gray-700 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
    @endif

    <!-- Main Project Image Modal -->
    @if($showMainImageModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-gray-800 rounded-lg p-6 w-full max-w-md mx-4">
                <h3 class="text-lg font-semibold text-gray-100 mb-4">Upload Main Project Image</h3>
                
                <div class="space-y-4">
                    <div>
                        <label for="mainImageFile" class="block text-sm font-medium text-gray-300 mb-2">Select Main Image</label>
                        <input 
                            wire:model="mainImageFile"
                            type="file"
                            id="mainImageFile"
                            accept="image/*"
                            class="w-full bg-gray-700 border border-gray-600 rounded-md px-3 py-2 text-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        >
                        @error('mainImageFile') 
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <p class="text-sm text-gray-400">
                        This will replace any existing main image for this project.
                    </p>
                    
                    @if($project->getFirstMediaUrl('main'))
                        <div class="bg-gray-700 rounded-lg p-4">
                            <p class="text-sm text-gray-300 mb-2">Current main image:</p>
                            <img src="{{ $project->getFirstMediaUrl('main') }}" 
                                 alt="Current main image" 
                                 class="w-full h-32 object-cover rounded">
                            <button wire:click="deleteMainImage"
                                    wire:confirm="Are you sure you want to delete the main project image?"
                                    class="mt-2 px-3 py-1 bg-red-600 text-white text-sm rounded hover:bg-red-700 transition-colors">
                                Delete Current Image
                            </button>
                        </div>
                    @endif
                </div>
                
                <div class="flex justify-end space-x-3 mt-6">
                    <button 
                        wire:click="closeMainImageModal"
                        class="px-4 py-2 text-gray-300 hover:text-gray-100 transition-colors"
                    >
                        Cancel
                    </button>
                    <button 
                        wire:click="uploadMainImage"
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors"
                    >
                        Upload Image
                    </button>
                </div>
            </div>
        </div>
    @endif
</Layouts.App>

<script>
    // Handle clipboard copy
    document.addEventListener('livewire:init', () => {
        Livewire.on('copyToClipboard', (event) => {
            navigator.clipboard.writeText(event.text).then(() => {
                // Show success notification
                const notification = document.createElement('div');
                notification.className = 'fixed top-4 right-4 bg-green-600 text-white px-4 py-2 rounded-lg shadow-lg z-50';
                notification.textContent = 'Share URL copied to clipboard!';
                document.body.appendChild(notification);
                
                // Remove notification after 3 seconds
                setTimeout(() => {
                    notification.remove();
                }, 3000);
            }).catch(err => {
                console.error('Failed to copy: ', err);
                // Fallback: show URL in alert
                alert('Share URL: ' + event.text);
            });
        });
    });
</script> 