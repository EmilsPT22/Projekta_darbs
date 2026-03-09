<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        Permission::firstOrCreate(['name' => 'create internships']);
        Permission::firstOrCreate(['name' => 'edit internships']);
        Permission::firstOrCreate(['name' => 'delete internships']);
        Permission::firstOrCreate(['name' => 'view internships']);

        $admin = Role::firstOrCreate(['name' => 'admin']);
        $student = Role::firstOrCreate(['name' => 'student']);

        $admin->givePermissionTo(Permission::all());

        $student->givePermissionTo('view internships');
    }
}
