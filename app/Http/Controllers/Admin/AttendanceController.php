<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\AttendanceRecord;
use App\Models\Batch;
use App\Models\BatchDay;
use App\Models\Student;
use App\Models\StudentBatch;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class AttendanceController extends Controller
{
    public function show($batchDayId)
    {
        if (!auth()->user()->can('view_attendance')) {
            abort(403, 'Unauthorized action.');
        }

        // Retrieve the batch day with its associated batch
        $batchDay = BatchDay::with('batch')->find($batchDayId);

        // Abort with a 404 if the batch day is not found
        if (!$batchDay) {
            abort(404, 'Batch Day not found');
        }

        // Get the current day name and time
        $currentDayName = now()->format('l');
        $currentTime = now()->format('H:i'); // 24-hour format for time comparison

        // Check if today is the batch day
        if ($batchDay->day_name === $currentDayName) {
            // Check if attendance has started
            if ($currentTime < $batchDay->start_time) {
                alert('Oops!', 'Attendance has not started yet.', 'info');
                return back();
            }
        } else {
            // If today is not the batch day, show an error message
            alert('Oops!', 'Today is not the batch day.', 'info');
            return back();
        }

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

        // Retrieve the students associated with the batch
        $students = StudentBatch::with(['student', 'student.user', 'attendance'])
            ->where('batch_id', $batchDay->batch->id)
            ->get();

        // Check if attendance records already exist
        if ($attendance->records()->count() <= 0) {
            foreach ($students as $student) {
                AttendanceRecord::create([
                    'student_id' => $student->student_id,
                    'attendance_id' => $attendance->id,
                ]);
            }
        }

        // Return the attendance view with the retrieved data
        return view('admin.attendance.attendance', compact('students', 'batchDay', 'attendance'));
    }

    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'reg_id' => 'required|exists:students,reg_id',
            'status' => 'required|in:0,1,2',
            'attendance_id' => 'required|exists:attendances,id'
        ]);

        try {
            $student = Student::where('reg_id', $request->reg_id)->first();
            $attendance = Attendance::findOrFail($request->attendance_id);

            // Check if the record already exists
            $isRecordExists = AttendanceRecord::where('student_id', $student->id)
                ->where('attendance_id', $attendance->id)
                ->exists();

            if ($isRecordExists) {
                if (!auth()->user()->can('update_attendance')) {
                    abort(403, 'Unauthorized action.');
                }
                
                $record = AttendanceRecord::where('student_id', $student->id)
                    ->where('attendance_id', $attendance->id)
                    ->update([
                        'status' => $request->status
                    ]);

                $msgText = 'Attendance record updated successfully';
            } else {
                if (!auth()->user()->can('create_attendance')) {
                    abort(403, 'Unauthorized action.');
                }

                // Create the attendance record
                $record = AttendanceRecord::create([
                    'attendance_id' => $attendance->id,
                    'student_id' => $student->id,
                    'status' => $request->status
                ]);

                $msgText = 'Attendance record created successfully';
            }


            // Return a success response
            return response()->json([
                'success' => true,
                'message' => $msgText,
                'data' => $record
            ]);
        } catch (\Throwable $th) {
            Log::error($th->getMessage() . ' on line ' . $th->getLine() . ' in file ' . $th->getFile());

            // Return an error response
            return response()->json([
                'success' => false,
                'message' => 'Failed to create attendance record',
                'error' => $th->getMessage()
            ]);
        }
    }

    public function index()
    {
        if (!auth()->user()->can('view_attendance')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            return DataTables::of(Attendance::latest())
                ->addIndexColumn()
                ->addColumn('DT_RowIndex', '')
                ->addColumn('action', function ($row) {
                    return view('admin.attendance.action', compact('row'));
                })
                ->addColumn('batch_name', function ($row) {
                    return $row?->batch->name;
                })
                ->addColumn('total_student', function ($row) {
                    return $row->records->count();
                })
                ->addColumn('total_absent', function ($row) {
                    return $row->records->where('status', 0)->count();
                })
                ->addColumn('total_present', function ($row) {
                    return $row->records->where('status', 1)->count();
                })
                ->addColumn('total_late', function ($row) {
                    return $row->records->where('status', 2)->count();
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.attendance.index');
    }

    public function list($id)
    {
        if (!auth()->user()->can('view_attendance')) {
            abort(403, 'Unauthorized action.');
        }

        $attendance = Attendance::findOrFail($id);

        // Return the attendance view with the retrieved data
        return view('admin.attendance.show', compact('attendance'));
    }
}
