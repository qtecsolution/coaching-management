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
        foreach ($courses as $course) {
            Batch::create([
                'title' => 'Batch ' . $course->id,
                'course_id' => $course->id,
                'status' => fake()->numberBetween(0, 2),
                'price' => fake()->randomFloat(2, 0, 8000),
                'discount_type' => fake()->randomElement(['flat', 'percentage']),
                'discount' => fake()->randomFloat(2, 0, 60),
            ]);
        }

        // Fetch all teachers
        $teachers = User::where('user_type', 'teacher')->get();
        if ($teachers->isEmpty()) {
            $this->command->warn("No teachers found. Please seed the users with 'teacher' user type.");
            return;
        }

        // Create batch days
        $batches = Batch::all();

        foreach ($batches as $batch) {
            BatchDay::create([
                'batch_id' => $batch->id,
                'user_id' => $teachers->random()->id,
                'day' => rand(1, 7),
                'start_time' => '09:00',
                'end_time' => '12:00',
            ]);

            BatchDay::create([
                'batch_id' => $batch->id,
                'user_id' => $teachers->random()->id,
                'day' => rand(1, 7),
                'start_time' => '15:00',
                'end_time' => '18:00',
            ]);
        }
    }
}
