<?php

namespace App\Livewire\Projects;

use App\Models\Project;
use App\Models\Note;
use Livewire\Component;
use Livewire\WithFileUploads;

class Show extends Component
{
    use WithFileUploads;

    public Project $project;
    public string $activeTab = 'tasks';
    
    // Note management
    public bool $showNoteModal = false;
    public string $noteContent = '';
    public string $noteableType = '';
    public int $noteableId = 0;

    // Task management
    public bool $showTaskModal = false;
    public bool $editingTask = false;
    public int $editingTaskId = 0;
    public string $taskTitle = '';
    public string $taskDescription = '';
    public string $taskDueDate = '';
    public bool $taskShare = false;

    // Material management
    public bool $showMaterialModal = false;
    public bool $editingMaterial = false;
    public int $editingMaterialId = 0;
    public string $materialName = '';
    public string $materialDescription = '';
    public string $materialAmount = '';
    public string $materialSource = '';
    public int $materialEstCost = 0;
    public int $materialActualCost = 0;
    public bool $materialAcquired = false;
    public bool $materialShare = false;

    // Material cost display properties (USD format)
    public string $materialEstCostDisplay = '';
    public string $materialActualCostDisplay = '';

    // Reference Images management
    public bool $showImageModal = false;
    public bool $showImageUploadModal = false;
    public string $selectedImageUrl = '';
    public $imageFile;
    public bool $imageShare = false;

    // Project editing
    public bool $showProjectEditModal = false;
    public string $projectName = '';
    public string $projectSeries = '';
    public string $projectVersion = '';
    public string $projectDescription = '';

    // Expandable notes sections
    public int $expandedTaskId = 0;
    public int $expandedMaterialId = 0;

    // Task Progress Images management
    public bool $showTaskImageModal = false;
    public int $taskImageTaskId = 0;
    public $taskImageFile;
    
    // Task image viewing
    public bool $showTaskImageViewModal = false;
    public string $taskImageViewUrl = '';
    public string $taskImageViewTitle = '';
    
    // Main project image management
    public bool $showMainImageModal = false;
    public $mainImageFile;

    public function mount(Project $project)
    {
        // Ensure user can only view their own projects
        if ($project->user_id !== auth()->id()) {
            abort(403);
        }

        $this->project = $project->load([
            'tasks.media',
            'materials',
            'notes',
            'media'
        ]);
    }

    public function setTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function toggleProjectShare()
    {
        $this->project->update(['share' => !$this->project->share]);
        $this->project->refresh();
    }

    public function toggleShare()
    {
        $this->project->update(['share' => !$this->project->share]);
        $this->project->refresh();
    }

    public function openNoteModal($type = 'project', $id = null)
    {
        $this->noteableType = $type;
        $this->noteableId = $id ?? $this->project->id;
        $this->noteContent = '';
        $this->showNoteModal = true;
    }

    public function closeNoteModal()
    {
        $this->showNoteModal = false;
        $this->noteContent = '';
        $this->noteableType = '';
        $this->noteableId = 0;
    }

    public function saveNote()
    {
        $this->validate([
            'noteContent' => 'required|string|max:1000',
        ]);

        $note = new Note([
            'content' => $this->noteContent,
            'user_id' => auth()->id(),
        ]);

        // Associate note with the appropriate model
        switch ($this->noteableType) {
            case 'project':
                $this->project->notes()->save($note);
                break;
            case 'task':
                $task = $this->project->tasks()->findOrFail($this->noteableId);
                $task->notes()->save($note);
                break;
            case 'material':
                $material = $this->project->materials()->findOrFail($this->noteableId);
                $material->notes()->save($note);
                break;
        }

        // Refresh the project data
        $this->project->refresh();
        $this->project->load(['tasks.notes', 'materials.notes', 'notes']);

        $this->closeNoteModal();
    }

    public function render()
    {
        return view('livewire.projects.show');
    }

    // Task Management Methods
    public function openTaskModal($taskId = null)
    {
        if ($taskId) {
            $task = $this->project->tasks()->findOrFail($taskId);
            $this->editingTask = true;
            $this->editingTaskId = $task->id;
            $this->taskTitle = $task->title;
            $this->taskDescription = $task->description ?? '';
            $this->taskDueDate = $task->due_date ? $task->due_date->format('Y-m-d') : '';
            $this->taskShare = $task->share;
        } else {
            $this->editingTask = false;
            $this->editingTaskId = 0;
            $this->taskTitle = '';
            $this->taskDescription = '';
            $this->taskDueDate = '';
            $this->taskShare = false;
        }
        $this->showTaskModal = true;
    }

