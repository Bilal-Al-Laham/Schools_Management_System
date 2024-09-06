<?php

namespace Database\Factories;

use App\Models\Fee;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Fee>
 */
class FeeFactory extends Factory
{
    protected $model = Fee::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $student = User::where('role', 'student')->inRandomOrder()->first();
        
        return [
            'student_id' => $student->id,
            'amount' => $this->faker->randomFloat(2, 1000000, 4000000),
            'payment_date' => $this->faker->optional()->date(),
            'due_date' => $this->faker->dateTimeBetween('now', '+1year')->format('Y-m-d'),
            'status' => $this->faker->randomElement(['is paid', 'is not paid'])
        ];
    }
}
