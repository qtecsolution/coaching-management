<?php

use App\Console\Commands\GenerateMonthlyPayments;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Schedule::command(GenerateMonthlyPayments::class)->monthlyOn(1, '00:00');