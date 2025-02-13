<?php

namespace Database\Seeders;

use App\Models\Batch;
use App\Models\Student;
use App\Models\StudentBatch;
use App\Models\StudentPayment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StudentBatchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Fetch all active batches and students
        $batches = Batch::active()->get();
        $students = Student::active()->get();

        foreach ($students as $student) {
            // Assign a random batch to the student
            $batch = $batches->random();

            // Create a student-batch relationship
            StudentBatch::create([
                'student_id' => $student->id,
                'batch_id' => $batch->id,
            ]);

            // Create a student payment for the batch
            $totalDue = $batch->price - ($batch->discount_type == 'percentage'? ($batch->discount / 100) * $batch->price : $batch->discount);

            StudentPayment::create([
                'student_id' => $student->id,
                'batch_id' => $batch->id,
                'amount' => $batch->price,
                'discount_type' => $batch->discount_type,
                'discount' => $batch->discount,
                'total_due' => $totalDue,
                'final_amount' => $totalDue,
            ]);

            // Update the total students count for the batch
            $batch->update(['total_students' => StudentBatch::where('batch_id', $batch->id)->count()]);
        }
    }
}
