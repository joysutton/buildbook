@props(['show' => false, 'uploadType' => '', 'wireClose' => 'closeImageUploadModal', 'wireUpload' => 'uploadImage'])

<x-modal :show="$show" title="Upload Image" :wire-close="$wireClose">
    <div class="space-y-4">
        <div>
            <label for="imageFile" class="block text-sm font-medium text-gray-300 mb-2">Select Image</label>
            <input 
                wire:model="imageFile"
                type="file" 
                id="imageFile"
                accept="image/*"
                class="w-full bg-gray-700 border border-gray-600 rounded-md px-3 py-2 text-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-700">
            @error('imageFile') 
                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
        
        @if($imageFile)
            <div class="bg-gray-700 rounded-lg p-4">
                <p class="text-sm text-gray-300">Selected file: {{ $imageFile->getClientOriginalName() }}</p>
                <p class="text-xs text-gray-400 mt-1">Size: {{ number_format($imageFile->getSize() / 1024, 2) }} KB</p>
            </div>
        @endif
        
        <div class="text-sm text-gray-400">
            <p>Supported formats: JPG, PNG, GIF, WebP</p>
            <p>Maximum file size: 10MB</p>
        </div>
    </div>
    
    <x-slot name="footer">
        <button 
            wire:click="{{ $wireUpload }}"
            wire:loading.attr="disabled"
            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
        >
            <span wire:loading.remove>Upload Image</span>
            <span wire:loading>Uploading...</span>
        </button>
    </x-slot>
</x-modal> 