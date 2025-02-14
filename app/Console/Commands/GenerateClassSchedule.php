<?php

namespace App\Console\Commands;

use App\Models\Batch;
use App\Models\BatchDayDate;
use Carbon\Carbon;
use Illuminate\Console\Command;

class GenerateClassSchedule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-class-schedule';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $batches = Batch::with('batch_days')->active()->get();
        $today = Carbon::now();
        $nextSaturday = $today->copy()->next(Carbon::SATURDAY);

        foreach ($batches as $batch) {
            foreach ($batch->batch_days as $day) {
                // Convert day number (1-7) to Carbon day constant (0-6)
                $dayMapping = [
                    1 => Carbon::SATURDAY,
                    2 => Carbon::SUNDAY,
                    3 => Carbon::MONDAY,
                    4 => Carbon::TUESDAY,
                    5 => Carbon::WEDNESDAY,
                    6 => Carbon::THURSDAY,
                    7 => Carbon::FRIDAY,
                ];

                $classDate = $nextSaturday->copy();

                // If the day number is greater than 1 (Saturday), add the difference
                if ($day->day > 1) {
                    $classDate->addDays($day->day - 1);
                }

                BatchDayDate::create([
                    'batch_day_id' => $day->id,
                    'date' => $classDate->format('Y-m-d'),
                    'day' => $day->day
                ]);
            }
        }

        $this->info('Class schedule generated successfully for next week.');
    }
}
