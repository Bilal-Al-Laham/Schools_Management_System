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
        return [
            'subject_id' => Subject::factory(), 
            'teacher_id' => User::factory(), 
            'section_id' => section::factory(), 
            'title' => $this->faker->title, 
            'description' => $this->faker->paragraph, 
            'due_date' => Carbon::now()->addDays(rand(1, 30))
        ];
    }
}
