<?php
namespace App\Services;
use App\Exceptions\BusinessException;
use App\Models\Permission;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class PermissionService
{
    public function paginate(Request $request):LengthAwarePaginator
    {
        return Permission::query()
            ->where('is_active', 1)
            ->when(filled($request->search), function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search . '%');
            })
            ->latest()
            ->paginate((int) $request->get('per_page', 10));
    }
    public function store(array $data): Permission
    {
        return Permission::create($data);
    }
    public function show(Permission $permission): Permission
    {
        if($permission->is_active == '0'){
            throw new BusinessException();
        }
        return $permission;
    }
    public function update(Permission $permission, array $data): Permission
    {
        if($permission->is_active == '0'){
            throw new BusinessException();
        }
        $permission->update($data);
        return $permission->refresh();
    }
    public function delete(Permission $permission): void
    {
        if($permission->is_active == '0'){
            throw new BusinessException();
        }
        $permission->is_active = '0';
        $permission->save();
    }
}
