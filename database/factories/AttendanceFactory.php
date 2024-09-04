<?php

namespace Database\Factories;

use App\Models\Attendance;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Attendance>
 */
class AttendanceFactory extends Factory
{
    protected $model = Attendance::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'role' => $this->faker->randomElement(['student', 'teacher']),
            'date' => Carbon::today(),
            'status' => $this->faker->randomElement(['present', 'absent', 'late', 'excuse']),
            'notes' => $this->faker->optional()->sentence
        ];
    }
}
