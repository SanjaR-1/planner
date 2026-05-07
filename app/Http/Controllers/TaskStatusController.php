<?php

namespace App\Http\Controllers;

use App\Models\TaskStatus;
use Illuminate\Http\JsonResponse;
use App\Services\TaskStatusService;
use App\Http\Requests\StoreTaskStatusRequest;
use App\Http\Requests\UpdateTaskStatusRequest;
use App\Traits\ApiResponseTrait;

class TaskStatusController extends Controller
{
    use ApiResponseTrait;

    public function __construct(
        protected TaskStatusService $taskStatusService
    ) {}

    public function list(): JsonResponse
    {
        $statuses = $this->taskStatusService->list();

        return $this->success($statuses, 'Task Status List');
    }

    public function create(StoreTaskStatusRequest $request): JsonResponse
    {
        $status = $this->taskStatusService->store(
            $request->validated()
        );

        return $this->success(
            $status,
            'Task status created successfully',
            201
        );
    }

    public function update(UpdateTaskStatusRequest $request, TaskStatus $status): JsonResponse
    {
        $status = $this->taskStatusService->update(
            $status,
            $request->validated()
        );

        return $this->success(
            $status,
            'Task status updated successfully'
        );
    }

    public function delete(TaskStatus $status): JsonResponse
    {
        $this->taskStatusService->delete($status);

        return $this->success(
            null,
            'Task status deleted successfully'
        );
    }
}
