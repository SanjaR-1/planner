<?php
namespace Database\Seeders;
use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'role.create',
            'role.update',
            'role.delete',
            'permission.create',
            'permission.update',
            'permission.delete',
            'user.create',
            'user.update',
            'user.delete',
            'user.assign_role',
            'project.create',
            'project.update',
            'project.delete',
            'project.assign_user',
            'task.create',
            'task.update',
            'task.delete',
            'task.change_status',
            'task.comment',
        ];
        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
            'name' => $permission,
            ]);
        }
    }
}
