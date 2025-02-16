<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\BatchDayDate;
use App\Models\Lead;
use App\Models\Payment;
use App\Models\Student;
use App\Models\Teacher;

class DashboardController extends Controller
{
    // function to show dashboard page
    public function index()
    {
        if (auth()->user()->user_type == 'teacher') {
            $user = Teacher::with(['user'])
                ->where('user_id', auth()->id())
                ->first();

            $dayIds = $user?->batch_days?->pluck('id') ?? [];
            $classSchedules = BatchDayDate::whereIn('batch_day_id', $dayIds)
                ->orderBy('date', 'asc')
                ->get();

            return view('teacher.dashboard', compact('user', 'classSchedules'));
        }

        $totalBatch = Batch::active()->count();
        $totalStudent = Student::active()->count();
        $totalPayment = Payment::sum('amount');
        $totalLead = Lead::count();

        return view('admin.dashboard', compact('totalBatch', 'totalStudent', 'totalPayment', 'totalLead'));
    }
}
