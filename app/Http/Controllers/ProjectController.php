<?php
namespace App\Http\Controllers;
use App\Http\Requests\AttachUsersRequest;
use App\Models\Project;
use App\Models\User;
use App\Http\Requests\ListRequest;
use Illuminate\Http\JsonResponse;
use App\Services\ProjectService;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Traits\ApiResponseTrait;
class ProjectController extends Controller
{
    use ApiResponseTrait;
    public function __construct(
        protected ProjectService $projectService
    ) {}
    public function list(ListRequest $request): JsonResponse
    {
        $projects = $this->projectService->paginate($request);
        return $this->paginatedResponse($projects);
    }
    public function show(Project $project): JsonResponse
    {
        $data = $this->projectService->show($project);
        return $this->success($data);
    }
    public function create(StoreProjectRequest $request): JsonResponse
    {
        $project = $this->projectService->store(
            $request->validated(),
            $request->user()
        );
        return $this->success(
            $project
        );
    }
    public function update(UpdateProjectRequest $request, Project $project): JsonResponse
    {
        $project = $this->projectService->update(
            $project,
            $request->validated()
        );
        return $this->success(
            $project
        );
    }
    public function delete(Project $project): JsonResponse
    {
        $this->projectService->delete($project);
        return $this->success(null);
    }
    public function attachUsers(AttachUsersRequest $request, Project $project): JsonResponse
    {
        $data = $this->projectService
            ->attachUsers(
                $project,
                $request->validated()['user_ids']
            );
        return $this->success(
            $data
        );
    }
    public function detachUser(Project $project, User $user): JsonResponse
    {
        $project = $this->projectService->detachUser(
            $project,
            $user->id
        );
        return $this->success(
            $project
        );
    }
}
