<?php
namespace App\Http\Controllers;
use App\Http\Requests\UserLoginRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
class AuthController extends Controller
{
    public function __construct(
        protected AuthService $authService
    ) {}
    public function login(UserLoginRequest $request): JsonResponse
    {
        $result = $this->authService->loginUser($request->validated());
        return response()->json([
            'success' => true,
            'message' => 'Login Successful',
            'data' => $result,
        ]);
    }
    public function logout(): JsonResponse
    {
        $result = $this->authService->logoutUser(request()->user());
        return response()->json([
            'success' => true,
            'message' => $result['message'],
        ]);
    }
    public function me(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Current user',
            'data' => request()->user()->load('role'),
        ]);
    }
}
