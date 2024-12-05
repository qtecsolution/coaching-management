<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\PaymentReport;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    // function to show dashboard page
    public function index()
    {
        if (auth()->user()->user_type == 'teacher') {
            $user = Teacher::with(['user', 'batch_days.batch'])
                ->whereHas('batch_days.batch', function ($query) {
                    $query->active();
                })
                ->where('user_id', auth()->id())->first();
            return view('teacher.dashboard', compact('user'));
        }

        // Get all payments
        $payments = PaymentReport::get();
        $data['total_student'] = Student::count();
        $data['total_estimated_collection'] = number_format($payments->sum('estimated_collection_amount'), 2);
        $data['total_collection'] = number_format($payments->sum('collected_amount'), 2);
        $data['total_due'] = number_format(($payments->sum('estimated_collection_amount') - $payments->sum('collected_amount')), 2);

        // Get the current year
        $currentYear = now()->year;

        // Generate all months of the current year
        $months = collect(range(1, 12))->map(fn($month) => sprintf('%d-%02d', $currentYear, $month));

        // Fetch the actual data from the database
        $monthlyCollections = PaymentReport::whereBetween('month', [
            $currentYear . '-01',
            $currentYear . '-12',
        ])->get()
            ->keyBy('month');

        $areaChartIsBlank = true;
        $message = '';
        // Prepare the data for the chart
        $chartData = $months->mapWithKeys(function ($month) use ($monthlyCollections, &$areaChartIsBlank) {
            if($monthlyCollections->get($month)?->collected_amount > 0){
                $areaChartIsBlank = false;
            }
            return [
                $month => [
                    'month' => $month,
                    'collected_amount' => $monthlyCollections->get($month)?->collected_amount ?? 0,
                ]
            ];
        });
        $amounts = $chartData->pluck('collected_amount');
        if($areaChartIsBlank){
            $message = 'No data available';
        }
        return view('admin.dashboard', compact('data', 'amounts', 'message'));
    }
}
