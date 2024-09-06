<?php

namespace Database\Factories;

use App\Models\Library;
use App\Models\School;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Library>
 */
class LibraryFactory extends Factory
{
    protected $model = Library::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $school = School::inRandomOrder()->first();

        return [
            'school_id' => $school->id,
            'title' => $this->faker->sentence(3),
            'author' => $this->faker->userName,
            'isbn' => $this->faker->unique()->isbn10(),
            'quantity' => $this->faker->numberBetween(1, 100),
        ];
    }
}
