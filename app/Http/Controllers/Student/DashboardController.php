<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    // function to show dashboard page
    public function index()
    {
        $user = User::with(['student', 'student.batch', 'student.batch.batch_days'])->find(auth()->id());

        return view('user.dashboard', compact('user'));
    }
}
