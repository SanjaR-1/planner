<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Services\PermissionService;
use App\Http\Requests\StorePermissionRequest;
use App\Http\Requests\UpdatePermissionRequest;
use App\Traits\ApiResponseTrait;

class PermissionController extends Controller
{
    use ApiResponseTrait;

    public function __construct(
        protected PermissionService $permissionService
    ) {}

    public function list(Request $request): JsonResponse
    {
        $permissions = $this->permissionService->paginate(
            $request
        );

        return $this->paginatedResponse(
            $permissions,
            'Permission List'
        );
    }

    public function create(StorePermissionRequest $request): JsonResponse
    {
        $permission = $this->permissionService->store(
            $request->validated()
        );

        return $this->success(
            $permission,
            'Permission Created',
            201
        );
    }

    public function show(Permission $permission): JsonResponse
    {
        return $this->success(
            $permission,
            'Permission found'
        );
    }

    public function update(UpdatePermissionRequest $request, Permission $permission): JsonResponse
    {
        $permission = $this->permissionService->update(
            $permission,
            $request->validated()
        );

        return $this->success(
            $permission,
            'Permission updated successfully'
        );
    }

    public function delete(Permission $permission): JsonResponse
    {
        $this->permissionService->delete($permission);

        return $this->success(
            null,
            'Permission deleted successfully'
        );
    }
}