    public function closeTaskModal()
    {
        $this->showTaskModal = false;
        $this->editingTask = false;
        $this->editingTaskId = 0;
        $this->taskTitle = '';
        $this->taskDescription = '';
        $this->taskDueDate = '';
        $this->taskShare = false;
    }

    public function saveTask()
    {
        $this->validate([
            'taskTitle' => 'required|string|max:255',
            'taskDescription' => 'nullable|string|max:1000',
            'taskDueDate' => 'nullable|date',
        ]);

        $taskData = [
            'title' => $this->taskTitle,
            'description' => $this->taskDescription,
            'share' => $this->taskShare,
        ];

        if ($this->taskDueDate) {
            $taskData['due_date'] = $this->taskDueDate;
        }

        if ($this->editingTask) {
            $task = $this->project->tasks()->findOrFail($this->editingTaskId);
            $task->update($taskData);
        } else {
            $this->project->tasks()->create($taskData);
        }

        $this->project->refresh();
        $this->project->load(['tasks.media', 'tasks.notes']);
        $this->closeTaskModal();
    }

    public function toggleTaskCompletion($taskId)
    {
        $task = $this->project->tasks()->findOrFail($taskId);
        
        if ($task->completion_date) {
            $task->update(['completion_date' => null]);
        } else {
            $task->update(['completion_date' => now()]);
        }

        $this->project->refresh();
        $this->project->load(['tasks.media', 'tasks.notes']);
    }

    public function updateTaskShare($taskId, $share)
    {
        $task = $this->project->tasks()->findOrFail($taskId);
        $task->update(['share' => $share]);
    }

    public function updateTaskDueDate($taskId, $dueDate)
    {
        $task = $this->project->tasks()->findOrFail($taskId);
        $task->update(['due_date' => $dueDate ?: null]);
    }

    public function deleteTask($taskId)
    {
        $task = $this->project->tasks()->findOrFail($taskId);
        $task->delete();
        
        $this->project->refresh();
        $this->project->load(['tasks.media', 'tasks.notes']);
    }

    // Material Management Methods
    public function openMaterialModal($materialId = null)
    {
        if ($materialId) {
            $material = $this->project->materials()->findOrFail($materialId);
            $this->editingMaterial = true;
            $this->editingMaterialId = $material->id;
            $this->materialName = $material->name;
            $this->materialDescription = $material->description ?? '';
            $this->materialAmount = $material->amount ?? '';
            $this->materialSource = $material->source ?? '';
            $this->materialEstCost = $material->est_cost ?? 0;
            $this->materialActualCost = $material->actual_cost ?? 0;
            $this->materialAcquired = $material->acquired;
            $this->materialShare = $material->share;
            
            // Convert cents to USD display format
            $this->materialEstCostDisplay = $this->materialEstCost ? number_format($this->materialEstCost / 100, 2) : '';
            $this->materialActualCostDisplay = $this->materialActualCost ? number_format($this->materialActualCost / 100, 2) : '';
        } else {
            $this->editingMaterial = false;
            $this->editingMaterialId = 0;
            $this->materialName = '';
            $this->materialDescription = '';
            $this->materialAmount = '';
            $this->materialSource = '';
            $this->materialEstCost = 0;
            $this->materialActualCost = 0;
            $this->materialAcquired = false;
            $this->materialShare = false;
            $this->materialEstCostDisplay = '';
            $this->materialActualCostDisplay = '';
        }
        $this->showMaterialModal = true;
    }

    public function closeMaterialModal()
    {
        $this->showMaterialModal = false;
        $this->editingMaterial = false;
        $this->editingMaterialId = 0;
        $this->materialName = '';
        $this->materialDescription = '';
        $this->materialAmount = '';
        $this->materialSource = '';
        $this->materialEstCost = 0;
        $this->materialActualCost = 0;
        $this->materialAcquired = false;
        $this->materialShare = false;
        $this->materialEstCostDisplay = '';
        $this->materialActualCostDisplay = '';
    }

