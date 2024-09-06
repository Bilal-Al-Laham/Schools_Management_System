<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call([
            SchoolSeeder::class,
            SchoolClassSeeder::class,
            SectionSeeder::class,
            UserSeeder::class,
            SubjectSeeder::class,
            AssignmentSeeder::class,
            AttendanceSeeder::class,
            DocumentSeeder::class,
            ExamentionSeeder::class,
            ExamResultSeeder::class,
            FeeSeeder::class,
            LibrarySeeder::class,
            NoteSeeder::class,
            ScheduleSeeder::class
        ]);
    }
}
