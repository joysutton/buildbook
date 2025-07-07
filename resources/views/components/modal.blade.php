@props(['show' => false, 'title' => '', 'wireClose' => 'closeModal'])

@if($show)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-gray-800 rounded-lg p-6 w-full max-w-md mx-4">
            @if($title)
                <h3 class="text-lg font-semibold text-gray-100 mb-4">{{ $title }}</h3>
            @endif
            
            {{ $slot }}
            
            <div class="flex justify-end space-x-3 mt-6">
                <button 
                    wire:click="{{ $wireClose }}"
                    class="px-4 py-2 text-gray-300 hover:text-gray-100 transition-colors"
                >
                    Cancel
                </button>
                {{ $footer ?? '' }}
            </div>
        </div>
    </div>
@endif 