<?php

namespace Database\Seeders;

use App\Models\SchoolClass;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SchoolClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // SchoolClass::factory()->count(6)->create();
        $classes = [
            'first class',
            'second class',
            'third class',
            'fourth class',
            'fifth class',
            'sixth class',
            'seventh class',
            'eighth class',
            'ninth class',
            'tenth class',
            'eleventh class',
            'twelfth class',
        ];

        foreach ($classes as $class) {
            SchoolClass::create([
                'name' => $class,
                'year' => now()->year
            ]);
        }
    }
}
