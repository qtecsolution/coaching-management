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
            $price = fake()->randomFloat(0, 1000, 8000);
            $discountType = fake()->randomElement(['flat', 'percentage']);
            $discount = fake()->randomFloat(0, 0, 60);

            Batch::create([
                'title' => 'Batch ' . $course->id,
                'course_id' => $course->id,
                'status' => fake()->numberBetween(0, 2),
                'price' => $price,
                'discount_type' => $discountType,
                'discount' => $discount,
                'total_price' => $this->calculateTotalPrice($price, $discountType, $discount),
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

    public function calculateTotalPrice($price, $discountType, $discount)
    {
        if ($discountType == 'flat') {
            return $price - $discount;
        } elseif ($discountType == 'percentage') {
            return $price - ($price * ($discount / 100));
        }
    }
}
