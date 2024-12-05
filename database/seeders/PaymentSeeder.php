<?php

namespace Database\Seeders;

use App\Models\Payment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Faker\Factory as Faker;

class PaymentSeeder extends Seeder
{

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        try {
            for ($i = 0; $i <= 10; $i++) {
                $month = now()->subDays(30 * $i)->format('Y-m');
                Artisan::call('payments:generate', ['month' => $month]);
            }
        } catch (\Exception $e) {
            Log::error('Error in PaymentSeeder: ' . $e->getMessage());
        }

        $count = Payment::count();
        $randomPayments = Payment::query()
            ->inRandomOrder()
            ->take(floor($count / 2))
            ->get();

        foreach ($randomPayments as $payment) {
            $payment->update([
                'status' => 1,
                'transaction_id' =>
                str()->random(8),
                'note' => $faker->sentence(5),
            ]);
        }
        // current month payments
        $payments = Payment::query()->where('month', now()->format('Y-m'))->take(5)
            ->get();
        foreach ($payments as $payment) {
            $payment->update([
                'status' => 1,
                'transaction_id' =>
                str()->random(8),
                'note' => $faker->sentence(5),
            ]);
        }
    }
}
