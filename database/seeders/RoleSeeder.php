<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Permissions
        Permission::create(['name' => 'add queries']);
        Permission::create(['name' => 'edit queries']);
        Permission::create(['name' => 'delete queries']);
        Permission::create(['name' => 'comment queries']);

        // 2. Create Roles and Assign Permissions
        $admin = Role::create(['name' => 'admin']);
        $admin->givePermissionTo(Permission::all());

        $patient = Role::create(['name' => 'patient']);
        $patient->givePermissionTo('add queries');
        $patient->givePermissionTo('edit queries');

        $doctor = Role::create(['name' => 'doctor']);
        $doctor->givePermissionTo('comment queries');
    }
}
