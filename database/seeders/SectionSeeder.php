<?php

namespace Database\Seeders;

use App\Models\SchoolClass;
use App\Models\section;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // section::factory()->count(25)->create();
        $sections = [
            'first section',
            'second section',
            'third section',
            'forth section',
            'fifth section'
        ];
        SchoolClass::all()->each(function ($schoolClass) use ($sections){ 
            foreach ($sections as $section) {
                section::create([
                    'name' => $section,
                    'school_class_id' => $schoolClass->id
                ]);
            }
        });
    }
}
