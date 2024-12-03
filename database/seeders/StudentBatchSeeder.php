<?php

namespace Database\Seeders;

use App\Models\Batch;
use App\Models\Student;
use App\Models\StudentBatch;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StudentBatchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $batches = Batch::active()->get();
        $students = Student::all();
        foreach ($students as $student) {
            $batch = $batches->random();
            StudentBatch::create([
                'student_id' => $student->id,
                'batch_id' => $batch->id,
            ]);
        }
    }
}
