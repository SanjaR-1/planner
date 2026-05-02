<?php
namespace App\Http\Controllers;
use App\Models\TaskStatus;
use Illuminate\Http\JsonResponse;
use App\Services\TaskStatusService;
use App\Http\Requests\StoreTaskStatusRequest;
use App\Http\Requests\UpdateTaskStatusRequest;
class TaskStatusController extends Controller
{
    public function __construct(
        protected TaskStatusService $taskStatusService
    ) {}
    public function list(): JsonResponse
    {
        $statuses = $this->taskStatusService->list();
        return response()->json([
            'success' => true,
            'message' => 'Task statuses list',
            'data' => $statuses,
        ]);
    }
    public function create(StoreTaskStatusRequest $request): JsonResponse
    {
        $status = $this->taskStatusService->store($request->validated()
        );
        return response()->json([
            'success' => true,
            'message' => 'Task status created successfully',
            'data' => $status,
        ], 201);
    }
    public function update(UpdateTaskStatusRequest $request, TaskStatus $status): JsonResponse
    {
        $status = $this->taskStatusService->update(
            $status,
            $request->validated()
        );
        return response()->json([
            'success' => true,
            'message' => 'Task status updated successfully',
            'data' => $status,
        ]);
    }
    public function delete(TaskStatus $status): JsonResponse
    {
        $this->taskStatusService->delete($status);
        return response()->json([
            'success' => true,
            'message' => 'Task status deleted successfully',
        ]);
    }
}
