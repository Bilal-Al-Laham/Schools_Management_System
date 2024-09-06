<?php

namespace Database\Factories;

use App\Models\Document;
use App\Models\Subject;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Document>
 */
class DocumentFactory extends Factory
{

    protected $model = Document::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $subject = Subject::inRandomOrder()->first();
        return [
            'subject_id' => $subject->id,
            'document_name' => $subject->name,
            'document_path' => $this->faker->filePath()
        ];
    }
}
