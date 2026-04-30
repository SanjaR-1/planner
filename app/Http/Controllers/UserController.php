<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Requests\UpdateUserRequest;

class UserController extends Controller
{
    public function __construct(protected UserService $userService) {}
    public function index(Request $request): JsonResponse
    {
        $users = $this->userService->paginate(
            (int) $request->get('per_page', 10)
        );
        return response()->json([
            'success' => true,
            'message' => 'Users list',
            'data' => $users,
        ]);
    }
    public function store(UserRegisterRequest $request): JsonResponse
    {
        $user = $this->userService->store($request->validated());
        return response()->json([
            'success' => true,
            'message' => 'User created successfully',
            'data' => $user,
        ], 201);
    }
    public function show(User $user): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $user->load('role'),
        ]);
    }
    public function update(UpdateUserRequest $request, User $user): JsonResponse
    {
        $updated_user = $this->userService->update($user, $request->validated());
        return response()->json([
            'success' => true,
            'message' => 'User updated successfully',
            'data' => $updated_user,
        ]);
    }
    public function destroy(User $user): JsonResponse
    {
        $this->userService->delete($user);

        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully',
        ]);
    }
}
