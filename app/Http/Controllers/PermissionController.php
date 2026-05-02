<?php
namespace App\Http\Controllers;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Services\PermissionService;
use App\Http\Requests\StorePermissionRequest;
use App\Http\Requests\UpdatePermissionRequest;
class PermissionController extends Controller
{
    public function __construct(
        protected PermissionService $permissionService
    ) {}
    public function list(Request $request): JsonResponse
    {
        $permissions = $this->permissionService->paginate(
            (int) $request->get('per_page', 10)
        );
        return response()->json([
            'success' => true,
            'message' => 'Permissions list',
            'data' => $permissions,
        ]);
    }
    public function create(StorePermissionRequest $request): JsonResponse
    {
        $permission = $this->permissionService->store($request->validated());
        return response()->json([
            'success' => true,
            'message' => 'Permission created successfully',
            'data' => $permission,
        ], 201);
    }
    public function show(Permission $permission): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $permission,
        ]);
    }
    public function update(UpdatePermissionRequest $request, Permission $permission): JsonResponse
    {
        $permission = $this->permissionService->update(
            $permission,
            $request->validated()
        );
        return response()->json([
            'success' => true,
            'message' => 'Permission updated successfully',
            'data' => $permission,
        ]);
    }
    public function delete(Permission $permission): JsonResponse
    {
        $this->permissionService->delete($permission);
        return response()->json([
            'success' => true,
            'message' => 'Permission deleted successfully',
        ]);
    }
}
