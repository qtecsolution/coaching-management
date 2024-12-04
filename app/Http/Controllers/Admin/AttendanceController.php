<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Batch;
use App\Models\BatchDay;
use App\Models\StudentBatch;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function attendance($batchDayId)
    {
        // Retrieve the batch day with its associated batch
        $batchDay = BatchDay::with('batch')->find($batchDayId);

        // Abort with a 404 if the batch day is not found
        if (!$batchDay) {
            abort(404);
        }

        // Get the current day name and time
        $currentDayName = now()->format('l');
        $currentTime = now()->format('H:i');

        $isAttendanceExist = Attendance::where('batch_day_id', $batchDayId)
            ->where('date', now()->toDateString())->exists();

        // Check if today is the batch day
        if ($batchDay->day_name === $currentDayName && !$isAttendanceExist) {
            // If the batch day matches the current day but the time is before the start time
            if ($currentTime < $batchDay->start_time) {
                alert('Oops!', 'Attendance has not started yet.', 'info');
                return back();
            }
        } else {
            // If the current day doesn't match the batch day, attendance cannot be started
            alert('Oops!', 'Attendance is not allowed for today.', 'error');
            return back();
        }

        // Retrieve the students associated with the batch
        $students = StudentBatch::with(['student', 'student.user'])
            ->where('batch_id', $batchDay->batch->id)
            ->get();

        // Create or update the attendance record for the batch day
        $attendance = Attendance::updateOrCreate(
            [
                'batch_day_id' => $batchDayId,
                'batch_id' => $batchDay->batch->id,
            ],
            [
                'date' => now()->toDateString(),
            ]
        );

        // Return the attendance view with the retrieved data
        return view('admin.attendance.attendance', compact('students', 'batchDay', 'attendance'));
    }
}
