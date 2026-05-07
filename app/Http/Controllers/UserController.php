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

        return $this->paginatedResponse($users, 'Users list');
    }

    public function create(UserRegisterRequest $request): JsonResponse
    {
        $user = $this->userService->store(
            $request->validated()
        );

        return $this->success(
            $user,
            'User created successfully',
            201
        );
    }

    public function show(User $user): JsonResponse
    {
        return $this->success(
            $user->load('role'),
            'User found'
        );
    }

    public function update(UpdateUserRequest $request, User $user): JsonResponse
    {
        $updated_user = $this->userService->update(
            $user,
            $request->validated()
        );

        return $this->success(
            $updated_user,
            'User updated successfully'
        );
    }

    public function delete(User $user): JsonResponse
    {
        $this->userService->delete($user);

        return $this->success(
            null,
            'User deleted successfully'
        );
    }
}
