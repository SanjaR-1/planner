<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TaskStatusController;
Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);
    });
});
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('users', UserController::class);
    Route::apiResource('roles', RoleController::class);
    Route::post('/roles/{role}/permissions', [RoleController::class, 'attachPermissions']);
    Route::put('/roles/{role}/permissions', [RoleController::class, 'syncPermissions']);
    Route::delete('/roles/{role}/permissions/{permission}', [RoleController::class, 'detachPermission']);
    Route::apiResource('permissions', PermissionController::class);
    Route::apiResource('projects', ProjectController::class);
    Route::post('/projects/{project}/users', [ProjectController::class, 'attachUsers']);
    Route::delete('/projects/{project}/users/{user}', [ProjectController::class, 'detachUser']);
    Route::get('/projects/{project}/statuses', [TaskStatusController::class, 'index']);
    Route::post('/projects/{project}/statuses', [TaskStatusController::class, 'store']);
    Route::put('/statuses/{status}', [TaskStatusController::class, 'update']);
    Route::delete('/statuses/{status}', [TaskStatusController::class, 'destroy']);
    Route::apiResource('tasks', TaskController::class);
    Route::patch('/tasks/{task}/status', [TaskController::class, 'updateStatus']);
});
