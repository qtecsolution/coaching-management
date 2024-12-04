<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function attendance($batchDayId)
    {
        return view('admin.attendance.attendance');
    }
}
