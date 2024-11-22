<?php

namespace Database\Seeders;

use App\Models\Assignment;
use App\Models\section;
use App\Models\Subject;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AssignmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Assignment::truncate();
        $subjects = Subject::with('teacher')->whereHas('teacher')->get();

        // Assignment::factory()->count(10)->create();
        foreach ($subjects as $subject) {
            if ($subject->teacher) {

            $sections = section::where('school_class_id', $subject->school_class_id)->get();

            foreach ($sections as $section) {
                    Assignment::create([
                        'subject_id' => $subject->id,
                        'teacher_id' => $subject->teacher->id,
                        'section_id' => $section->id,
                        'title' => $subject->name . "Assignment",
                        'description' => fake()->paragraph,
                        'due_date' => Carbon::now()->addWeek(1)
                    ]);
                }
            }
        }
    }
}
