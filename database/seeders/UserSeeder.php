<?php

namespace Database\Seeders;

use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $studentRole = 'student';
        $managerRole = 'manager';
        $teacherRole = 'teacher';
        $adminRole = 'admin';


        Section::all()->each(function ($section) use ($studentRole) {
            $studentCount = rand(10, 15);
            User::factory()->count($studentCount)->create([
                'role' => $studentRole,
                'school_class_id' => $section->school_class_id, 
                'section_id' => $section->id 
            ]);
        });

        SchoolClass::all()->each(function ($schoolClass) use ($managerRole) {
            User::factory()->count(12)->create([
                'role' => $managerRole,
                'school_class_id' => $schoolClass->id, 
                'section_id' => null 
            ]);
        });

        SchoolClass::all()->each(function ($schoolClass) use ($teacherRole) {
            $teacherRole = 'teacher';
            User::factory()->count(10)->create([
                'role' => $teacherRole,
                'school_class_id' => $schoolClass->id, 
                'section_id' => null
            ]);
        });

        User::updateOrCreate(
            ['role' => $adminRole],
            [
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => bcrypt('password'),
                'phone_number' => '+963 999 999 999',
                'school_class_id' => null, // Admin is not tied to any specific class
                'section_id' => null
            ]
        );
    }        
}
