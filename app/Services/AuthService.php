<?php
namespace App\Services;
use App\Exceptions\BusinessException;
use App\Models\User;
class AuthService
{
    public function loginUser(array $data):array{
        if (!auth()->attempt([
            'phone' => $data['phone'],
            'password' => $data['password']
        ])) {
            throw new BusinessException(null);
        }
        $user = auth()->user();
        $token = $user->createToken('api',['*'],now()->addDay())->plainTextToken;
        return [
            'token' => $token,
            'user' => $user,
        ];
    }
    public function logoutUser(User $user):void
    {
        $user->currentAccessToken()->delete();
    }
}
