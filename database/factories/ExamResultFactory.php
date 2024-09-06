<?php

namespace Database\Factories;

use App\Models\Examention;
use App\Models\ExamResult;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ExamResult>
 */
class ExamResultFactory extends Factory
{
    protected $model = ExamResult::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $student = User::where('role', 'student')->inRandomOrder()->first();
        $examention = Examention::inRandomOrder()->first();

        return [
            'student_id' => $student->id,
            'examention_id' => $examention->id,
            'score' => $this->faker->numberBetween(0, 100)
        ];
    }
}
