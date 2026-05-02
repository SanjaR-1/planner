<?php
namespace App\Services;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
class UserService
{
    public function paginate(int $perPage){
        return User::with('role')->latest()->paginate($perPage);
    }
    public function store(array $data){
        $data['password'] = Hash::make($data['password']);
        $user = User::create($data);
        return $user->load('role');
    }
    public function update(User $user, array $data)
    {
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }
        $user->update($data);
        return $user->refresh()->load('role:id,name');
    }
    public function delete(User $user){
        return $user->delete();
    }
}
