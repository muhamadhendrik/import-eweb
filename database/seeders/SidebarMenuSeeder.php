<?php

namespace Database\Seeders;

use App\Models\Acl\Menu;
use Illuminate\Database\Seeder;

class SidebarMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $parent = Menu::updateOrCreate([
        //     'name' => 'Products',
        //     'description' => 'Products',
        //     'link' => '#',
        //     'parent_id' => 0,
        //     'parent_type' => 'parent',
        //     'parent_icon' => 'menu-icon ti ti-brand-producthunt',
        //     'permission_key' => null,
        //     'permission_option' => null,
        //     'ordering' => 2,
        // ]);

        // Menu::updateOrCreate([
        //     'name' => 'Products > Product List',
        //     'description' => 'Products > Product List',
        //     'link' => 'backoffice/master/products',
        //     'parent_id' => $parent->id,
        //     'parent_type' => null,
        //     'parent_icon' => null,
        //     'permission_key' => 'master-product',
        //     'permission_option' => 'view,delete',
        //     'ordering' => 1,
        // ]);

        // Menu::updateOrCreate([
        //     'name' => 'Products > Add Product',
        //     'description' => 'Products > Add Product',
        //     'link' => 'backoffice/master/products/create',
        //     'parent_id' => $parent->id,
        //     'parent_type' => null,
        //     'parent_icon' => null,
        //     'permission_key' => 'master-product',
        //     'permission_option' => 'update,create',
        //     'ordering' => 2,
        // ]);

        Menu::updateOrCreate([
            'name' => 'Import POS Eweb',
        ], [
            'description' => 'Import POS Eweb',
            'link' => 'backoffice/import-pos-eweb',
            'parent_id' => 0,
            'parent_type' => 'parent',
            'parent_icon' => 'menu-icon ti ti-user',
            'permission_key' => 'customer',
            'permission_option' => 'view,delete,update,create',
            'ordering' => 4,
        ]);
    }
}
