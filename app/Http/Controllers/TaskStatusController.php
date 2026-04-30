<?php
namespace App\Http\Controllers;
use App\Models\Project;
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
    public function index(Project $project): JsonResponse
    {
        $statuses = $this->taskStatusService->listByProject($project);
        return response()->json([
            'success' => true,
            'message' => 'Task statuses list',
            'data' => $statuses,
        ]);
    }
    public function store(StoreTaskStatusRequest $request, Project $project): JsonResponse
    {
        $status = $this->taskStatusService->store(
            $project,
            $request->validated()
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
    public function destroy(TaskStatus $status): JsonResponse
    {
        $this->taskStatusService->delete($status);
        return response()->json([
            'success' => true,
            'message' => 'Task status deleted successfully',
        ]);
    }
}
