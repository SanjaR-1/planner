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
        $guestRole = Role::firstOrCreate(
            ['name' => 'guest'],
            ['display_name' => 'mehmon']
        );
        $allPermissions = Permission::pluck('id')->toArray();
        $adminRole->permissions()->sync($allPermissions);
        $guestPermissions = Permission::whereIn('name', [
        ])->pluck('id')->toArray();
        $guestRole->permissions()->sync($guestPermissions);
    }
}
