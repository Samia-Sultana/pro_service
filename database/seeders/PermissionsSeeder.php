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


    }
}
