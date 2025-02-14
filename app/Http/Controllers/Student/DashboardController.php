<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\BatchDayDate;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    // function to show dashboard page
    public function index()
    {
        $user = User::find(auth()->id());
        $dayIds = $user?->student?->currentBatch?->batch?->batch_days?->pluck('id') ?? [];
        $classSchedules = BatchDayDate::whereIn('batch_day_id', $dayIds)
            ->orderBy('date', 'asc')
            ->get();

        return view('student.dashboard', compact('user', 'classSchedules'));
    }
}
