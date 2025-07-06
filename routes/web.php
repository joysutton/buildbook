<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Livewire\Projects\Index;
use App\Livewire\Projects\Show;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');

    // Project routes
    Volt::route('projects', 'projects.index')->name('projects.index');
    Volt::route('projects/create', 'projects.create')->name('projects.create');
    Volt::route('projects/{project}/edit', 'projects.edit')->name('projects.edit');

    // Task routes
    Volt::route('tasks', 'tasks.index')->name('tasks.index');
    Volt::route('tasks/{task}', 'tasks.show')->name('tasks.show');
    Volt::route('tasks/{task}/edit', 'tasks.edit')->name('tasks.edit');

    // Material routes
    Volt::route('materials', 'materials.index')->name('materials.index');
    Volt::route('materials/{material}', 'materials.show')->name('materials.show');
    Volt::route('materials/{material}/edit', 'materials.edit')->name('materials.edit');

    Route::get('/projects', Index::class)->name('projects.index');
    Route::get('/projects/{project}', Show::class)->name('projects.show');
});

// Public route for shared projects
Route::get('/projects/{project}/share', function (App\Models\Project $project) {
    if (!$project->share) {
        abort(404);
    }
    return view('projects.share', compact('project'));
})->name('projects.share');

// PDF generation route
Route::get('/projects/{project}/pdf', [App\Http\Controllers\PdfController::class, 'downloadProjectPdf'])
    ->middleware(['auth'])
    ->name('projects.pdf');

require __DIR__.'/auth.php';
