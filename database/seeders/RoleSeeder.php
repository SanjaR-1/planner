<?php
namespace Database\Seeders;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Admin role yaratamiz (yoki mavjudini olamiz)
        $adminRole = Role::firstOrCreate([
            'name' => 'admin',
        ]);

        // 2. Developer role
        $developerRole = Role::firstOrCreate([
            'name' => 'developer',
        ]);

        // 3. Barcha permissionlarni olamiz
        $allPermissions = Permission::pluck('id')->toArray();

        // 4. Adminga barcha permissionlarni beramiz
        $adminRole->permissions()->sync($allPermissions);

        // 5. Developer uchun faqat keraklilar
        $developerPermissions = Permission::whereIn('name', [
            'task.update',
            'task.change_status',
            'task.comment',
        ])->pluck('id')->toArray();

        $developerRole->permissions()->sync($developerPermissions);
    }
}
