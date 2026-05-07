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
            $priorities,
            'Task Priority List'
        );
    }

    public function create(StoreTaskPriorityRequest $request): JsonResponse
    {
        $priority = $this->taskPriorityService->store(
            $request->validated()
        );

        return $this->success(
            $priority,
            'Task priority created',
            201
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
            $priority,
            'Task priority updated'
        );
    }

    public function delete(TaskPriority $taskPriority): JsonResponse
    {
        $this->taskPriorityService->delete($taskPriority);

        return $this->success(
            null,
            'Task priority deleted'
        );
    }
}
