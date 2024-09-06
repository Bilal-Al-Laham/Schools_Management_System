<?php

namespace Database\Factories;

use App\Models\Schedule;
use App\Models\section;
use App\Models\Subject;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Schedule>
 */
class ScheduleFactory extends Factory
{
    protected $model = Schedule::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $subject = Subject::inRandomOrder()->first();
        $section = section::inRandomOrder()->first();
        return [
            'name' => $this->faker->sentence(3),
            'subject_id' => $subject->id,
            'section_id' => $section->id,
            'type' => $this->faker->randomElement(['weekly', 'exam', 'activity']),
            'day_of_week' => implode(',', $this->faker->randomElements(['sunday', 'monday', 'tuesday', 'wednesday', 'thursday'], 5)),
            'date' => $this->faker->date,
            'start_time' => $this->faker->time('H:i'),
            'end_time' => $this->faker->time('H:i'),
            'notes' => $this->faker->text
        ];
    }
}
