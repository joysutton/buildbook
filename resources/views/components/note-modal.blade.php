@props(['show' => false, 'noteContent' => '', 'editingNoteId' => 0, 'wireClose' => 'closeNoteModal', 'wireSave' => 'saveNote', 'wireUpdate' => 'updateNote'])

<x-modal :show="$show" :title="$editingNoteId ? 'Edit Note' : 'Add Note'" :wire-close="$wireClose">
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
    
    <x-slot name="footer">
        <button 
            wire:click="{{ $editingNoteId ? $wireUpdate : $wireSave }}"
            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors"
        >
            {{ $editingNoteId ? 'Update Note' : 'Save Note' }}
        </button>
    </x-slot>
</x-modal> 