<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $project->name }} - Sewing Project</title>
    <meta name="description" content="{{ $project->description ?: 'A sewing project by ' . $project->user->name }}">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        gray: {
                            750: '#374151',
                        }
                    }
                }
            }
        }
    </script>
    
    <!-- Custom styles -->
    <style>
        .gradient-text {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Header Area -->
    <div class="relative h-64 md:h-80 lg:h-96">
        <!-- Background gradient -->
        <div class="absolute inset-0 bg-gradient-to-r from-gray-800 to-gray-700"></div>
        
        <!-- Main image (50% width) -->
        @if($project->getFirstMediaUrl('main'))
            <div class="absolute right-0 top-0 bottom-0 w-1/2 overflow-hidden">
                <img src="{{ $project->getFirstMediaUrl('main') }}" 
                     alt="Main project image"
                     class="w-full h-full object-cover">
                <!-- Fade overlay from left to right -->
                <div class="absolute inset-0 bg-gradient-to-r from-gray-900 via-gray-900/50 to-transparent"></div>
            </div>
        @endif
        
        <!-- Project info overlay -->
        <div class="relative h-full flex items-center p-6 md:p-8 lg:p-12">
            <div class="text-white">
                <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold mb-2">{{ $project->name }}</h1>
                @if($project->series)
                    <p class="text-xl md:text-2xl text-gray-300 mb-1">Series: {{ $project->series }}</p>
                @endif
                @if($project->version)
                    <p class="text-xl md:text-2xl text-gray-300 mb-4">Version: {{ $project->version }}</p>
                @endif
                <p class="text-lg text-gray-300">by {{ $project->user->handle ?: $project->user->name }}</p>
            </div>
        </div>
    </div>

    <!-- Info Area -->
    <div class="p-6 md:p-8 lg:p-12 bg-white">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Description -->
            <div>
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Description</h2>
                <div class="bg-gray-50 rounded-lg p-4">
                    @if($project->description)
                        <p class="text-gray-700 whitespace-pre-wrap">{{ $project->description }}</p>
                    @else
                        <p class="text-gray-500 italic">No description provided.</p>
                    @endif
                </div>
            </div>

            <!-- Creator Bio -->
            <div>
                <h2 class="text-xl font-semibold text-gray-800 mb-4">About the Creator</h2>
                <div class="bg-gray-50 rounded-lg p-4">
                    @if($project->user->bio)
                        <p class="text-gray-700">{{ $project->user->bio }}</p>
                    @else
                        <p class="text-gray-500 italic">No bio available.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Section Tabs -->
    <div class="bg-white border-b border-gray-200">
        <div class="px-6 md:px-8 lg:px-12">
            <div class="flex space-x-1">
                <button 
                    onclick="showTab('tasks')"
                    class="px-4 py-2 text-sm font-medium rounded-t-lg transition-colors bg-blue-600 text-white"
                    id="tasks-tab"
                >
                    Tasks
                </button>
                <button 
                    onclick="showTab('materials')"
                    class="px-4 py-2 text-sm font-medium rounded-t-lg transition-colors bg-gray-200 text-gray-700 hover:bg-gray-300"
                    id="materials-tab"
                >
                    Materials
                </button>
                <button 
                    onclick="showTab('references')"
                    class="px-4 py-2 text-sm font-medium rounded-t-lg transition-colors bg-gray-200 text-gray-700 hover:bg-gray-300"
                    id="references-tab"
                >
                    Reference Images
                </button>
            </div>
        </div>
    </div>

    <!-- Content Area -->
    <div class="bg-gray-50 min-h-96">
        <!-- Tasks Tab -->
        <div id="tasks-content" class="tab-content">
            <div class="p-6 md:p-8 lg:p-12">
                <h3 class="text-xl font-semibold text-gray-800 mb-6">Project Tasks</h3>
                
                @php
                    $sharedTasks = $project->tasks->where('share', true);
                @endphp
                
                @if($sharedTasks->count() > 0)
                    <div class="space-y-4">
                        @foreach($sharedTasks as $task)
                            <div class="bg-white rounded-lg border border-gray-200 overflow-hidden shadow-sm">
                                <div class="p-4">
                                    <div class="flex items-start space-x-4">
                                        <!-- Task image thumbnail -->
                                        <div class="flex-shrink-0">
                                            @if($task->getFirstMediaUrl('progress_image'))
                                                <img src="{{ $task->getFirstMediaUrl('progress_image') }}" 
                                                     alt="Task progress" 
                                                     class="w-16 h-16 object-cover rounded">
                                            @else
                                                <div class="w-16 h-16 bg-gray-200 rounded flex items-center justify-center">
                                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Task content -->
                                        <div class="flex-1">
                                            <h4 class="text-lg font-medium text-gray-800 mb-2">{{ $task->title }}</h4>
                                            @if($task->description)
                                                <p class="text-gray-600 mb-3">{{ $task->description }}</p>
                                            @endif
                                            
                                            <div class="flex items-center space-x-4">
                                                @if($task->due_date)
                                                    <div class="text-sm text-gray-500">
                                                        Due: {{ $task->due_date->format('M j, Y') }}
                                                    </div>
                                                @endif
                                                
                                                @if($task->completion_date)
                                                    <div class="text-sm text-green-600 font-medium">
                                                        ✓ Completed: {{ $task->completion_date->format('M j, Y') }}
                                                    </div>
                                                @else
                                                    <div class="text-sm text-gray-500">
                                                        Status: In Progress
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Task Notes -->
                                    @php
                                        $sharedTaskNotes = $task->notes->where('share', true);
                                    @endphp
                                    @if($sharedTaskNotes->count() > 0)
                                        <div class="mt-4 pt-4 border-t border-gray-200">
                                            <h5 class="text-sm font-medium text-gray-700 mb-2">Notes:</h5>
                                            <div class="space-y-2">
                                                @foreach($sharedTaskNotes as $note)
                                                    <div class="bg-gray-50 rounded p-3">
                                                        <p class="text-sm text-gray-700">{{ $note->content }}</p>
                                                        <p class="text-xs text-gray-500 mt-1">{{ $note->created_at->format('M j, Y g:i A') }}</p>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <p class="text-gray-500">No shared tasks available.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Materials Tab -->
        <div id="materials-content" class="tab-content hidden">
            <div class="p-6 md:p-8 lg:p-12">
                <h3 class="text-xl font-semibold text-gray-800 mb-6">Materials</h3>
                
                @php
                    $sharedMaterials = $project->materials->where('share', true);
                @endphp
                
                @if($sharedMaterials->count() > 0)
                    <div class="bg-white rounded-lg overflow-hidden shadow-sm">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-700">Title</th>
                                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-700">Description</th>
                                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-700">Amount</th>
                                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-700">Source</th>
                                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-700">Cost</th>
                                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-700">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($sharedMaterials as $material)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 text-sm text-gray-800">{{ $material->name }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-600">{{ $material->description ?: '-' }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-600">{{ $material->amount ?: '-' }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-600">{{ $material->source ?: '-' }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-600">
                                            @if($material->actual_cost)
                                                ${{ number_format($material->actual_cost / 100, 2) }}
                                            @elseif($material->est_cost)
                                                ${{ number_format($material->est_cost / 100, 2) }} (est.)
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-sm">
                                            @if($material->acquired)
                                                <span class="text-green-600 font-medium">✓ Acquired</span>
                                            @else
                                                <span class="text-gray-500">Not acquired</span>
                                            @endif
                                        </td>
                                    </tr>
                                    
                                    <!-- Material Notes -->
                                    @php
                                        $sharedMaterialNotes = $material->notes->where('share', true);
                                    @endphp
                                    @if($sharedMaterialNotes->count() > 0)
                                        <tr>
                                            <td colspan="6" class="px-4 py-3 bg-gray-50">
                                                <h6 class="text-sm font-medium text-gray-700 mb-2">Notes:</h6>
                                                <div class="space-y-2">
                                                    @foreach($sharedMaterialNotes as $note)
                                                        <div class="bg-white rounded p-2 border border-gray-200">
                                                            <p class="text-sm text-gray-700">{{ $note->content }}</p>
                                                            <p class="text-xs text-gray-500 mt-1">{{ $note->created_at->format('M j, Y g:i A') }}</p>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-12">
                        <p class="text-gray-500">No shared materials available.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Reference Images Tab -->
        <div id="references-content" class="tab-content hidden">
            <div class="p-6 md:p-8 lg:p-12">
                <h3 class="text-xl font-semibold text-gray-800 mb-6">Reference Images</h3>
                
                @php
                    $sharedImages = $project->getMedia('references')->filter(function($media) {
                        return $media->getCustomProperty('share', false);
                    });
                @endphp
                
                @if($sharedImages->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($sharedImages as $media)
                            <div class="bg-white rounded-lg overflow-hidden shadow-sm border border-gray-200">
                                <img src="{{ $media->getUrl() }}" 
                                     alt="Reference image" 
                                     class="w-full h-48 object-cover">
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <p class="text-gray-500">No shared reference images available.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="bg-white border-t border-gray-200 p-6 md:p-8 lg:p-12">
        <div class="text-center">
            <p class="text-gray-500 text-sm">
                Project created with BuildBook - Sewing Project Management
            </p>
        </div>
    </div>

    <!-- JavaScript for tab switching -->
    <script>
        function showTab(tabName) {
            // Hide all tab contents
            const tabContents = document.querySelectorAll('.tab-content');
            tabContents.forEach(content => {
                content.classList.add('hidden');
            });
            
            // Remove active state from all tabs
            const tabs = document.querySelectorAll('[id$="-tab"]');
            tabs.forEach(tab => {
                tab.classList.remove('bg-blue-600', 'text-white');
                tab.classList.add('bg-gray-200', 'text-gray-700');
            });
            
            // Show selected tab content
            document.getElementById(tabName + '-content').classList.remove('hidden');
            
            // Add active state to selected tab
            document.getElementById(tabName + '-tab').classList.remove('bg-gray-200', 'text-gray-700');
            document.getElementById(tabName + '-tab').classList.add('bg-blue-600', 'text-white');
        }
    </script>
</body>
</html> 