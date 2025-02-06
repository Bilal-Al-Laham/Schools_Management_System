<?php

namespace Database\Seeders;

use App\Models\Fee;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Fee::create([
            'student_id' => 1,
            'total_amount' => 300.00,
            'remaining_amount' => 300.00,
            'first_payment_date' => now()->addMonths(1),
            'final_payment_date' => now()->addMonths(5)
        ]);
    }
}
