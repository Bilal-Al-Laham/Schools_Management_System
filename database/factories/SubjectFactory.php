<?php

namespace Database\Factories;

use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Subject>
 */
class SubjectFactory extends Factory
{
    protected $model = Subject::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $school_class = SchoolClass::inRandomOrder()->first();
        $teacher = User::where('role', 'teacher')->inRandomOrder()->first();
        return [
            'name' => $this->faker->word,
            'school_class_id' => $school_class->id,
            'teacher_id' => $teacher->id
        ];
    }
}
