<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::create([
            'name' => 'Vendor Management',
            'action' => 'read',
        ]);

        Permission::create([
            'name' => 'Vendor Management',
            'action' => 'edit',
        ]);

        Permission::create([
            'name' => 'Vendor Management',
            'action' => 'create',
        ]);

        Permission::create([
            'name' => 'Vendor Management',
            'action' => 'delete',
        ]);

        Permission::create([
            'name' => 'Expert Management',
            'action' => 'read',
        ]);

        Permission::create([
            'name' => 'Expert Management',
            'action' => 'edit',
        ]);

        Permission::create([
            'name' => 'Expert Management',
            'action' => 'create',
        ]);

        Permission::create([
            'name' => 'Expert Management',
            'action' => 'delete',
        ]);

        Permission::create([
            'name' => 'Category Management',
            'action' => 'read',
        ]);

        Permission::create([
            'name' => 'Category Management',
            'action' => 'edit',
        ]);

        Permission::create([
            'name' => 'Category Management',
            'action' => 'create',
        ]);

        Permission::create([
            'name' => 'Category Management',
            'action' => 'delete',
        ]);

        Permission::create([
            'name' => 'Order Management',
            'action' => 'read',
        ]);

        Permission::create([
            'name' => 'Order Management',
            'action' => 'edit',
        ]);

        Permission::create([
            'name' => 'Order Management',
            'action' => 'create',
        ]);

        Permission::create([
            'name' => 'Order Management',
            'action' => 'delete',
        ]);

        Permission::create([
            'name' => 'Role Management',
            'action' => 'read',
        ]);

        Permission::create([
            'name' => 'Role Management',
            'action' => 'edit',
        ]);

        Permission::create([
            'name' => 'Role Management',
            'action' => 'create',
        ]);

        Permission::create([
            'name' => 'Role Management',
            'action' => 'delete',
        ]);

        Permission::create([
            'name' => 'Admin Management',
            'action' => 'read',
        ]);

        Permission::create([
            'name' => 'Admin Management',
            'action' => 'edit',
        ]);

        Permission::create([
            'name' => 'Admin Management',
            'action' => 'create',
        ]);

        Permission::create([
            'name' => 'Admin Management',
            'action' => 'delete',
        ]);

        Permission::create([
            'name' => 'Customer Management',
            'action' => 'read',
        ]);

        Permission::create([
            'name' => 'Customer Management',
            'action' => 'edit',
        ]);

        Permission::create([
            'name' => 'Customer Management',
            'action' => 'create',
        ]);

        Permission::create([
            'name' => 'Customer Management',
            'action' => 'delete',
        ]);

        Permission::create([
            'name' => 'Expense Type Management',
            'action' => 'read',
        ]);

        Permission::create([
            'name' => 'Expense Type Management',
            'action' => 'edit',
        ]);

        Permission::create([
            'name' => 'Expense Type Management',
            'action' => 'create',
        ]);

        Permission::create([
            'name' => 'Expense Type Management',
            'action' => 'delete',
        ]);

        Permission::create([
            'name' => 'Expense Management',
            'action' => 'read',
        ]);

        Permission::create([
            'name' => 'Expense Management',
            'action' => 'edit',
        ]);

        Permission::create([
            'name' => 'Expense Management',
            'action' => 'create',
        ]);

        Permission::create([
            'name' => 'Expense Management',
            'action' => 'delete',
        ]);

        Permission::create([
            'name' => 'Income Management',
            'action' => 'read',
        ]);

        Permission::create([
            'name' => 'Wallet Management',
            'action' => 'read',
        ]);

        Permission::create([
            'name' => 'Wallet Management',
            'action' => 'send-money',
        ]);

        Permission::create([
            'name' => 'Wallet Management',
            'action' => 'withdraw',
        ]);


    }
}
