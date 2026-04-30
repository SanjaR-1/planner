<?php
namespace App\Services;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
class AuthService
{
    public function loginUser(array $data):array{
        $user = User::where('phone', $data['phone'])->first();
        if(!$user || !Hash::check($data['password'], $user->password)){
            abort(401,'login or password is invalid');
        }
        $token = $user->createToken('api',['*'],now()->addMinutes(30))->plainTextToken;
        return [
            'token' => $token,
            'user' => $user,
        ];
    }
    public function logoutUser(User $user):array
    {
        $user->currentAccessToken()->delete();
        return [
            'message' => 'Logged out successfully'
        ];
    }
}
