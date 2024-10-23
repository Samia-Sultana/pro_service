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
        $editEmployee = Permission::create(['name' => 'edit employee']);
        $deleteEmployee = Permission::create(['name' => 'delete employee']);
        $createEmployee = Permission::create(['name' => 'create employee']);
        $viewEmployee = Permission::create(['name' => 'view employee']);


    }
}
