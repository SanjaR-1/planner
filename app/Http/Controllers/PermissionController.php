<?php
namespace App\Http\Controllers;
use App\Models\Permission;
use Illuminate\Http\JsonResponse;
use App\Services\PermissionService;
use App\Http\Requests\StorePermissionRequest;
use App\Http\Requests\UpdatePermissionRequest;
use App\Traits\ApiResponseTrait;
use App\Http\Requests\ListRequest;
class PermissionController extends Controller
{
    use ApiResponseTrait;
    public function __construct(
        protected PermissionService $permissionService
    ) {}
    public function list(ListRequest $request): JsonResponse
    {
        $permissions = $this->permissionService->paginate(
            $request
        );
        return $this->paginatedResponse($permissions);
    }
    public function show(Permission $permission): JsonResponse
    {
        $data = $this->permissionService->show($permission);
        return $this->success($data);
    }
    public function create(StorePermissionRequest $request): JsonResponse
    {
        $permission = $this->permissionService->store(
            $request->validated()
        );
        return $this->success(
            $permission,
        );
    }
    public function update(UpdatePermissionRequest $request, Permission $permission): JsonResponse
    {
        $permission = $this->permissionService->update(
            $permission,
            $request->validated()
        );
        return $this->success(
            $permission
        );
    }
    public function delete(Permission $permission): JsonResponse
    {
        $this->permissionService->delete($permission);
        return $this->success(null);
    }
}
