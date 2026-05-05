<?php

namespace App\Services;

use App\Models\Permission;

class PermissionService
{
    public function paginate($request)
    {
        $search = $request->search;
        return Permission::query()
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', '%' . $search . '%');
            })
            ->latest()
            ->paginate((int) $request->get('per_page', 10));
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
