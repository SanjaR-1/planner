<?php

namespace App\Services;

use App\Models\Permission;

class PermissionService
{
    public function paginate(int $perPage = 10)
    {
        return Permission::latest()
            ->paginate($perPage);
    }

    public function store(array $data): Permission
    {
        return Permission::create($data);
    }

    public function update(Permission $permission, array $data): Permission
    {
        $permission->update($data);

        return $permission->refresh();
    }

    public function delete(Permission $permission): bool
    {
        return $permission->delete();
    }
}
