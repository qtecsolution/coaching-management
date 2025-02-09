<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BatchDay;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    // function to show dashboard page
    public function index()
    {
        if (auth()->user()->user_type == 'teacher') {
            $user = Teacher::with(['user'])
                ->where('user_id', auth()->id())
                ->first();

            $batchDays = BatchDay::with('batch')
                ->where('user_id', auth()->id())
                ->whereHas('batch', function ($query) {
                    $query->active();
                })->get();

            return view('teacher.dashboard', compact('user', 'batchDays'));
        }

        return view('admin.dashboard');
    }
}
