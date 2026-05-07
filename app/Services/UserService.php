<?php
namespace App\Services;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class UserService
{
    public function paginate(Request $request): LengthAwarePaginator{
        $search = $request->search;
        $allowedSorts = ['created_at_asc', 'created_at_desc'];
        $sort = in_array($request->sort, $allowedSorts) ? $request->sort : null;
        $users = User::query()
            ->when(filled($search), function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })
            ->when(filled($request->role_id), fn ($query) => $query
                ->where('role_id', $request->role_id)
            )
            ->when($sort==='created_at_asc', fn ($q) => $q->orderBy('created_at'))
            ->when($sort === 'created_at_desc', fn ($q) => $q->orderByDesc('created_at'))
            ->with('role:id,name')->paginate((int) min($request->get('per_page', 10),70) );
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
