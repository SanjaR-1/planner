<?php
namespace App\Http\Controllers;
use App\Models\Role;
use App\Http\Requests\ListRequest;
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
    public function list(ListRequest $request): JsonResponse
    {
        $roles = $this->roleService->paginate($request);
        return $this->paginatedResponse($roles);
    }
    public function create(StoreRoleRequest $request): JsonResponse
    {
        $role = $this->roleService->store(
            $request->validated()
        );
        return $this->success(
            $role
        );
    }
    public function show(Role $role): JsonResponse
    {
        $data = $this->roleService->show($role);
        return $this->success(
            $data
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
        );
    }
    public function delete(Role $role): JsonResponse
    {
        $this->roleService->delete($role);
        return $this->success(
            null
        );
    }
}
