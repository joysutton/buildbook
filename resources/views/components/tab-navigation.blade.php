@props(['tabs', 'activeTab', 'wireAction' => 'setTab'])

<div class="bg-gray-800 border-b border-gray-700">
    <div class="px-6 md:px-8 lg:px-12">
        <div class="flex space-x-1">
            @foreach($tabs as $tab)
                <button 
                    wire:click="{{ $wireAction }}('{{ $tab['key'] }}')"
                    class="px-4 py-2 text-sm font-medium rounded-t-lg transition-colors {{ $activeTab === $tab['key'] ? 'bg-gray-700 text-white' : 'bg-gray-900 text-gray-400 hover:text-gray-300 hover:bg-gray-800' }}"
                >
                    {{ $tab['label'] }}
                </button>
            @endforeach
        </div>
    </div>
</div> 