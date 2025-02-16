<?php

namespace Database\Seeders;

use App\Models\Payment;
use App\Models\Student;
use Illuminate\Database\Seeder;

class PaymentSeeder extends Seeder
{
    public function run(): void
    {
        // Get all student IDs for reference
        $studentIds = Student::pluck('id')->toArray();

        // Create 50 fake payments over the last 30 days
        for ($i = 0; $i < 50; $i++) {
            $studentId = fake()->randomElement($studentIds);
            $student = Student::find($studentId);

            Payment::create([
                'batch_id' => $student->currentBatch->batch_id,
                'student_id' => $student->id,
                'amount' => fake()->numberBetween(1000, 10000),
                'method' => fake()->randomElement(['cash', 'bkash', 'nagad', 'rocket']),
                'date' => fake()->dateTimeBetween('-30 days', 'now'),
                'created_at' => fake()->dateTimeBetween('-30 days', 'now'),
                'updated_at' => now(),
            ]);
        }
    }
}
