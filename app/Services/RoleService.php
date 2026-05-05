<?php
namespace App\Services;
use App\Models\Role;

class RoleService
{
    public function paginate($request)
    {
        $permissionId = $request->permission_id;
        $search = $request->search;
        $roles = Role::query()
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', '%' . $search . '%');
            })
            ->when($permissionId,function ($q) use ($permissionId){
                $q->whereHas('permissions',function ($query) use ($permissionId){
                    $query->where('permissions.id',$permissionId);
                });
            })
            ->when($request->sort === 'created_at_asc', fn ($q) => $q->orderBy('created_at'))
            ->when($request->sort === 'created_at_desc', fn ($q) => $q->orderByDesc('created_at'))
            ->with(['permissions:id,display_name'])
            ->latest()
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
    public function update(Role $role, array $data): Role
    {
        $permissionIds = $data['permission_ids'] ?? [];
        unset($data['permission_ids']);
        $role->update($data);
        $role->permissions()->sync($permissionIds);
        return $role->refresh()->load('permissions');
    }
    public function delete(Role $role): bool
    {
        return $role->delete();
    }
}
