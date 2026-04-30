<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Services\RoleService;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Http\Requests\AttachPermissionsRequest;

class RoleController extends Controller
{
    public function __construct(
        protected RoleService $roleService
    ) {}

    public function index(Request $request): JsonResponse
    {
        $roles = $this->roleService->paginate(
            (int) $request->get('per_page', 10)
        );
        return response()->json([
            'success' => true,
            'message' => 'Roles list',
            'data' => $roles,
        ]);
    }
    public function store(StoreRoleRequest $request): JsonResponse
    {
        $role = $this->roleService->store($request->validated());
        return response()->json([
            'success' => true,
            'message' => 'Role created successfully',
            'data' => $role,
        ], 201);
    }
    public function show(Role $role): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $role->load('permissions'),
        ]);
    }
    public function update(UpdateRoleRequest $request, Role $role): JsonResponse
    {
        $role = $this->roleService->update($role, $request->validated());
        return response()->json([
            'success' => true,
            'message' => 'Role updated successfully',
            'data' => $role,
        ]);
    }
    public function destroy(Role $role): JsonResponse
    {
        $this->roleService->delete($role);
        return response()->json([
            'success' => true,
            'message' => 'Role deleted successfully',
        ]);
    }
    public function attachPermissions(AttachPermissionsRequest $request, Role $role): JsonResponse
    {
        $role = $this->roleService->attachPermissions(
            $role,
            $request->validated()['permission_ids']
        );
        return response()->json([
            'success' => true,
            'message' => 'Permissions attached successfully',
            'data' => $role,
        ]);
    }
    public function syncPermissions(AttachPermissionsRequest $request, Role $role): JsonResponse
    {
        $role = $this->roleService->syncPermissions(
            $role,
            $request->validated()['permission_ids']
        );
        return response()->json([
            'success' => true,
            'message' => 'Permissions synced successfully',
            'data' => $role,
        ]);
    }
    public function detachPermission(Role $role, Permission $permission): JsonResponse
    {
        $role = $this->roleService->detachPermission($role, $permission);
        return response()->json([
            'success' => true,
            'message' => 'Permission detached successfully',
            'data' => $role,
        ]);
    }
}
