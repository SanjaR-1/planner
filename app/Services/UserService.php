<?php
namespace App\Services;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
class UserService
{
    public function paginate($request){
        $search = $request->search;
        $users = User::query()
            ->when($search, function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })
            ->when($request->role_id, fn ($query) => $query
                ->where('role_id', $request->role_id)
            )
            ->when($request->sort === 'created_at_asc', fn ($q) => $q->orderBy('created_at'))
            ->when($request->sort === 'created_at_desc', fn ($q) => $q->orderByDesc('created_at'))
            ->with('role:id,name')->latest()->paginate((int) $request->get('per_page', 10));
        return $users;
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
