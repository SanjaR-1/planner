<?php

namespace App\Http\Controllers;
use App\Http\Requests\UserLoginRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use App\Traits\ApiResponseTrait;
class AuthController extends Controller
{
    use ApiResponseTrait;
    public function __construct(
        protected AuthService $authService
    ) {}
    public function login(UserLoginRequest $request): JsonResponse
    {
        $result = $this->authService->loginUser(
            $request->validated()
        );
        return $this->success($result);
    }
    public function logout():JsonResponse
    {
        $this->authService->logoutUser(request()->user());
        return $this->success(null);
    }
    public function me(): JsonResponse
    {
        return $this->success(request()->user()->load('role'));
    }
}
