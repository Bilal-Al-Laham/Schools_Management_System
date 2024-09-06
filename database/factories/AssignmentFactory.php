<?php

namespace Database\Factories;

use App\Models\Assignment;
use App\Models\section;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Assignment>
 */
class AssignmentFactory extends Factory
{

    protected $model = Assignment::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // $teacher = User::where('role', 'teacher')->inRandomOrder()->first();
        // $subject = Subject::where('teacher_id', $teacher->id)->inRandomOrder()->first();

        $teacher = User::where('role', 'teacher')->inRandomOrder()->first() ?? User::factory()->create(['role' => 'teacher']);
        $subject = Subject::where('teacher_id', $teacher->id)->inRandomOrder()->first() ?? Subject::factory()->create(['teacher_id' => $teacher->id]);

        return [
            'subject_id' => $subject->id, 
            'teacher_id' => $teacher->id, 
            'section_id' => section::factory(), 
            'title' => $this->faker->sentence, 
            'description' => $this->faker->paragraph, 
            'due_date' => Carbon::now()->addDays(rand(1, 7))
        ];
    }
}
