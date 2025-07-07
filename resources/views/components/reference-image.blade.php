@props(['media', 'showActions' => true])

<div class="relative group flex-shrink-0">
    <div class="relative overflow-hidden rounded-lg bg-gray-800 border border-gray-600 max-w-xs">
        <img src="{{ $media->getUrl() }}" 
             alt="Reference image" 
             class="w-auto h-auto max-w-full max-h-64 object-contain cursor-pointer"
             wire:click="openImageModal('{{ $media->getUrl() }}')">
        
        @if($showActions)
            <!-- Delete button overlay -->
            <button wire:click="deleteImage({{ $media->id }})"
                    wire:confirm="Are you sure you want to delete this image?"
                    class="absolute top-2 right-2 p-1 bg-red-600 text-white rounded opacity-0 group-hover:opacity-100 focus:opacity-100 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 focus:ring-offset-gray-800 transition-opacity"
                    title="Delete image"
                    aria-label="Delete reference image">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
            </button>
        @endif
    </div>
    
    @if($showActions)
        <!-- Share checkbox -->
        <div class="mt-2">
            <label class="flex items-center space-x-2">
                <input type="checkbox" 
                       id="share-image-{{ $media->id }}"
                       {{ $media->getCustomProperty('share') ? 'checked' : '' }}
                       wire:click="toggleImageShare({{ $media->id }}, $event.target.checked)"
                       class="rounded border-gray-600 text-blue-600"
                       aria-label="Share reference image in public view">
                <span class="text-sm text-gray-400">Share image</span>
            </label>
        </div>
    @endif
</div> 