<?php

namespace App\Console\Commands;

use App\Models\Batch;
use App\Models\Payment;
use App\Models\PaymentReport;
use App\Models\StudentBatch;
use Carbon\Carbon;
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
        if (!preg_match('/^\d{4}-(0[1-9]|1[0-2])$/', $month)) {
            $this->error('Invalid month format. Please use YYYY-MM format, and month must be between 01 and 12.');
            return;
        }

        // Get the current month
        $currentMonth = now()->format('Y-m');

        // Check if the provided month is greater than the current month
        if ($month > $currentMonth) {
            $this->error('The provided month cannot be greater than the current month of the current year.');
            return;
        }

        // Convert the month to the start of the month using Carbon
        $cutoffDate = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
        $activeBatches = Batch::active()->whereDate('created_at', '<=', $cutoffDate)->get();

        foreach ($activeBatches as $batch) {
            $studentBatches = StudentBatch::where('batch_id', $batch->id)->get();
            foreach ($studentBatches as $studentBatch) {
                Payment::firstOrCreate(
                    [
                        'student_batch_id' => $studentBatch->id,
                        'month' => $month,
                    ],
                    [
                        'transaction_id' => null,
                        'amount' => $batch->tuition_fee,
                        'date' => now(),
                        'payment_method' => 'Others',
                        'note' => null,
                        'status' => 0,
                    ]
                );
            }
        }

        $estimatedCollectionAmount = $activeBatches->sum(function ($batch) {
            return $batch->total_students * $batch->tuition_fee;
        }) ?? 0;

        $collectedAmount = Payment::where('month', $month)
        ->where('status', 1)
            ->sum('amount');

        // Update or create the report
        $report = PaymentReport::updateOrCreate(
            ['month' => $month],
            [
                'estimated_collection_amount' => $estimatedCollectionAmount,
                'collected_amount' => $collectedAmount,
                'due_amount' => $estimatedCollectionAmount - $collectedAmount,
            ]
        );

        // Display a success message
        $message = 'Payment data generated for ' . Carbon::parse($month)->format('M, Y') . ' successfully.';
        $this->info($message);
    }
}
