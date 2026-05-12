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
        return $this->success($statuses);
    }
    public function show(TaskStatus $taskStatus): JsonResponse{
        $taskStatus = $this->taskStatusService->show($taskStatus);
        return $this->success($taskStatus);
    }
    public function create(StoreTaskStatusRequest $request): JsonResponse
    {
        $status = $this->taskStatusService->store(
            $request->validated()
        );
        return $this->success(
            $status
        );
    }
    public function update(UpdateTaskStatusRequest $request, TaskStatus $status): JsonResponse
    {
        $status = $this->taskStatusService->update(
            $status,
            $request->validated()
        );
        return $this->success(
            $status
        );
    }
    public function delete(TaskStatus $status): JsonResponse
    {
        $this->taskStatusService->delete($status);
        return $this->success(
            null
        );
    }
}
