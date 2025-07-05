<?php

use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\ProfileController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\NoteController;
use App\Http\Controllers\Api\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public routes
Route::post('/register', [RegisterController::class, 'register']);
Route::post('/login', [LoginController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::patch('/profile', [ProfileController::class, 'update']);
    Route::delete('/profile', [ProfileController::class, 'destroy']);
    
    // Logout
    Route::post('/logout', function (Request $request) {
        $request->user()->currentAccessToken()->delete();
        
        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    });

    // Project routes
    Route::apiResource('projects', \App\Http\Controllers\Api\ProjectController::class);
    
    // Task routes
    Route::apiResource('tasks', TaskController::class);
    
    // Material routes
    Route::get('/projects/{project}/materials', [\App\Http\Controllers\Api\MaterialController::class, 'index']);
    Route::post('/projects/{project}/materials', [\App\Http\Controllers\Api\MaterialController::class, 'store']);
    Route::get('/materials/{material}', [\App\Http\Controllers\Api\MaterialController::class, 'show']);
    Route::put('/materials/{material}', [\App\Http\Controllers\Api\MaterialController::class, 'update']);
    Route::delete('/materials/{material}', [\App\Http\Controllers\Api\MaterialController::class, 'destroy']);
    
    // Note routes
    // Project notes
    Route::get('/projects/{project}/notes', [NoteController::class, 'indexForProject']);
    Route::post('/projects/{project}/notes', [NoteController::class, 'storeForProject']);
    
    // Material notes
    Route::get('/materials/{material}/notes', [NoteController::class, 'indexForMaterial']);
    Route::post('/materials/{material}/notes', [NoteController::class, 'storeForMaterial']);
    
    // Task notes
    Route::get('/tasks/{task}/notes', [NoteController::class, 'indexForTask']);
    Route::post('/tasks/{task}/notes', [NoteController::class, 'storeForTask']);
    
    // Individual note operations
    Route::get('/notes/{note}', [NoteController::class, 'show']);
    Route::put('/notes/{note}', [NoteController::class, 'update']);
    Route::delete('/notes/{note}', [NoteController::class, 'destroy']);
}); 