<?php

namespace Database\Seeders;

use App\Models\Examention;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ExamentionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Examention::factory()->count(20)->create();
    }
}
