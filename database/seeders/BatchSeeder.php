<?php

namespace Database\Seeders;

use App\Models\Batch;
use App\Models\BatchDay;
use App\Models\Level;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BatchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $levels = Level::all();

        $batches = [
            ['name' => 'Batch 1', 'level_id' => $levels->random()->id, 'tuition_fee' => 1000],
            ['name' => 'Batch 2', 'level_id' => $levels->random()->id, 'tuition_fee' => 1200],
            ['name' => 'Batch 3', 'level_id' => $levels->random()->id, 'tuition_fee' => 1500],
            ['name' => 'Batch 4', 'level_id' => $levels->random()->id, 'tuition_fee' => 1300],
        ];

        foreach ($batches as $batch) {
            Batch::create($batch);
        }

        // Fetch some Batches, Teachers (Users), and Subjects from the database
        $batches = Batch::all();
        $subjects = Subject::all();
        $teachers = User::where('user_type', 'teacher')->get();

        // Define some batch days data
        $batchDays = [
            ['batch_id' => $batches->random()->id, 'user_id' => $teachers->random()->id, 'subject_id' => $subjects->random()->id, 'day' => '1', 'start_time' => '09:00', 'end_time' => '12:00'],
            ['batch_id' => $batches->random()->id, 'user_id' => $teachers->random()->id, 'subject_id' => $subjects->random()->id, 'day' => '2', 'start_time' => '09:00', 'end_time' => '12:00'],
            ['batch_id' => $batches->random()->id, 'user_id' => $teachers->random()->id, 'subject_id' => $subjects->random()->id, 'day' => '3', 'start_time' => '01:00', 'end_time' => '04:00'],
            ['batch_id' => $batches->random()->id, 'user_id' => $teachers->random()->id, 'subject_id' => $subjects->random()->id, 'day' => '4', 'start_time' => '01:00', 'end_time' => '04:00'],
            ['batch_id' => $batches->random()->id, 'user_id' => $teachers->random()->id, 'subject_id' => $subjects->random()->id, 'day' => '5', 'start_time' => '09:00', 'end_time' => '12:00'],
        ];

        // Insert the data into the batch_days table
        foreach ($batchDays as $batchDay) {
            BatchDay::create($batchDay);
        }
    }
}
