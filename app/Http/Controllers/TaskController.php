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

    public function list(Request $request): JsonResponse
    {
        $tasks = $this->taskService->paginate(
            (int) $request->get('per_page', 10)
        );

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
    public function show(Task $task): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $task->load([
                'project:id,name',
                'creator:id,name',
                'developer:id,name',
                'status:id,name',
                'logs',
                'comments',
                'attachments',
            ]),
        ]);
    }
    public function update(UpdateTaskRequest $request, Task $task): JsonResponse
    {
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
    public function delete(Task $task): JsonResponse
    {
        $this->taskService->delete($task);
        return response()->json([
            'success' => true,
            'message' => 'Task deleted successfully',
        ]);
    }
}
