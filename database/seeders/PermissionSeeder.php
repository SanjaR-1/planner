<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'role.view' => 'Rollarni ko‘rish',
            'role.create' => 'Role yaratish',
            'role.update' => 'Role yangilash',
            'role.delete' => 'Role o‘chirish',

            'permission.view' => 'Permissionlarni ko‘rish',
            'permission.create' => 'Permission yaratish',
            'permission.update' => 'Permission yangilash',
            'permission.delete' => 'Permission o‘chirish',

            'user.view' => 'Userlarni ko‘rish',
            'user.create' => 'User yaratish',
            'user.update' => 'User yangilash',
            'user.delete' => 'User o‘chirish',
            'user.assign_role' => 'Userga role berish',

            'project.view' => 'Projectlarni ko‘rish',
            'project.create' => 'Project yaratish',
            'project.update' => 'Project yangilash',
            'project.delete' => 'Project o‘chirish',
            'project.assign_user' => 'Projectga user qo‘shish',

            'task.view' => 'Tasklarni ko‘rish',
            'task.create' => 'Task yaratish',
            'task.update' => 'Task yangilash',
            'task.delete' => 'Task o‘chirish',
            'task.change_status' => 'Task statusini o‘zgartirish',
            'task.comment' => 'Taskga izoh yozish',
        ];

        foreach ($permissions as $name => $display) {
            Permission::updateOrCreate(
                ['name' => $name],
                ['display_name' => $display]
            );
        }
    }
}
