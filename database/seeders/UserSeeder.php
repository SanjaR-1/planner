<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::where('name', 'admin')->firstOrFail();
        User::updateOrCreate(
            ['phone' => '998979222498'],
            [
                'role_id' => $adminRole->id,
                'name' => 'admin',
                'password' => Hash::make('123456'),
            ]
        );
    }
}
