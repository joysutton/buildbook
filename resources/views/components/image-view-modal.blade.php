@props(['show' => false, 'imageUrl' => '', 'imageTitle' => '', 'wireClose' => 'closeImageViewModal'])

@if($show)
    <div class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50">
        <div class="relative max-w-4xl max-h-full mx-4">
            <!-- Close button -->
            <button 
                wire:click="{{ $wireClose }}"
                class="absolute top-4 right-4 z-10 p-2 bg-gray-800 text-white rounded-full hover:bg-gray-700 transition-colors"
            >
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
            
            <!-- Image container -->
            <div class="bg-gray-800 rounded-lg overflow-hidden">
                @if($imageTitle)
                    <div class="px-6 py-4 border-b border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-100">{{ $imageTitle }}</h3>
                    </div>
                @endif
                
                <div class="p-4">
                    <img src="{{ $imageUrl }}" 
                         alt="{{ $imageTitle ?: 'Image' }}" 
                         class="max-w-full max-h-96 object-contain">
                </div>
            </div>
        </div>
    </div>
@endif 