<?php

namespace Database\Factories;

use App\Models\SchoolClass;
use App\Models\section;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\section>
 */
class SectionFactory extends Factory
{
    protected $model = section::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $class = SchoolClass::inRandomOrder()->first();
        return [
            'name' => $this->faker->randomElement(['first section', 'second section', 'third section', 'forth section', 'fiveth section', 'sixth section']),
            'school_class_id' => $class->id
        ];
    }
}
