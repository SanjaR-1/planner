<?php
namespace App\Http\Controllers;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Services\ProjectService;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
class ProjectController extends Controller
{
    public function __construct(
        protected ProjectService $projectService
    ) {}
    public function index(Request $request): JsonResponse
    {
        $projects = $this->projectService->paginate(
            (int) $request->get('per_page', 10)
        );
        return response()->json([
            'success' => true,
            'message' => 'Projects list',
            'data' => $projects
        ]);
    }
    public function store(StoreProjectRequest $request): JsonResponse
    {
        $project = $this->projectService->store(
            $request->validated(),
            $request->user()
        );
        return response()->json([
            'success' => true,
            'message' => 'Project created successfully',
            'data' => $project
        ], 201);
    }
    public function show(Project $project): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $project->load([
                'creator:id,name',
                'developers:id,name'
            ]),
        ]);
    }
    public function update(UpdateProjectRequest $request, Project $project): JsonResponse
    {
        $project = $this->projectService->update(
            $project,
            $request->validated()
        );
        return response()->json([
            'success' => true,
            'message' => 'Project updated successfully',
            'data' => $project
        ]);
    }
    public function destroy(Project $project): JsonResponse
    {
        $this->projectService->delete($project);

        return response()->json([
            'success' => true,
            'message' => 'Project deleted successfully'
        ]);
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
        return response()->json([
            'success' => true,
            'message' => 'Users attached to project successfully',
            'data' => $project
        ]);
    }
    public function detachUser(Project $project, User $user): JsonResponse
    {
        $project = $this->projectService->detachUser(
            $project,
            $user->id
        );
        return response()->json([
            'success' => true,
            'message' => 'User detached from project successfully',
            'data' => $project
        ]);
    }
}
