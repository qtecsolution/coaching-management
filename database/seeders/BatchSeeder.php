<?php

namespace Database\Seeders;

use App\Models\Batch;
use App\Models\BatchDay;
use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Seeder;

class BatchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Fetch all courses
        $courses = Course::all();

        if ($courses->isEmpty()) {
            $this->command->warn("No courses found. Please seed the courses first.");
            return;
        }

        // Create batches
        $batches = [
            ['name' => 'Batch 1', 'course_id' => $courses->random()->id, 'status' => fake()->numberBetween(0, 2)],
            ['name' => 'Batch 2', 'course_id' => $courses->random()->id, 'status' => fake()->numberBetween(0, 2)],
            ['name' => 'Batch 3', 'course_id' => $courses->random()->id, 'status' => fake()->numberBetween(0, 2)],
            ['name' => 'Batch 4', 'course_id' => $courses->random()->id, 'status' => fake()->numberBetween(0, 2)],
            ['name' => 'Batch 5', 'course_id' => $courses->random()->id, 'status' => fake()->numberBetween(0, 2)],
        ];

        foreach ($batches as $batch) {
            Batch::create($batch);
        }

        // Fetch all batches and teachers
        $batches = Batch::all();
        $teachers = User::where('user_type', 'teacher')->get();

        if ($teachers->isEmpty()) {
            $this->command->warn("No teachers found. Please seed the users with 'teacher' user type.");
            return;
        }

        // Create batch days
        $batchDays = [
            ['batch_id' => $batches->random()->id, 'user_id' => $teachers->random()->id, 'day' => 1, 'start_time' => '09:00', 'end_time' => '12:00'],
            ['batch_id' => $batches->random()->id, 'user_id' => $teachers->random()->id, 'day' => 2, 'start_time' => '09:00', 'end_time' => '12:00'],
            ['batch_id' => $batches->random()->id, 'user_id' => $teachers->random()->id, 'day' => 3, 'start_time' => '13:00', 'end_time' => '16:00'],
            ['batch_id' => $batches->random()->id, 'user_id' => $teachers->random()->id, 'day' => 4, 'start_time' => '13:00', 'end_time' => '16:00'],
            ['batch_id' => $batches->random()->id, 'user_id' => $teachers->random()->id, 'day' => 5, 'start_time' => '09:00', 'end_time' => '12:00'],
        ];

        foreach ($batchDays as $batchDay) {
            BatchDay::create($batchDay);
        }
    }
}
