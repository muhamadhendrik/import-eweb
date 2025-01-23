<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class CreateAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (User::where('email', 'superadmin@gmail.com')->exists()) {
            return;
        }

        $user = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@gmail.com',
            'password' => bcrypt('superadmin123')
        ]);

        $role = Role::create(['name' => 'superadmin']);

        $permissions = Permission::pluck('id', 'name')->all();

        $role->syncPermissions($permissions);

        $user->assignRole([$role->id]);
    }
}
