<?php
namespace App\Http\Controllers;
use App\Exceptions\BusinessException;
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
        return $this->paginatedResponse($tasks);
    }
    public function create(StoreTaskRequest $request, Project $project): JsonResponse
    {
        $task = $this->taskService->store(
            $request->validated(),
            $request->user(),
            $project
        );
        return $this->success($task);
    }
    public function show(Project $project, Task $task): JsonResponse
    {
        $data = $this->taskService->show($project,$task);
        return $this->success($data);
    }
    public function update(UpdateTaskRequest $request, Project $project, Task $task): JsonResponse
    {
        if($task->project_id !== $project->id){
            throw new BusinessException();
        }
        $task = $this->taskService->update(
            $task,
            $request->validated()
        );
        return $this->success(
            $task
        );
    }
    public function delete(Project $project, Task $task): JsonResponse
    {
        if($task->project_id !== $project->id){
            throw new BusinessException();
        }
        $this->taskService->delete($task);
        return $this->success(
            null
        );
    }
}
