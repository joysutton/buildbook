@props(['show' => false, 'editingTaskId' => 0, 'taskTitle' => '', 'taskDescription' => '', 'taskDueDate' => '', 'wireClose' => 'closeTaskModal', 'wireSave' => 'saveTask', 'wireUpdate' => 'updateTask'])

<x-modal :show="$show" :title="$editingTaskId ? 'Edit Task' : 'Add New Task'" :wire-close="$wireClose">
    <div class="space-y-4">
        <div>
            <label for="taskTitle" class="block text-sm font-medium text-gray-300 mb-2">Task Title</label>
            <input 
                wire:model="taskTitle"
                type="text" 
                id="taskTitle"
                class="w-full bg-gray-700 border border-gray-600 rounded-md px-3 py-2 text-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                placeholder="Enter task title">
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
                placeholder="Enter task description"></textarea>
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
                class="w-full bg-gray-700 border border-gray-600 rounded-md px-3 py-2 text-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            @error('taskDueDate') 
                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
    </div>
    
    <x-slot name="footer">
        <button 
            wire:click="{{ $editingTaskId ? $wireUpdate : $wireSave }}"
            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors"
        >
            {{ $editingTaskId ? 'Update Task' : 'Create Task' }}
        </button>
    </x-slot>
</x-modal> 