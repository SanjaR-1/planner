<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskPriorityRequest;
use App\Http\Requests\UpdateTaskPriorityRequest;
use App\Models\TaskPriority;
use Illuminate\Http\JsonResponse;
use App\Services\TaskPriorityService;

class TaskPriorityController extends Controller
{
    public function __construct(protected TaskPriorityService $taskPriorityService){}
    public function list():JsonResponse
    {
        $priorities = $this->taskPriorityService->list();
        return response()->json([
            'success' => true,
            'message' => 'Task priorities list',
            'data' => $priorities
        ]);
    }
    public function create(StoreTaskPriorityRequest $request):JsonResponse{
        $priority = $this->taskPriorityService->store($request->validated());
        return response()->json([
           'success' => true,
           'message' => 'Task priority created',
           'data' => $priority
        ]);
    }
    public function update(UpdateTaskPriorityRequest $request, TaskPriority $priority):JsonResponse{
        $priority = $this->taskPriorityService->update(
            $priority,
            $request->validated()
        );
        return response()->json([
            'success' => true,
            'message' => 'Task priority updated',
            'data' => $priority
        ]);
    }
    public function delete(TaskPriority $taskPriority):JsonResponse{
       $this->taskPriorityService->delete($taskPriority);
        return response()->json([
            'success' => true,
            'message' => 'Task priority deleted'
        ]);
    }
}
