<?php

namespace Database\Seeders;

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
        section::factory()->count(15)->create();
    }
}
