<?php
namespace App\Services;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Http\Requests\ListRequest;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Exceptions\BusinessException;
class UserService
{
    public function paginate(ListRequest $request): LengthAwarePaginator{
        $search = filled($request->search) ? ($request->search) : null;
        $allowedSorts = ['created_at_asc', 'created_at_desc'];
        $sort = in_array($request->sort, $allowedSorts) ? $request->sort : null;
        $users = User::query()
            ->where('is_active', 1)
            ->when($search, function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })
            ->when(filled($request->role_id), fn ($query) => $query
                ->where('role_id', $request->role_id)
            )
            ->when($sort==='created_at_asc', fn ($q) => $q->orderBy('created_at'))
            ->when($sort === 'created_at_desc', fn ($q) => $q->orderByDesc('created_at'))
            ->with('role:id,name')->paginate((int) $request->get('per_page', 10));
        return $users;
    }
    public function showUser(User $user): User
    {
        if($user -> is_active == 0){
            throw new BusinessException('Foydalanuvchi aktiv emas');
        }
        return $user->load('role');
    }
    public function store(array $data): User{
        $data['password'] = Hash::make($data['password']);
        $user = User::create($data);
        return $user->load('role');
    }
    public function update(User $user, array $data): User
    {
        if($user -> is_active == 0){
            throw new BusinessException('Foydalanuvchi aktiv emas');
        }
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }
        $user->update($data);
        return $user->refresh()->load('role:id,name');
    }
    public function delete(User $user): void
    {
        if($user->is_active == '0'){
            throw new BusinessException('Foydalanuvchi o\'chirilgan');
        }
        $user->is_active = '0';
        $user->save();
    }
}
