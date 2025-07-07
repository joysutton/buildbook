@props(['show' => false, 'editingMaterialId' => 0, 'materialName' => '', 'materialDescription' => '', 'materialAmount' => '', 'materialSource' => '', 'materialEstCost' => '', 'materialActualCost' => '', 'wireClose' => 'closeMaterialModal', 'wireSave' => 'saveMaterial', 'wireUpdate' => 'updateMaterial'])

<x-modal :show="$show" :title="$editingMaterialId ? 'Edit Material' : 'Add Material'" :wire-close="$wireClose">
    <div class="space-y-4">
        <div>
            <label for="materialName" class="block text-sm font-medium text-gray-300 mb-2">Material Name</label>
            <input 
                wire:model="materialName"
                type="text" 
                id="materialName"
                class="w-full bg-gray-700 border border-gray-600 rounded-md px-3 py-2 text-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                placeholder="Enter material name">
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
                placeholder="Enter material description"></textarea>
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
                    placeholder="e.g., 2 yards">
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
                    placeholder="e.g., JoAnn Fabrics">
                @error('materialSource') 
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>
        
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="materialEstCost" class="block text-sm font-medium text-gray-300 mb-2">Estimated Cost ($)</label>
                <input 
                    wire:model="materialEstCost"
                    type="number" 
                    step="0.01"
                    id="materialEstCost"
                    class="w-full bg-gray-700 border border-gray-600 rounded-md px-3 py-2 text-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="0.00">
                @error('materialEstCost') 
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="materialActualCost" class="block text-sm font-medium text-gray-300 mb-2">Actual Cost ($)</label>
                <input 
                    wire:model="materialActualCost"
                    type="number" 
                    step="0.01"
                    id="materialActualCost"
                    class="w-full bg-gray-700 border border-gray-600 rounded-md px-3 py-2 text-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="0.00">
                @error('materialActualCost') 
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>
    
    <x-slot name="footer">
        <button 
            wire:click="{{ $editingMaterialId ? $wireUpdate : $wireSave }}"
            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors"
        >
            {{ $editingMaterialId ? 'Update Material' : 'Add Material' }}
        </button>
    </x-slot>
</x-modal> 