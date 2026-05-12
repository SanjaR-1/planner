<?php
namespace App\Http\Controllers;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Traits\ApiResponseTrait;
class UserController extends Controller
{
    use ApiResponseTrait;
    public function __construct(protected UserService $userService) {}
    public function list(Request $request): JsonResponse
    {
        $users = $this->userService->paginate($request);
        return $this->paginatedResponse($users);
    }
    public function create(UserRegisterRequest $request): JsonResponse
    {
        $user = $this->userService->store($request->validated());
        return $this->success($user,201);
    }
    public function show(User $user): JsonResponse
    {
        $user = $this->userService->showUser($user);
        return $this->success($user);
    }
    public function update(UpdateUserRequest $request, User $user): JsonResponse
    {
        $updated_user = $this->userService->update($user,$request->validated());
        return $this->success($updated_user);
    }
    public function delete(User $user): JsonResponse
    {
        $this->userService->delete($user);
        return $this->success(null);
    }
}
