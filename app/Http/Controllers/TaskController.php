<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Services\TaskService;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Traits\ApiResponseTrait;

class TaskController extends Controller
{
    use ApiResponseTrait;

    public function __construct(
        protected TaskService $taskService
    ) {}

    public function list(Project $project, Request $request): JsonResponse
    {
        $tasks = $this->taskService->paginate($request);

        return $this->paginatedResponse($tasks, 'Tasks list');
    }

    public function create(StoreTaskRequest $request, Project $project): JsonResponse
    {
        $task = $this->taskService->store(
            $request->validated(),
            $request->user(),
            $project
        );

        return $this->success(
            $task,
            'Task created successfully',
            201
        );
    }

    public function show(Project $project, Task $task): JsonResponse
    {
        abort_if($task->project_id !== $project->id, 404);

        $task = $task->load([
            'project:id,name',
            'creator:id,name',
            'developer:id,name',
            'status:id,name',
            'priority:id,name',
        ]);

        return $this->success(
            $task,
            'Task found'
        );
    }

    public function update(UpdateTaskRequest $request, Project $project, Task $task): JsonResponse
    {
        abort_if($task->project_id !== $project->id, 404);

        $task = $this->taskService->update(
            $task,
            $request->validated()
        );

        return $this->success(
            $task,
            'Task updated successfully'
        );
    }

    public function delete(Project $project, Task $task): JsonResponse
    {
        abort_if($task->project_id !== $project->id, 404);

        $this->taskService->delete($task);

        return $this->success(
            null,
            'Task deleted successfully'
        );
    }
}
