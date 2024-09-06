<?php

namespace Database\Factories;

use App\Models\Note;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Note>
 */
class NoteFactory extends Factory
{
    protected $model = Note::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $student = User::where('role', 'student')->inRandomOrder()->first() ;
        $teacher = User::where('role', 'teacher')->inRandomOrder()->first(

        ) ;
        return [
            'student_id' => $student->id,
            'teacher_id' => $teacher->id,
            'content' => $this->faker->paragraph
        ];
    }
}
