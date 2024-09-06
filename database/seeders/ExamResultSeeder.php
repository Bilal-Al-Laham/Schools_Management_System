<?php

namespace Database\Seeders;

use App\Models\ExamResult;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ExamResultSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ExamResult::factory()->count(20)->create();
    }
}
