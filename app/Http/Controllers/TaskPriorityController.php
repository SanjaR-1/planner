<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskPriorityRequest;
use App\Http\Requests\UpdateTaskPriorityRequest;
use App\Models\TaskPriority;
use Illuminate\Http\JsonResponse;
use App\Services\TaskPriorityService;
use App\Traits\ApiResponseTrait;

class TaskPriorityController extends Controller
{
    use ApiResponseTrait;
    public function __construct(protected TaskPriorityService $taskPriorityService){}
    public function list(): JsonResponse
    {
        $priorities = $this->taskPriorityService->list();
        return $this->success(
            $priorities
        );
    }
    public function show(TaskPriority $taskPriority): JsonResponse{
        $priority = $this->taskPriorityService->show($taskPriority);
        return $this->success($priority);
    }
    public function create(StoreTaskPriorityRequest $request): JsonResponse
    {
        $priority = $this->taskPriorityService->store(
            $request->validated()
        );
        return $this->success(
            $priority
        );
    }
    public function update(
        UpdateTaskPriorityRequest $request,
        TaskPriority $priority
    ): JsonResponse {
        $priority = $this->taskPriorityService->update(
            $priority,
            $request->validated()
        );
        return $this->success(
            $priority
        );
    }
    public function delete(TaskPriority $taskPriority): JsonResponse
    {
        $this->taskPriorityService->delete($taskPriority);
        return $this->success(
            null
        );
    }
}
