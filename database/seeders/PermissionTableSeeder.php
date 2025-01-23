<?php

namespace Database\Seeders;

use App\Models\Acl\Menu;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Menu::updateOrCreate([
            'name' => 'Dashboard',
            'description' => 'Dashboard',
            'link' => 'backoffice/dashboard',
            'parent_id' => 0,
            'parent_type' => 'parent',
            'parent_icon' => 'menu-icon tf-icons ti ti-smart-home',
            'permission_key' => 'dashboard',
            'permission_option' => 'view',
            'ordering' => 1,
        ]);

        $menu_admin_management = Menu::updateOrCreate([
            'name' => 'Admin Management',
            'description' => 'Admin Management',
            'link' => '#',
            'parent_id' => 0,
            'parent_type' => 'parent',
            'parent_icon' => 'menu-icon tf-icons ti ti-settings',
            'permission_key' => null,
            'permission_option' => null,
            'ordering' => 99,
        ]);

        Menu::updateOrCreate([
            'name' => 'Admin Management > User List',
            'description' => 'Admin Management > User List',
            'link' => 'backoffice/user-management/users',
            'parent_id' => $menu_admin_management->id,
            'parent_type' => null,
            'parent_icon' => null,
            'permission_key' => 'user-management-user',
            'permission_option' => 'view,create,update,delete',
            'ordering' => 1,
        ]);

        Menu::updateOrCreate([
            'name' => 'Admin Management > User Role',
            'description' => 'Admin Management > User Role',
            'link' => 'backoffice/user-management/roles',
            'parent_id' => $menu_admin_management->id,
            'parent_type' => null,
            'parent_icon' => null,
            'permission_key' => 'user-management-role',
            'permission_option' => 'view,create,update,delete',
            'ordering' => 2,
        ]);

        $all_access = [
            'view',
            'create',
            'update',
            'delete',
        ];

        foreach ($all_access as $access) {
            Permission::updateOrCreate([
                'name' => 'user-management-user-'.$access,
                'guard_name' => 'web',
            ]);

            Permission::updateOrCreate([
                'name' => 'user-management-role-'.$access,
                'guard_name' => 'web',
            ]);
        }

        Permission::updateOrCreate([
            'name' => 'dashboard-view',
            'guard_name' => 'web',
        ]);
    }
}
