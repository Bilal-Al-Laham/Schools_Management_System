<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Roles
        $adminRole = Role::updateOrCreate(['name'=>'admin'], ['guard_name' => 'web']);
        $teacherRole = Role::updateOrCreate(['name'=>'teacher'], ['guard_name' => 'web']);
        $studentRole = Role::updateOrCreate(['name'=>'student'], ['guard_name' => 'web']);
        $managerRole = Role::updateOrCreate(['name'=>'manager'], ['guard_name' => 'web']);

        // Define Permissions
        $permissions= [
            'index_assignment',
            'add_assignment',
            'update_hall',
            'create_section',
            'index_hall'
        ];
        foreach($permissions as $permissionName)
        {
            Permission::updateOrCreate(['name' => $permissionName, 'guard_name' => 'web']);
        }

        // Assign permissions to roles
        $adminRole->syncPermissions($permissions); // delete old permissions and keep those inside the $permissions
        $managerRole->syncPermissions($permissions);
        $studentRole->givePermissionTo(['index_assignment']); // add permissions on top of old ones
        $teacherRole->givePermissionTo(['add_assignment']); // add permissions on top of old ones

        $adminuser= \App\Models\User::updateOrCreate(
            ['role' => 'admin'],
            [
                'name' => 'Admin User',
                'email' => 'Admin@example.com',
                'password' => bcrypt('password'),
                'phone_number' => '+963 999 999 999',
                'school_class_id' => null,
                'section_id' => null
            ]
        );
        $adminuser->assignRole($adminRole);

        // Assign permissions associated with the role to the user
        $permissions = $adminRole->permissions()->pluck('name')->toArray();
        $adminuser->givePermissionTo($permissions);

        $managerUser = \App\Models\User::updateOrCreate(
            ['role' => 'manager'],
            [
                'name' => "manager User",
                'email' => "manager@example.com",
                'password' => bcrypt("manager"),
                'phone_number' => '+963 999 999 998',
                'school_class_id' => null,
                'section_id' => null
            ]
        );
        $managerUser->assignRole($managerRole);

        $studentuser=\App\Models\User::factory()->create([
            'name' => 'student User',
            'email' => 'student@example.com',
            'password' => bcrypt('password'),
        ]);
        $studentuser->assignRole($studentRole);

        // Assign permissions associated with the role to the user
        $permissions = $studentRole->permissions()->pluck('name')->toArray();
        $studentuser->givePermissionTo($permissions);

        $teacherUser = \App\Models\User::updateOrCreate(
            ['role' => 'teacher'], // Use role as unique condition
            [
                'name' => 'Teacher User',
                'email' => 'teacher@example.com',
                'password' => bcrypt('password'),
                'phone_number' => '+963 999 999 996',
                'school_class_id' => null,
                'section_id' => null
            ]);
            $teacherUser->assignRole($teacherRole);
        
    
        // Assign permissions associated with the role to the user
        $permissions = $teacherRole->permissions()->pluck('name')->toArray();
        $teacherUser->givePermissionTo($permissions);

        $teacherUser2 = \App\Models\User::updateOrCreate(
            ['role' => 'teacher'], // Use role as unique condition
            [
                'name' => 'Teacher2 User',
                'email' => 'teacher2@example.com',
                'password' => bcrypt('password'),
                'phone_number' => '+963 999 999 995',
                'school_class_id' => null,
                'section_id' => null
            ]);
            $teacherUser2->assignRole($teacherRole);
        
        // Assign permissions associated with the role to the user
        $permissions = $teacherRole->permissions()->pluck('name')->toArray();
        $teacherUser2->givePermissionTo($permissions);
    }
}