    public function saveMaterial()
    {
        $this->validate([
            'materialName' => 'required|string|max:255',
            'materialDescription' => 'nullable|string|max:1000',
            'materialAmount' => 'nullable|string|max:255',
            'materialSource' => 'nullable|string|max:255',
            'materialEstCostDisplay' => 'nullable|string|regex:/^\d*\.?\d{0,2}$/',
            'materialActualCostDisplay' => 'nullable|string|regex:/^\d*\.?\d{0,2}$/',
        ]);

        // Convert USD display format to cents
        $estCostCents = $this->materialEstCostDisplay ? (int) round((float) $this->materialEstCostDisplay * 100) : null;
        $actualCostCents = $this->materialActualCostDisplay ? (int) round((float) $this->materialActualCostDisplay * 100) : null;

        $materialData = [
            'name' => $this->materialName,
            'description' => $this->materialDescription,
            'amount' => $this->materialAmount,
            'source' => $this->materialSource,
            'est_cost' => $estCostCents,
            'actual_cost' => $actualCostCents,
            'acquired' => $this->materialAcquired,
            'share' => $this->materialShare,
        ];

        if ($this->editingMaterial) {
            $material = $this->project->materials()->findOrFail($this->editingMaterialId);
            $material->update($materialData);
        } else {
            $this->project->materials()->create($materialData);
        }

        $this->project->refresh();
        $this->project->load(['materials.notes']);
        $this->closeMaterialModal();
    }

    public function updateMaterialAcquired($materialId, $acquired)
    {
        $material = $this->project->materials()->findOrFail($materialId);
        $material->update(['acquired' => $acquired]);
    }

    public function updateMaterialShare($materialId, $share)
    {
        $material = $this->project->materials()->findOrFail($materialId);
        $material->update(['share' => $share]);
    }

    public function deleteMaterial($materialId)
    {
        $material = $this->project->materials()->findOrFail($materialId);
        $material->delete();
        
        $this->project->refresh();
        $this->project->load(['materials.notes']);
    }

    // Reference Images Management Methods
    public function openImageModal($imageUrl)
    {
        $this->selectedImageUrl = $imageUrl;
        $this->showImageModal = true;
    }

    public function closeImageModal()
    {
        $this->showImageModal = false;
        $this->selectedImageUrl = '';
    }

    public function openImageUploadModal()
    {
        $this->showImageUploadModal = true;
        $this->imageFile = null;
        $this->imageShare = false;
    }

    public function closeImageUploadModal()
    {
        $this->showImageUploadModal = false;
        $this->imageFile = null;
        $this->imageShare = false;
    }

    public function updatedImageFile()
    {
        $this->validate([
            'imageFile' => 'required|image|max:10240', // 10MB max
        ]);
    }

    public function uploadImage()
    {
        $this->validate([
            'imageFile' => 'required|image|max:10240', // 10MB max
        ]);

        $media = $this->project->addMedia($this->imageFile)
            ->toMediaCollection('references');

        // Set custom properties for sharing if needed
        if ($this->imageShare) {
            $media->setCustomProperty('share', true);
            $media->save();
        }

        $this->project->refresh();
        $this->project->load(['media']);
        $this->closeImageUploadModal();
    }

    public function deleteImage($mediaId)
    {
        $media = $this->project->getMedia('references')->find($mediaId);
        if ($media) {
            $media->delete();
        }

        $this->project->refresh();
        $this->project->load(['media']);
    }

    public function toggleImageShare($mediaId, $share)
    {
        $media = $this->project->getMedia('references')->find($mediaId);
        if ($media) {
            $media->setCustomProperty('share', $share);
            $media->save();
        }
    }

    // Project Editing Methods
    public function openProjectEditModal()
    {
        $this->projectName = $this->project->name;
        $this->projectSeries = $this->project->series ?? '';
        $this->projectVersion = $this->project->version ?? '';
        $this->projectDescription = $this->project->description ?? '';
        $this->showProjectEditModal = true;
    }

    public function closeProjectEditModal()
    {
        $this->showProjectEditModal = false;
        $this->projectName = '';
        $this->projectSeries = '';
        $this->projectVersion = '';
        $this->projectDescription = '';
    }

