@props(['note', 'showActions' => true])

<div class="border-l-4 border-blue-500 pl-3 group">
    <div class="flex items-start justify-between">
        <div class="flex-1">
            <p class="text-gray-300 text-sm">{{ $note->content }}</p>
            <p class="text-gray-500 text-xs mt-1">{{ $note->created_at->format('M j, Y g:i A') }}</p>
        </div>
        @if($showActions)
            <div class="flex items-center space-x-1 opacity-0 group-hover:opacity-100 focus-within:opacity-100 transition-opacity ml-2">
                <button wire:click="editNote({{ $note->id }})" 
                        class="p-1 text-gray-400 hover:text-blue-400 focus:text-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-gray-900 transition-colors rounded"
                        title="Edit note"
                        aria-label="Edit note created on {{ $note->created_at->format('M j, Y g:i A') }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                </button>
                <button wire:click="deleteNote({{ $note->id }})" 
                        wire:confirm="Are you sure you want to delete this note?"
                        class="p-1 text-gray-400 hover:text-red-400 focus:text-red-400 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 focus:ring-offset-gray-900 transition-colors rounded"
                        title="Delete note"
                        aria-label="Delete note created on {{ $note->created_at->format('M j, Y g:i A') }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                </button>
            </div>
        @endif
    </div>
</div> 