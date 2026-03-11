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
        Permission::firstOrCreate(['name' => 'view all entries']);
        Permission::firstOrCreate(['name' => 'edit entries']);
        Permission::firstOrCreate(['name' => 'add feedback']);
        Permission::firstOrCreate(['name' => 'grade entries']);
        Permission::firstOrCreate(['name' => 'manage students']);
        Permission::firstOrCreate(['name' => 'manage themes']);

        $admin = Role::firstOrCreate(['name' => 'admin']);
        $internshipManager = Role::firstOrCreate(['name' => 'internship_manager']);
        $teacher = Role::firstOrCreate(['name' => 'teacher']);
        $student = Role::firstOrCreate(['name' => 'student']);

        $admin->givePermissionTo(Permission::all());

        $internshipManager->givePermissionTo([
            'view internships',
            'create internships',
            'edit internships',
            'delete internships',
            'view all entries',
            'edit entries',
            'add feedback',
            'grade entries',
            'manage students',
            'manage themes',
        ]);

        $teacher->givePermissionTo([
            'view internships',
            'view all entries',
        ]);

        $student->givePermissionTo('view internships');
    }
}
