@props(['materials', 'expandedMaterialId'])

<div class="bg-gray-800 rounded-lg overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-900">
            <tr>
                <th class="px-4 py-3 text-left text-sm font-medium text-gray-300">Title</th>
                <th class="px-4 py-3 text-left text-sm font-medium text-gray-300">Description</th>
                <th class="px-4 py-3 text-left text-sm font-medium text-gray-300">Amount</th>
                <th class="px-4 py-3 text-left text-sm font-medium text-gray-300">Source</th>
                <th class="px-4 py-3 text-left text-sm font-medium text-gray-300">Cost</th>
                <th class="px-4 py-3 text-left text-sm font-medium text-gray-300">Acquired</th>
                <th class="px-4 py-3 text-left text-sm font-medium text-gray-300">Share</th>
                <th class="px-4 py-3 text-left text-sm font-medium text-gray-300">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-700">
            @foreach($materials as $material)
                <tr class="hover:bg-gray-750 cursor-pointer" wire:click="toggleMaterialNotes({{ $material->id }})">
                    <td class="px-4 py-4 text-sm text-gray-300">{{ $material->name }}</td>
                    <td class="px-4 py-4 text-sm text-gray-400 max-w-xs">
                        <div class="line-clamp-3">{{ $material->description }}</div>
                    </td>
                    <td class="px-4 py-4 text-sm text-gray-300">{{ $material->amount }}</td>
                    <td class="px-4 py-4 text-sm text-gray-300">{{ $material->source }}</td>
                    <td class="px-4 py-4 text-sm text-gray-300">
                        <div class="space-y-1">
                            @if($material->est_cost)
                                <div>Est: ${{ number_format($material->est_cost / 100, 2) }}</div>
                            @endif
                            @if($material->actual_cost)
                                <div>Actual: ${{ number_format($material->actual_cost / 100, 2) }}</div>
                            @endif
                        </div>
                    </td>
                    <td class="px-4 py-4 text-sm text-gray-300">
                        <input type="checkbox" 
                               id="acquired-{{ $material->id }}"
                               {{ $material->acquired ? 'checked' : '' }}
                               wire:click.stop="updateMaterialAcquired({{ $material->id }}, $event.target.checked)"
                               class="rounded border-gray-600 text-blue-600"
                               aria-label="Mark {{ $material->name }} as acquired">
                    </td>
                    <td class="px-4 py-4 text-sm text-gray-300">
                        <input type="checkbox" 
                               id="share-{{ $material->id }}"
                               {{ $material->share ? 'checked' : '' }}
                               wire:click.stop="updateMaterialShare({{ $material->id }}, $event.target.checked)"
                               class="rounded border-gray-600 text-blue-600"
                               aria-label="Share {{ $material->name }} in public view">
                    </td>
                    <td class="px-4 py-4 text-sm text-gray-300">
                        <div class="flex space-x-2">
                            <button wire:click.stop="openMaterialModal({{ $material->id }})" 
                                    class="p-1 text-gray-400 hover:text-gray-300 focus:text-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 focus:ring-offset-gray-800 rounded"
                                    title="Edit material"
                                    aria-label="Edit material: {{ $material->name }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </button>
                            <button wire:click.stop="deleteMaterial({{ $material->id }})" 
                                    wire:confirm="Are you sure you want to delete this material?"
                                    class="p-1 text-red-400 hover:text-red-300 focus:text-red-300 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 focus:ring-offset-gray-800 rounded"
                                    title="Delete material"
                                    aria-label="Delete material: {{ $material->name }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </div>
                    </td>
                </tr>
                
                <!-- Expandable Notes Row -->
                @if($expandedMaterialId === $material->id)
                    <tr class="bg-gray-750">
                        <td colspan="8" class="px-4 py-4">
                            <div class="flex justify-between items-center mb-3">
                                <h5 class="text-sm font-medium text-gray-300">Material Notes</h5>
                                <button wire:click="addNoteToMaterial({{ $material->id }})" 
                                        class="px-3 py-1 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-gray-800 transition-colors">
                                    Add Note
                                </button>
                            </div>
                            
                            @if($material->notes->count() > 0)
                                <div class="space-y-3">
                                    @foreach($material->notes as $note)
                                        <x-note :note="$note" />
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-500 italic text-sm">No notes yet for this material.</p>
                            @endif
                        </td>
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>
</div> 