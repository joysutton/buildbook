<Layouts.App :title="$project->name">
    <div class="min-h-screen bg-gray-900">
        <!-- Header Area -->
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
                                        class="p-2 text-gray-300 hover:text-white hover:bg-gray-700 rounded-lg transition-colors"
                                        title="Copy share URL">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"></path>
                                    </svg>
                                </button>
                            @endif
                            
                            <!-- Main image upload button -->
                            <button wire:click="openMainImageModal" 
                                    class="p-2 text-gray-300 hover:text-white hover:bg-gray-700 rounded-lg transition-colors"
                                    title="Upload main project image">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </button>
                            
                            <!-- Edit project button -->
                            <button wire:click="openProjectEditModal" 
                                    class="p-2 text-gray-300 hover:text-white hover:bg-gray-700 rounded-lg transition-colors"
                                    title="Edit project">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                               {{ $project->share ? 'checked' : '' }}
                               wire:click="toggleProjectShare"
                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <span class="text-gray-300">Share Project</span>
                    </label>
                </div>
            </div>
        </div>

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
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-semibold text-gray-100">Project Notes</h2>
                        <button wire:click="openNoteModal('project')" class="px-3 py-1 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 transition-colors">
                            Add Note
                        </button>
                    </div>
                    <div class="bg-gray-700 rounded-lg p-4 max-h-64 overflow-y-auto">
                        @if($project->notes->count() > 0)
                            <div class="space-y-3">
                                @foreach($project->notes as $note)
                                    <div class="border-l-4 border-blue-500 pl-3 group">
                                        <div class="flex items-start justify-between">
                                            <div class="flex-1">
                                                <p class="text-gray-300 text-sm">{{ $note->content }}</p>
                                                <p class="text-gray-500 text-xs mt-1">{{ $note->created_at->format('M j, Y g:i A') }}</p>
                                            </div>
                                            <div class="flex items-center space-x-1 opacity-0 group-hover:opacity-100 transition-opacity ml-2">
                                                <button wire:click="editNote({{ $note->id }})" 
                                                        class="p-1 text-gray-400 hover:text-blue-400 transition-colors"
                                                        title="Edit note">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                    </svg>
                                                </button>
                                                <button wire:click="deleteNote({{ $note->id }})" 
                                                        wire:confirm="Are you sure you want to delete this note?"
                                                        class="p-1 text-gray-400 hover:text-red-400 transition-colors"
                                                        title="Delete note">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
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
        <div class="bg-gray-800 border-b border-gray-700">
            <div class="px-6 md:px-8 lg:px-12">
                <div class="flex space-x-1">
                    <button 
                        wire:click="setTab('tasks')"
                        class="px-4 py-2 text-sm font-medium rounded-t-lg transition-colors {{ $activeTab === 'tasks' ? 'bg-gray-700 text-white' : 'bg-gray-900 text-gray-400 hover:text-gray-300 hover:bg-gray-800' }}"
                    >
                        Tasks
                    </button>
                    <button 
                        wire:click="setTab('materials')"
                        class="px-4 py-2 text-sm font-medium rounded-t-lg transition-colors {{ $activeTab === 'materials' ? 'bg-gray-700 text-white' : 'bg-gray-900 text-gray-400 hover:text-gray-300 hover:bg-gray-800' }}"
                    >
                        Materials
                    </button>
                    <button 
                        wire:click="setTab('references')"
                        class="px-4 py-2 text-sm font-medium rounded-t-lg transition-colors {{ $activeTab === 'references' ? 'bg-gray-700 text-white' : 'bg-gray-900 text-gray-400 hover:text-gray-300 hover:bg-gray-800' }}"
                    >
                        Reference Images
                    </button>
                </div>
            </div>
        </div>

        <!-- Content Area -->
        <div class="bg-gray-700 min-h-96">
            @if($activeTab === 'tasks')
                <div class="p-6 md:p-8 lg:p-12">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-semibold text-gray-100">Project Tasks</h3>
                        <button wire:click="openTaskModal()" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors">
                            Add New Task
                        </button>
                    </div>
                    
                    @if($project->tasks->count() > 0)
                        <div class="space-y-4">
                            @foreach($project->tasks as $task)
                                <div class="bg-gray-800 rounded-lg border border-gray-600 overflow-hidden relative">
                                    <div class="p-4">
                                        <div class="flex items-start space-x-4">
                                            <!-- Task image thumbnail -->
                                            <div class="flex-shrink-0 relative group">
                                                @if($task->getFirstMediaUrl('progress_image'))
                                                    <button wire:click.stop="openTaskImageViewModal('{{ $task->getFirstMediaUrl('progress_image') }}', '{{ $task->title }}')" 
                                                            class="block">
                                                        <img src="{{ $task->getFirstMediaUrl('progress_image') }}" 
                                                             alt="Task progress" 
                                                             class="w-32 h-32 object-cover rounded cursor-pointer hover:opacity-80 transition-opacity">
                                                    </button>
                                                    
                                                    <!-- Delete image button -->
                                                    <button wire:click.stop="deleteTaskImage({{ $task->id }})"
                                                            wire:confirm="Are you sure you want to delete this progress image?"
                                                            class="absolute -top-1 -right-1 p-1 bg-red-600 text-white rounded-full opacity-0 group-hover:opacity-100 transition-opacity">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                        </svg>
                                                    </button>
                                                @else
                                                    <div class="w-32 h-32 bg-gray-600 rounded flex items-center justify-center">
                                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                        </svg>
                                                    </div>
                                                @endif
                                                
                                                <!-- Upload image button - always visible -->
                                                <button wire:click.stop="openTaskImageModal({{ $task->id }})"
                                                        class="absolute -bottom-1 -right-1 p-1 bg-blue-600 text-white rounded-full hover:bg-blue-700 transition-colors">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                            <!-- Task content -->
                                            <div class="flex-1">
                                                <h4 class="text-lg font-medium text-gray-100 mb-2">{{ $task->title }}</h4>
                                                @if($task->description)
                                                    <p class="text-gray-400 mb-3">{{ $task->description }}</p>
                                                @endif
                                                <div class="flex items-center justify-between">
                                                    <div class="flex items-center space-x-4">
                                                        <div>
                                                            <label class="text-sm text-gray-400">Due Date:</label>
                                                            <input type="date" 
                                                                   value="{{ $task->due_date ? $task->due_date->format('Y-m-d') : '' }}"
                                                                   wire:change="updateTaskDueDate({{ $task->id }}, $event.target.value)"
                                                                   class="ml-2 bg-gray-700 border border-gray-600 rounded px-2 py-1 text-sm text-gray-300">
                                                        </div>
                                                        
                                                        <div>
                                                            @if($task->completion_date)
                                                                <span class="text-sm text-green-400">Completed: {{ $task->completion_date->format('M j, Y') }}</span>
                                                            @else
                                                                <label class="flex items-center space-x-2">
                                                                    <input type="checkbox" 
                                                                           wire:click="toggleTaskCompletion({{ $task->id }})"
                                                                           class="rounded border-gray-600 text-blue-600">
                                                                    <span class="text-sm text-gray-400">Completed?</span>
                                                                </label>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="flex items-center space-x-2">
                                                        <label class="flex items-center space-x-2">
                                                            <input type="checkbox" 
                                                                   {{ $task->share ? 'checked' : '' }}
                                                                   wire:click="updateTaskShare({{ $task->id }}, $event.target.checked)"
                                                                   class="rounded border-gray-600 text-blue-600">
                                                            <span class="text-sm text-gray-400">Share?</span>
                                                        </label>
                                                        
                                                        <button wire:click.stop="openTaskModal({{ $task->id }})" class="p-1 text-gray-400 hover:text-gray-300">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                            </svg>
                                                        </button>
                                                        
                                                        <button wire:click.stop="deleteTask({{ $task->id }})" 
                                                                wire:confirm="Are you sure you want to delete this task?"
                                                                class="p-1 text-red-400 hover:text-red-300">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Vertical Notes Tab -->
                                            <button wire:click="toggleTaskNotes({{ $task->id }})"
                                                    class="absolute top-0 right-0 mt-0 mr-0 bg-gray-300 text-gray-800 px-5 py-2 rounded-bl-lg shadow-lg font-semibold text-xs tracking-widest uppercase hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-400 z-10"
                                                    style="">
                                                Toggle Notes
                                            </button>
                                        </div>
                                    </div>
                                    <!-- Expandable Notes Section -->
                                    @if($expandedTaskId === $task->id)
                                        <div class="border-t border-gray-600 bg-gray-750 p-4">
                                            <div class="flex justify-between items-center mb-3">
                                                <h5 class="text-sm font-medium text-gray-300">Task Notes</h5>
                                                <button wire:click="addNoteToTask({{ $task->id }})" 
                                                        class="px-3 py-1 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 transition-colors">
                                                    Add Note
                                                </button>
                                            </div>
                                            
                                            @if($task->notes->count() > 0)
                                                <div class="space-y-3">
                                                    @foreach($task->notes as $note)
                                                        <div class="border-l-4 border-blue-500 pl-3 group">
                                                            <div class="flex items-start justify-between">
                                                                <div class="flex-1">
                                                                    <p class="text-gray-300 text-sm">{{ $note->content }}</p>
                                                                    <p class="text-gray-500 text-xs mt-1">{{ $note->created_at->format('M j, Y g:i A') }}</p>
                                                                </div>
                                                                <div class="flex items-center space-x-1 opacity-0 group-hover:opacity-100 transition-opacity ml-2">
                                                                    <button wire:click="editNote({{ $note->id }})" 
                                                                            class="p-1 text-gray-400 hover:text-blue-400 transition-colors"
                                                                            title="Edit note">
                                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                                        </svg>
                                                                    </button>
                                                                    <button wire:click="deleteNote({{ $note->id }})" 
                                                                            wire:confirm="Are you sure you want to delete this note?"
                                                                            class="p-1 text-gray-400 hover:text-red-400 transition-colors"
                                                                            title="Delete note">
                                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                                        </svg>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @else
                                                <p class="text-gray-500 italic text-sm">No notes yet for this task.</p>
                                            @endif
                                        </div>
                                    @endif
                                </div>
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
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-semibold text-gray-100">Project Materials</h3>
                        <button wire:click="openMaterialModal()" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors">
                            Add Material
                        </button>
                    </div>
                    
                    @if($project->materials->count() > 0)
                        <div class="bg-gray-800 rounded-lg overflow-hidden">
                            <table class="w-full">
                                <thead class="bg-gray-900">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-300">Title</th>
                                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-300">Description</th>
                                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-300">Amount</th>
                                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-300">Source</th>
                                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-300">Cost</th>
                                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-300">Acquired</th>
                                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-300">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-700">
                                    @foreach($project->materials as $material)
                                        <tr class="hover:bg-gray-750 cursor-pointer" wire:click="toggleMaterialNotes({{ $material->id }})">
                                            <td class="px-4 py-4 text-sm text-gray-300">{{ $material->name }}</td>
                                            <td class="px-4 py-4 text-sm text-gray-400 max-w-xs">
                                                <div class="line-clamp-3">{{ $material->description }}</div>
                                            </td>
                                            <td class="px-4 py-4 text-sm text-gray-300">{{ $material->amount }}</td>
                                            <td class="px-4 py-4 text-sm text-gray-300">{{ $material->source }}</td>
                                            <td class="px-4 py-4 text-sm text-gray-300">
                                                <div class="space-y-1">
                                                    @if($material->est_cost)
                                                        <div>Est: ${{ number_format($material->est_cost / 100, 2) }}</div>
                                                    @endif
                                                    @if($material->actual_cost)
                                                        <div>Actual: ${{ number_format($material->actual_cost / 100, 2) }}</div>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-4 py-4 text-sm text-gray-300">
                                                <input type="checkbox" 
                                                       {{ $material->acquired ? 'checked' : '' }}
                                                       wire:click.stop="updateMaterialAcquired({{ $material->id }}, $event.target.checked)"
                                                       class="rounded border-gray-600 text-blue-600">
                                            </td>
                                            <td class="px-4 py-4 text-sm text-gray-300">
                                                <div class="flex space-x-2">
                                                    <button wire:click.stop="openMaterialModal({{ $material->id }})" class="p-1 text-gray-400 hover:text-gray-300">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                        </svg>
                                                    </button>
                                                    <button wire:click.stop="deleteMaterial({{ $material->id }})" 
                                                            wire:confirm="Are you sure you want to delete this material?"
                                                            class="p-1 text-red-400 hover:text-red-300">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                        </svg>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        
                                        <!-- Expandable Notes Row -->
                                        @if($expandedMaterialId === $material->id)
                                            <tr class="bg-gray-750">
                                                <td colspan="7" class="px-4 py-4">
                                                    <div class="flex justify-between items-center mb-3">
                                                        <h5 class="text-sm font-medium text-gray-300">Material Notes</h5>
                                                        <button wire:click="addNoteToMaterial({{ $material->id }})" 
                                                                class="px-3 py-1 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 transition-colors">
                                                            Add Note
                                                        </button>
                                                    </div>
                                                    
                                                    @if($material->notes->count() > 0)
                                                        <div class="space-y-3">
                                                            @foreach($material->notes as $note)
                                                                <div class="border-l-4 border-blue-500 pl-3 group">
                                                                    <div class="flex items-start justify-between">
                                                                        <div class="flex-1">
                                                                            <p class="text-gray-300 text-sm">{{ $note->content }}</p>
                                                                            <p class="text-gray-500 text-xs mt-1">{{ $note->created_at->format('M j, Y g:i A') }}</p>
                                                                        </div>
                                                                        <div class="flex items-center space-x-1 opacity-0 group-hover:opacity-100 transition-opacity ml-2">
                                                                            <button wire:click="editNote({{ $note->id }})" 
                                                                                    class="p-1 text-gray-400 hover:text-blue-400 transition-colors"
                                                                                    title="Edit note">
                                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                                        </svg>
                                                                    </button>
                                                                    <button wire:click="deleteNote({{ $note->id }})" 
                                                                            wire:confirm="Are you sure you want to delete this note?"
                                                                            class="p-1 text-gray-400 hover:text-red-400 transition-colors"
                                                                            title="Delete note">
                                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                                        </svg>
                                                                    </button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    @else
                                                        <p class="text-gray-500 italic text-sm">No notes yet for this material.</p>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-gray-900">
                                    <tr>
                                        <td colspan="4" class="px-4 py-3 text-sm font-medium text-gray-300">Totals:</td>
                                        <td class="px-4 py-3 text-sm text-gray-300">
                                            <div class="space-y-1">
                                                <div>Est: ${{ number_format($project->materials->sum('est_cost') / 100, 2) }}</div>
                                                <div>Actual: ${{ number_format($project->materials->sum('actual_cost') / 100, 2) }}</div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-300">
                                            {{ $project->materials->where('acquired', true)->count() }} / {{ $project->materials->count() }}
                                            ({{ $project->materials->count() > 0 ? round(($project->materials->where('acquired', true)->count() / $project->materials->count()) * 100) : 0 }}%)
                                        </td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-12">
                            <p class="text-gray-400">No materials added yet.</p>
                        </div>
                    @endif
                </div>
            @elseif($activeTab === 'references')
                <div class="p-6 md:p-8 lg:p-12">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-semibold text-gray-100">Reference Images</h3>
                        <button wire:click="openImageUploadModal()" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors">
                            Add Image
                        </button>
                    </div>
                    
                    @if($project->getMedia('references')->count() > 0)
                        <div class="flex flex-wrap gap-4">
                            @foreach($project->getMedia('references') as $media)
                                <div class="relative group flex-shrink-0">
                                    <div class="relative overflow-hidden rounded-lg bg-gray-800 border border-gray-600 max-w-xs">
                                        <img src="{{ $media->getUrl() }}" 
                                             alt="Reference image" 
                                             class="w-auto h-auto max-w-full max-h-64 object-contain cursor-pointer"
                                             wire:click="openImageModal('{{ $media->getUrl() }}')">
                                        
                                        <!-- Delete button overlay -->
                                        <button wire:click="deleteImage({{ $media->id }})"
                                                wire:confirm="Are you sure you want to delete this image?"
                                                class="absolute top-2 right-2 p-1 bg-red-600 text-white rounded opacity-0 group-hover:opacity-100 transition-opacity">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </div>
                                    
                                    <!-- Share checkbox -->
                                    <div class="mt-2">
                                        <label class="flex items-center space-x-2">
                                            <input type="checkbox" 
                                                   {{ $media->getCustomProperty('share') ? 'checked' : '' }}
                                                   wire:click="toggleImageShare({{ $media->id }}, $event.target.checked)"
                                                   class="rounded border-gray-600 text-blue-600">
                                            <span class="text-sm text-gray-400">Share image</span>
                                        </label>
                                    </div>
                                </div>
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