    public function saveProject()
    {
        $this->validate([
            'projectName' => 'required|string|max:255',
            'projectSeries' => 'nullable|string|max:255',
            'projectVersion' => 'nullable|string|max:255',
            'projectDescription' => 'nullable|string|max:10000',
        ]);

        $this->project->update([
            'name' => $this->projectName,
            'series' => $this->projectSeries ?: null,
            'version' => $this->projectVersion ?: null,
            'description' => $this->projectDescription ?: null,
        ]);

        $this->project->refresh();
        $this->closeProjectEditModal();
    }

    // Expandable Notes Methods
    public function toggleTaskNotes($taskId)
    {
        if ($this->expandedTaskId === $taskId) {
            $this->expandedTaskId = 0;
        } else {
            $this->expandedTaskId = $taskId;
            $this->expandedMaterialId = 0; // Close material notes if open
        }
    }

    public function toggleMaterialNotes($materialId)
    {
        if ($this->expandedMaterialId === $materialId) {
            $this->expandedMaterialId = 0;
        } else {
            $this->expandedMaterialId = $materialId;
            $this->expandedTaskId = 0; // Close task notes if open
        }
    }

    public function addNoteToTask($taskId)
    {
        $this->openNoteModal('task', $taskId);
    }

    public function addNoteToMaterial($materialId)
    {
        $this->openNoteModal('material', $materialId);
    }

    // Task Progress Images Methods
    public function openTaskImageModal($taskId)
    {
        $this->taskImageTaskId = $taskId;
        $this->taskImageFile = null;
        $this->showTaskImageModal = true;
    }

    public function closeTaskImageModal()
    {
        $this->showTaskImageModal = false;
        $this->taskImageTaskId = 0;
        $this->taskImageFile = null;
    }

    public function updatedTaskImageFile()
    {
        $this->validate([
            'taskImageFile' => 'required|image|max:10240', // 10MB max
        ]);
    }

    public function uploadTaskImage()
    {
        $this->validate([
            'taskImageFile' => 'required|image|max:10240', // 10MB max
        ]);

        $task = $this->project->tasks()->findOrFail($this->taskImageTaskId);
        
        // Remove existing progress image if any
        $existingMedia = $task->getMedia('progress_image')->first();
        if ($existingMedia) {
            $existingMedia->delete();
        }

        // Add new progress image
        $task->addMedia($this->taskImageFile)
            ->toMediaCollection('progress_image');

        $this->project->refresh();
        $this->project->load(['tasks.media']);
        $this->closeTaskImageModal();
    }

    public function deleteTaskImage($taskId)
    {
        $task = $this->project->tasks()->findOrFail($taskId);
        $media = $task->getMedia('progress_image')->first();
        if ($media) {
            $media->delete();
        }

        $this->project->refresh();
        $this->project->load(['tasks.media']);
    }

    // Task image viewing
    public function openTaskImageViewModal($url, $title)
    {
        $this->taskImageViewUrl = $url;
        $this->taskImageViewTitle = $title;
        $this->showTaskImageViewModal = true;
    }

    public function closeTaskImageViewModal()
    {
        $this->showTaskImageViewModal = false;
        $this->taskImageViewUrl = '';
        $this->taskImageViewTitle = '';
    }
    
    // Main project image methods
    public function openMainImageModal()
    {
        $this->showMainImageModal = true;
        $this->mainImageFile = null;
    }
    
    public function closeMainImageModal()
    {
        $this->showMainImageModal = false;
        $this->mainImageFile = null;
    }
    
    public function updatedMainImageFile()
    {
        $this->validate([
            'mainImageFile' => 'required|image|max:10240', // 10MB max
        ]);
    }
    
    public function uploadMainImage()
    {
        $this->validate([
            'mainImageFile' => 'required|image|max:10240', // 10MB max
        ]);

        // Remove existing main image if any
        $existingMedia = $this->project->getMedia('main')->first();
        if ($existingMedia) {
            $existingMedia->delete();
        }

        // Add new main image
        $this->project->addMedia($this->mainImageFile)
            ->toMediaCollection('main');

        $this->project->refresh();
        $this->project->load(['media']);
        $this->closeMainImageModal();
    }
    
    public function deleteMainImage()
    {
        $media = $this->project->getMedia('main')->first();
        if ($media) {
            $media->delete();
        }

        $this->project->refresh();
        $this->project->load(['media']);
    }
    
    public function copyShareUrl()
    {
        $shareUrl = route('projects.share', $this->project);
        $this->dispatch('copyToClipboard', text: $shareUrl);
    }
} 