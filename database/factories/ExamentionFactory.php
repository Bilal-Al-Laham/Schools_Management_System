<?php

namespace Database\Factories;

use App\Models\Examention;
use App\Models\SchoolClass;
use App\Models\Subject;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Examention>
 */
class ExamentionFactory extends Factory
{
    protected $model = Examention::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $subject = Subject::inRandomOrder()->first();
        $schoolClass = SchoolClass::inRandomOrder()->first();
        // $name = Subject::where('name', $subject->name)->getAttribute('name');

        return [
            'name' => $subject->name,
            'subject_id' => $subject->id,
            'school_class_id' => $schoolClass->id,
            'exam_date' => Carbon::today()->toDateString(),
            'start_time' => Carbon::now()->addHours(1)->toDateTimeString(),
            'end_time' => Carbon::now()->addHours(2)->toDateTimeString(),
            'type' => $this->faker->randomElement(['chapter tests', 'midterm Exam', 'final Exam']),
        ];
    }
}
