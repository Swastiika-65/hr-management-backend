<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
class RoleSeeder extends Seeder
{
    public function run()
    {
        $superadmin = Role::create(['name' => 'superadmin']);
        $admin      = Role::create(['name' => 'admin']);
        $employee   = Role::create(['name' => 'employee']);
        $hr         = Role::create(['name' => 'hr']);

        User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@example.com',
            'password' => Hash::make('password'),
            'role_id' => $superadmin->id
        ]);
    }
}
