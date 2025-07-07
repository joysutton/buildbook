@props(['title', 'action' => null])

<div class="flex justify-between items-center mb-4">
    <h2 class="text-xl font-semibold text-gray-100">{{ $title }}</h2>
    @if($action)
        <button wire:click="{{ $action['wire'] }}" class="px-3 py-1 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-gray-900 transition-colors">
            {{ $action['label'] }}
        </button>
    @endif
</div> 