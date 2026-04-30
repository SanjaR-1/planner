<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;
class UserSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::firstOrCreate([
            'email' => 'admin@mail.com',
        ], [
            'name' => 'Admin',
            'password' => Hash::make('123456'),
        ]);
        $adminRole = Role::firstOrCreate([
            'name' => 'admin',
        ]);
        $user->roles()->syncWithoutDetaching([
            $adminRole->id
        ]);
    }
}
