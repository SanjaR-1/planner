<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Services\RoleService;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Traits\ApiResponseTrait;

class RoleController extends Controller
{
    use ApiResponseTrait;

    public function __construct(
        protected RoleService $roleService
    ) {}

    public function list(Request $request): JsonResponse
    {
        $roles = $this->roleService->paginate($request);

        return $this->paginatedResponse($roles, 'List roles');
    }

    public function create(StoreRoleRequest $request): JsonResponse
    {
        $role = $this->roleService->store(
            $request->validated()
        );

        return $this->success(
            $role,
            'Role created successfully',
            201
        );
    }

    public function show(Role $role): JsonResponse
    {
        return $this->success(
            $role->load('permissions'),
            'Role found'
        );
    }

    public function update(UpdateRoleRequest $request, Role $role): JsonResponse
    {
        $role = $this->roleService->update(
            $role,
            $request->validated()
        );

        return $this->success(
            $role,
            'Role updated successfully'
        );
    }

    public function delete(Role $role): JsonResponse
    {
        $this->roleService->delete($role);

        return $this->success(
            null,
            'Role deleted successfully'
        );
    }
}
