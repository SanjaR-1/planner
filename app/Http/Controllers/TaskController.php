<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Services\TaskService;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;

class TaskController extends Controller
{
    public function __construct(
        protected TaskService $taskService
    ) {}

    public function list(Project $project, Request $request): JsonResponse
    {
        $tasks = $this->taskService->paginate($request);
        return response()->json([
            'success' => true,
            'message' => 'Tasks list',
            'data' => $tasks,
        ]);
    }
    public function create(StoreTaskRequest $request, Project $project): JsonResponse
    {
        $task = $this->taskService->store(
            $request->validated(),
            $request->user(),
            $project
        );
        return response()->json([
            'success' => true,
            'message' => 'Task created successfully',
            'data' => $task,
        ], 201);
    }
    public function show(Project $project, Task $task): JsonResponse
    {
        abort_if($task->project_id !== $project->id, 404);
        return response()->json([
            'success' => true,
            'data' => $task->load([
                'project:id,name',
                'creator:id,name',
                'developer:id,name',
                'status:id,name',
                'priority:id,name',
            ]),
        ]);
    }
    public function update(UpdateTaskRequest $request,Project $project, Task $task): JsonResponse
    {
        abort_if($task->project_id !== $project->id, 404);
        $task = $this->taskService->update(
            $task,
            $request->validated()
        );
        return response()->json([
            'success' => true,
            'message' => 'Task updated successfully',
            'data' => $task,
        ]);
    }
    public function delete(Project $project, Task $task): JsonResponse
    {
        abort_if($task->project_id !== $project->id, 404);
        $this->taskService->delete($task);
        return response()->json([
            'success' => true,
            'message' => 'Task deleted successfully',
        ]);
    }
}
