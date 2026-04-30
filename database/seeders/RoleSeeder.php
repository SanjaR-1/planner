<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::firstOrCreate(
            ['name' => 'admin'],
            ['display_name' => 'Administrator']
        );

        $developerRole = Role::firstOrCreate(
            ['name' => 'developer'],
            ['display_name' => 'Developer']
        );

        $allPermissions = Permission::pluck('id')->toArray();

        $adminRole->permissions()->sync($allPermissions);

        $developerPermissions = Permission::whereIn('name', [
            'project.view',
            'task.view',
            'task.update',
            'task.change_status',
            'task.comment',
        ])->pluck('id')->toArray();

        $developerRole->permissions()->sync($developerPermissions);
    }
}
