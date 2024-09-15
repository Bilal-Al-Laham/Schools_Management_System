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

        // Define Permissions
        $permissions= [
            'index_assignment',
            'update_hall',
            'create_hall',
            'index_hall'
        ];
        foreach($permissions as $permissionName)
        {
            Permission::findOrCreate($permissionName,'web');
        }

        // Assign permissions to roles
        $adminRole->syncPermissions($permissions); // delete old permissions and keep those inside the $permissions
        $studentRole->givePermissionTo(['index_assignment']); // add permissions on top of old ones

        $adminuser=\App\Models\User::factory()->create([
            "name" => 'Admin User',
            'email' => 'Admin@example.com',
            'password' => bcrypt('password'),
        ]);
        $adminuser->assignRole($adminRole);

        // Assign permissions associated with the role to the user
        $permissions = $adminRole->permissions()->pluck('name')->toArray();
        $adminuser->givePermissionTo($permissions);

        $studentuser=\App\Models\User::factory()->create([
            'name' => 'student User',
            'email' => 'student@example.com',
            'password' => bcrypt('password'),
        ]);
        $studentuser->assignRole($studentRole);

        // Assign permissions associated with the role to the user
        $permissions = $studentRole->permissions()->pluck('name')->toArray();
        $studentuser->givePermissionTo($permissions);

        $teacheruser=\App\Models\User::factory()->create([
            'name' => 'Planner User',
            'email' => 'planner@example.com',
            'password' => bcrypt('password'),
        ]);
        $teacheruser->assignRole($teacherRole);

        // Assign permissions associated with the role to the user
        $permissions = $teacherRole->permissions()->pluck('name')->toArray();
        $teacheruser->givePermissionTo($permissions);

        $taecheruser2=\App\Models\User::factory()->create([
            'name' => 'Planner User 2',
            'email' => 'planner2@example.com',
            'password' => bcrypt('password'),
        ]);
        $taecheruser2->assignRole($teacherRole);

        // Assign permissions associated with the role to the user
        $permissions = $teacherRole->permissions()->pluck('name')->toArray();
        $taecheruser2->givePermissionTo($permissions);

    }
}
