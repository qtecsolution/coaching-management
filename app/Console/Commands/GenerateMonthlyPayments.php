<?php

namespace App\Console\Commands;

use App\Models\Batch;
use App\Models\Payment;
use App\Models\StudentBatch;
use Illuminate\Console\Command;

class GenerateMonthlyPayments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    // protected $signature = 'app:generate-monthly-fees';
    protected $signature = 'payments:generate {month?}';

    /**
     * The console command description.
     *
     * @var string
     */

    protected $description = 'Generate monthly fees for all students';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Determine the month
        $month = $this->argument('month') ?? now()->format('Y-m');

        // Fetch all active batches
        $activeBatches = Batch::active()->get();

        foreach ($activeBatches as $batch) {
            // Fetch all student-batch relationships
            $studentBatches = StudentBatch::where('batch_id', $batch->id)->get();

            foreach ($studentBatches as $studentBatch) {
                // Check if a payment already exists for the given month
                $paymentExists = Payment::where('student_batch_id', $studentBatch->id)
                    ->where('month', $month)
                    ->exists();

                if (!$paymentExists) {
                    // Create a payment record
                    Payment::create([
                        'student_batch_id' => $studentBatch->id,
                        'transaction_id' => null,
                        'amount' => $batch->tuition_fee, 
                        'month' => $month,
                        'date' => now(),
                        'payment_method' => "Others", 
                        'note' => null,
                        'status' => 0, // Default as pending
                    ]);
                }
            }
        }

        $this->info("Payments for the month {$month} have been generated.");
    }
}