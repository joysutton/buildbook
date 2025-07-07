@props(['task', 'expandedTaskId' => 0])

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
                            class="absolute -top-1 -right-1 p-1 bg-red-600 text-white rounded-full opacity-0 group-hover:opacity-100 focus:opacity-100 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 focus:ring-offset-gray-800 transition-opacity"
                            title="Delete progress image"
                            aria-label="Delete progress image for task: {{ $task->title }}">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
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
                        class="absolute -bottom-1 -right-1 p-1 bg-blue-600 text-white rounded-full hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-gray-800 transition-colors"
                        aria-label="Upload progress image for task: {{ $task->title }}">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
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
                                           id="completed-{{ $task->id }}"
                                           wire:click="toggleTaskCompletion({{ $task->id }})"
                                           class="rounded border-gray-600 text-blue-600"
                                           aria-label="Mark task {{ $task->title }} as completed">
                                    <span class="text-sm text-gray-400">Completed?</span>
                                </label>
                            @endif
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-2">
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" 
                                   id="share-task-{{ $task->id }}"
                                   {{ $task->share ? 'checked' : '' }}
                                   wire:click="updateTaskShare({{ $task->id }}, $event.target.checked)"
                                   class="rounded border-gray-600 text-blue-600"
                                   aria-label="Share task {{ $task->title }} in public view">
                            <span class="text-sm text-gray-400">Share?</span>
                        </label>
                        
                        <button wire:click.stop="openTaskModal({{ $task->id }})" 
                                class="p-1 text-gray-400 hover:text-gray-300 focus:text-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 focus:ring-offset-gray-800 rounded"
                                title="Edit task"
                                aria-label="Edit task: {{ $task->title }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </button>
                        
                        <button wire:click.stop="deleteTask({{ $task->id }})" 
                                wire:confirm="Are you sure you want to delete this task?"
                                class="p-1 text-red-400 hover:text-red-300 focus:text-red-300 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 focus:ring-offset-gray-800 rounded"
                                title="Delete task"
                                aria-label="Delete task: {{ $task->title }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Vertical Notes Tab -->
    <button wire:click="toggleTaskNotes({{ $task->id }})"
            class="absolute top-0 right-0 mt-0 mr-0 bg-gray-300 text-gray-800 px-5 py-2 rounded-bl-lg shadow-lg font-semibold text-xs tracking-widest uppercase hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-400 z-10"
            style="">
        Toggle Notes
    </button>

    <!-- Expandable Notes Section -->
    @if($expandedTaskId === $task->id)
        <div class="border-t border-gray-600 bg-gray-750 p-4">
            <div class="flex justify-between items-center mb-3">
                <h5 class="text-sm font-medium text-gray-300">Task Notes</h5>
                <button wire:click="addNoteToTask({{ $task->id }})" 
                        class="px-3 py-1 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-gray-800 transition-colors">
                    Add Note
                </button>
            </div>
            
            @if($task->notes->count() > 0)
                <div class="space-y-3">
                    @foreach($task->notes as $note)
                        <x-note :note="$note" />
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 italic text-sm">No notes yet for this task.</p>
            @endif
        </div>
    @endif
</div> 