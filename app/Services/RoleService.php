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
        return Role::create($data)->load('permissions');
    }

    public function update(Role $role, array $data): Role
    {
        $role->update($data);

        return $role->refresh()->load('permissions');
    }

    public function delete(Role $role): bool
    {
        return $role->delete();
    }

    public function attachPermissions(Role $role, array $permissionIds): Role
    {
        $role->permissions()->syncWithoutDetaching($permissionIds);

        return $role->load('permissions');
    }

    public function syncPermissions(Role $role, array $permissionIds): Role
    {
        $role->permissions()->sync($permissionIds);

        return $role->load('permissions');
    }

    public function detachPermission(Role $role, Permission $permission): Role
    {
        $role->permissions()->detach($permission->id);

        return $role->load('permissions');
    }
}
