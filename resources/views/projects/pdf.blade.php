<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $project->name }} - BuildBook Project</title>
    
    <style>
        /* Reset and base styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Times New Roman', serif;
            line-height: 1.4;
            color: #000;
            background: white;
            font-size: 12pt;
        }
        
        /* Page layout for 8.5 x 11 inch */
        .page {
            width: 8.5in;
            height: 11in;
            margin: 0 auto;
            padding: 0.5in;
            background: white;
            position: relative;
        }
        
        /* Cover page */
        .cover-page {
            text-align: center;
            padding-top: 2in;
        }
        
        .cover-image {
            width: 6in;
            height: 4.5in;
            object-fit: cover;
            margin: 0 auto 0.5in;
            display: block;
        }
        
        .cover-title {
            font-size: 24pt;
            font-weight: bold;
            margin-bottom: 0.25in;
            color: #000;
        }
        
        .cover-meta {
            font-size: 14pt;
            margin-bottom: 0.25in;
            color: #333;
        }
        
        .cover-creator {
            font-size: 12pt;
            margin-bottom: 0.5in;
            color: #333;
        }
        
        .cover-date {
            font-size: 12pt;
            color: #333;
        }
        
        /* Section headers */
        .section-header {
            font-size: 16pt;
            font-weight: bold;
            margin-bottom: 0.25in;
            color: #000;
            text-align: center;
        }
        
        /* Reference images section */
        .reference-images {
            margin-bottom: 0.5in;
        }
        
        .image-container {
            display: flex;
            flex-wrap: wrap;
            gap: 0.25in;
            margin-bottom: 0.25in;
        }
        
        .reference-image-wrapper {
            flex: 0 0 auto;
            max-width: 3.5in;
            max-height: 4.5in;
            page-break-inside: avoid;
        }
        
        .reference-image {
            width: auto;
            height: auto;
            max-width: 100%;
            max-height: 4.5in;
            object-fit: contain;
        }
        
        /* Materials section */
        .materials-list {
            margin-bottom: 0.5in;
        }
        
        .material-item {
            margin-bottom: 0.125in;
            padding-left: 0.25in;
        }
        
        .material-name {
            font-weight: bold;
        }
        
        .material-details {
            font-style: italic;
            color: #333;
        }
        
        /* Tasks section */
        .tasks-section {
            margin-bottom: 0.5in;
        }
        
        .task-item {
            margin-bottom: 0.25in;
            page-break-inside: avoid;
        }
        
        .task-row {
            display: flex;
            align-items: flex-start;
            gap: 0.25in;
        }
        
        .task-image {
            width: 3.75in;
            height: 2.8in;
            object-fit: cover;
            flex-shrink: 0;
        }
        
        .task-content {
            flex: 1;
        }
        
        .task-title {
            font-weight: bold;
            margin-bottom: 0.125in;
        }
        
        .task-description {
            color: #333;
        }
        
        .task-status {
            margin-top: 0.125in;
            font-size: 10pt;
            color: #666;
        }
        
        /* Page breaks */
        .page-break {
            page-break-before: always;
        }
        

        
        /* Print optimizations */
        @media print {
            .page {
                page-break-after: always;
            }
            
            .page:last-child {
                page-break-after: auto;
            }
        }
    </style>
</head>
<body>
    <!-- Cover Page -->
    <div class="page">
        <div class="cover-page">
            @if($project->getFirstMediaUrl('main'))
                <img src="{{ $project->getFirstMediaUrl('main') }}" 
                     alt="Main project image" 
                     class="cover-image">
            @endif
            
            <h1 class="cover-title">{{ $project->name }}</h1>
            
            @if($project->series || $project->version)
                <div class="cover-meta">
                    @if($project->series)Series: {{ $project->series }}@endif
                    @if($project->series && $project->version) - @endif
                    @if($project->version)Version: {{ $project->version }}@endif
                </div>
            @endif
            
            <div class="cover-creator">by {{ $project->user->handle ?: $project->user->username }}</div>
            <div class="cover-date">{{ now()->format('F j, Y') }}</div>
        </div>
    </div>

    <!-- Reference Images Page -->
    @php
        $sharedImages = $project->getMedia('references')->filter(function($media) {
            return $media->getCustomProperty('share', false);
        });
    @endphp
    @if($sharedImages->count() > 0)
        <div class="page page-break">
            <h2 class="section-header">Reference Images</h2>
            
            <div class="reference-images">
                <div class="image-container">
                    @foreach($sharedImages as $media)
                        <div class="reference-image-wrapper">
                            <img src="{{ $media->getUrl() }}" 
                                 alt="Reference image" 
                                 class="reference-image">
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- Materials Page -->
    @php
        $sharedMaterials = $project->materials->filter(function($material) {
            return $material->share;
        });
    @endphp
    @if($sharedMaterials->count() > 0)
        <div class="page page-break">
            <h2 class="section-header">Materials for the Project</h2>
            
            <div class="materials-list">
                @foreach($sharedMaterials as $material)
                    <div class="material-item">
                        <span class="material-name">{{ $material->name }}</span>
                        @if($material->amount || $material->description)
                            <span class="material-details">
                                @if($material->amount) - {{ $material->amount }}@endif
                                @if($material->description) - {{ $material->description }}@endif
                            </span>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Tasks Page -->
    @php
        $sharedTasks = $project->tasks->filter(function($task) {
            return $task->share;
        });
    @endphp
    @if($sharedTasks->count() > 0)
        <div class="page page-break">
            <h2 class="section-header">Tasks</h2>
            
            <div class="tasks-section">
                @foreach($sharedTasks as $index => $task)
                    <div class="task-item">
                        @if($task->getFirstMediaUrl('progress_image'))
                            <div class="task-row">
                                @if($index % 2 == 0)
                                    <!-- Image on left, content on right -->
                                    <img src="{{ $task->getFirstMediaUrl('progress_image') }}" 
                                         alt="Task progress" 
                                         class="task-image">
                                    <div class="task-content">
                                        <div class="task-title">{{ $task->title }}</div>
                                        @if($task->description)
                                            <div class="task-description">{{ $task->description }}</div>
                                        @endif
                                    </div>
                                @else
                                    <!-- Content on left, image on right -->
                                    <div class="task-content">
                                        <div class="task-title">{{ $task->title }}</div>
                                        @if($task->description)
                                            <div class="task-description">{{ $task->description }}</div>
                                        @endif
                                    </div>
                                    <img src="{{ $task->getFirstMediaUrl('progress_image') }}" 
                                         alt="Task progress" 
                                         class="task-image">
                                @endif
                            </div>
                        @else
                            <!-- No image - task spans full width -->
                            <div class="task-content">
                                <div class="task-title">{{ $task->title }}</div>
                                @if($task->description)
                                    <div class="task-description">{{ $task->description }}</div>
                                @endif
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</body>
</html> 