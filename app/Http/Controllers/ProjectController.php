<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
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

    public function list(Request $request): JsonResponse
    {
        $projects = $this->projectService->paginate($request);

        return $this->paginatedResponse($projects, 'Project list');
    }

    public function create(StoreProjectRequest $request): JsonResponse
    {
        $project = $this->projectService->store(
            $request->validated(),
            $request->user()
        );

        return $this->success(
            $project,
            'Project created successfully',
            201
        );
    }

    public function show(Project $project): JsonResponse
    {
        return $this->success(
            $project->load([
                'creator:id,name',
                'developers:id,name'
            ]),
            'Project found'
        );
    }

    public function update(UpdateProjectRequest $request, Project $project): JsonResponse
    {
        $project = $this->projectService->update(
            $project,
            $request->validated()
        );

        return $this->success(
            $project,
            'Project updated successfully'
        );
    }

    public function delete(Project $project): JsonResponse
    {
        $this->projectService->delete($project);

        return $this->success(
            null,
            'Project deleted successfully'
        );
    }

    public function attachUsers(Request $request, Project $project): JsonResponse
    {
        $data = $request->validate([
            'user_id' => 'required|array',
            'user_id.*' => 'exists:users,id'
        ]);

        $project = $this->projectService->attachUsers(
            $project,
            $data['user_id']
        );

        return $this->success(
            $project,
            'Users attached to project successfully'
        );
    }

    public function detachUser(Project $project, User $user): JsonResponse
    {
        $project = $this->projectService->detachUser(
            $project,
            $user->id
        );

        return $this->success(
            $project,
            'User detached from project successfully'
        );
    }
}
