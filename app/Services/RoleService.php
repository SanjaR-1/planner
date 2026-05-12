<?php
namespace App\Services;
use App\Exceptions\BusinessException;
use App\Models\Role;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class RoleService
{
    public function paginate(Request $request): LengthAwarePaginator
    {
        $allowedSorts = ['asc', 'desc'];
        $sort = in_array($request->sort, $allowedSorts) ? $request->sort : null;
        $roles = Role::query()
            ->Where('is_active', '=', 1)
            ->when(filled($request->search), function ($query) use ($request) {
                $query->where('name', 'like', '%' .$request->search. '%');
            })
            ->when(filled($request->permission_id),function ($q) use ($request){
                $q->whereHas('permissions',function ($query) use ($request){
                    $query->where('permissions.id',$request->permission_id);
                });
            })
            ->when($sort, function ($q) use ($sort) {
                if($sort == 'asc'){
                    $q->orderBy('roles.created_at');
                }else{
                    $q->orderByDesc('roles.created_at');
                }
            },function ($q){
                $q->latest();
            })
            ->with(['permissions:id,display_name'])
            ->paginate((int) $request->get('per_page', 10));
        return $roles;
    }
    public function store(array $data): Role
    {
        $permissionIds = $data['permission_ids'] ?? [];
        unset($data['permission_ids']);
        $role = Role::create($data);
        if(!empty($permissionIds)){
               $role->permissions()->sync($permissionIds);
        }
        return $role->load('permissions');
    }
    public function show(Role $role): Role
    {
        if($role->is_active  == 0){
            throw new BusinessException();
        }
        return $role->load('permissions');
    }
    public function update(Role $role, array $data): Role
    {
        if($role->is_active == '0'){
            throw new BusinessException();
        }
        $permissionIds = $data['permission_ids'] ?? [];
        unset($data['permission_ids']);
        $role->update($data);
        $role->permissions()->sync($permissionIds);
        return $role->refresh()->load('permissions');
    }
    public function delete(Role $role): void
    {
        if($role->is_active == '0'){
            throw new BusinessException();
        }
        $role->is_active = '0';
        $role->save();
    }
}
