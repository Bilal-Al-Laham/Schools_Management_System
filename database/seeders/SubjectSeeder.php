<?php

namespace Database\Seeders;

use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Subject::factory()->count(25)->create();
        $subjects = [
            'Math',
            'Seience',
            'Physics',
            'Chemistry',
            'Arabic',
            'English',
            'Hitory',
            'Geography'
        ];

        $teachers = User::where('role', 'teacher')->get();

        SchoolClass::all()->each(function ($schoolClass) use ($subjects, $teachers) {
            foreach ($subjects as $subject) {
                $teacher = $teachers->random();

                Subject::factory()->create([
                    'name' => $subject,
                    'school_class_id' => $schoolClass->id,
                    'teacher_id' => $teacher->id
                ]);
            }
        });
    }
}
