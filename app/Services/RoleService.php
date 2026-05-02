<?php

namespace App\Services;

use App\Models\Role;
use App\Models\Permission;

class RoleService
{
    public function paginate(int $perPage = 10)
    {
        return Role::with('permissions')
            ->latest()
            ->paginate($perPage);
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
