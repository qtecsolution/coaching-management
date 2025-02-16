<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\BatchDayDate;
use App\Models\Lead;
use App\Models\Payment;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    // function to show dashboard page
    public function index(Request $request)
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        // If start and end dates are provided, filter by date range
        $batchQuery = Batch::query();
        if ($startDate && $endDate) {
            $batchQuery->whereBetween('created_at', [$startDate, $endDate]);
        }
        $totalBatch = $batchQuery->count();

        $studentQuery = Student::query();
        if ($startDate && $endDate) {
            $studentQuery->whereBetween('created_at', [$startDate, $endDate]);
        }
        $totalStudent = $studentQuery->count();

        $leadQuery = Lead::query();
        if ($startDate && $endDate) {
            $leadQuery->whereBetween('created_at', [$startDate, $endDate]);
        }
        $totalLead = $leadQuery->count();

        $paymentQuery = Payment::query();
        if ($startDate && $endDate) {
            $paymentQuery->whereBetween('created_at', [$startDate, $endDate]);
        }
        $totalPayment = $paymentQuery->count();

        // Prepare payment data for graph
        $paymentData = Payment::select([
            \DB::raw('DATE(created_at) as date'),
            \DB::raw('COUNT(*) as total_payments'),
            \DB::raw('SUM(amount) as total_amount')
        ])
        ->when($startDate && $endDate, function($query) use ($startDate, $endDate) {
            return $query->whereBetween('created_at', [$startDate, $endDate]);
        })
        ->groupBy(\DB::raw('DATE(created_at)'))  // Changed this line
        ->orderBy('date')
        ->get();

        return view('admin.dashboard', compact(
            'totalBatch',
            'totalStudent',
            'totalLead',
            'totalPayment',
            'paymentData'
        ));
    }
}
