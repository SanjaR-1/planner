<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TaskStatusController;
use App\Http\Controllers\TaskPriorityController;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    Route::get('/users', [UserController::class, 'list'])->middleware('permission:user.view');
    Route::post('/users', [UserController::class, 'create'])->middleware('permission:user.create');
    Route::get('/users/{user}', [UserController::class, 'show'])->middleware('permission:user.view');
    Route::put('/users/{user}', [UserController::class, 'update'])->middleware('permission:user.update');
    Route::delete('/users/{user}', [UserController::class, 'delete'])->middleware('permission:user.delete');

    Route::get('/roles', [RoleController::class, 'list'])->middleware('permission:role.view');
    Route::post('/roles', [RoleController::class, 'create'])->middleware('permission:role.create');
    Route::get('/roles/{role}', [RoleController::class, 'show'])->middleware('permission:role.view');
    Route::put('/roles/{role}', [RoleController::class, 'update'])->middleware('permission:role.update');
    Route::delete('/roles/{role}', [RoleController::class, 'delete'])->middleware('permission:role.delete');

    Route::get('/permissions', [PermissionController::class, 'list'])->middleware('permission:permission.view');
    Route::post('/permissions', [PermissionController::class, 'create'])->middleware('permission:permission.create');
    Route::get('/permissions/{permission}', [PermissionController::class, 'show'])->middleware('permission:permission.view');
    Route::put('/permissions/{permission}', [PermissionController::class, 'update'])->middleware('permission:permission.update');
    Route::delete('/permissions/{permission}', [PermissionController::class, 'delete'])->middleware('permission:permission.delete');


    Route::get('/projects', [ProjectController::class, 'list'])->middleware('permission:project.view');
    Route::post('/projects', [ProjectController::class, 'create'])->middleware('permission:project.create');
    Route::get('/projects/{project}', [ProjectController::class, 'show'])->middleware('permission:project.view');
    Route::put('/projects/{project}', [ProjectController::class, 'update'])->middleware('permission:project.update');
    Route::delete('/projects/{project}', [ProjectController::class, 'delete'])->middleware('permission:project.delete');
    Route::post('/projects/{project}/users', [ProjectController::class, 'attachUsers'])->middleware('permission:project.assign_user');
    Route::delete('/projects/{project}/users/{user}', [ProjectController::class, 'detachUser'])->middleware('permission:project.assign_user');

    Route::get('/statuses', [TaskStatusController::class, 'list'])->middleware('permission:task.view');
    Route::post('/statuses', [TaskStatusController::class, 'create'])->middleware('permission:task.create');
    Route::put('/statuses/{status}', [TaskStatusController::class, 'update'])->middleware('permission:task.update');
    Route::delete('/statuses/{status}', [TaskStatusController::class, 'delete'])->middleware('permission:task.delete');

    Route::get('/priorities', [TaskPriorityController::class, 'list'])->middleware('permission:task.view');
    Route::post('/priorities', [TaskPriorityController::class, 'create'])->middleware('permission:task.create');
    Route::put('/priorities/{priority}', [TaskPriorityController::class, 'update'])->middleware('permission:task.update');
    Route::delete('/priorities/{priority}', [TaskPriorityController::class, 'delete'])->middleware('permission:task.delete');

    Route::get('/projects/{project}/tasks', [TaskController::class, 'list'])->middleware('permission:task.view');
    Route::post('/projects/{project}/tasks', [TaskController::class, 'create'])->middleware('permission:task.create');
    Route::get('/projects/{project}/tasks/{task}', [TaskController::class, 'show'])->middleware('permission:task.view');
    Route::put('/projects/{project}/tasks/{task}', [TaskController::class, 'update'])->middleware('permission:task.update');
    Route::delete('/projects/{project}/tasks/{task}', [TaskController::class, 'delete'])->middleware('permission:task.delete');
});
