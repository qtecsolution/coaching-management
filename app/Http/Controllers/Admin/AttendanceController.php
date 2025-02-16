<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AttendanceRecord;
use App\Models\BatchDayDate;
use App\Models\StudentBatch;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $record = AttendanceRecord::where('batch_day_date_id', $request->batch_day_date_id)
            ->where('student_id', $request->student_id)
            ->first();

        if (!$record) {
            return response()->json([
                'success' => false,
                'message' => 'Attendance record not found'
            ], 500);
        }

        $record->update([
            'status' => $request->status
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Attendance updated successfully',
            'attendance' => [
                'total_absent' => $record->student->currentBatch->total_absent(),
                'total_present' => $record->student->currentBatch->total_present(),
                'total_late' => $record->student->currentBatch->total_late(),
            ]
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $classSchedule = BatchDayDate::findOrFail($id);
        $students = StudentBatch::where('batch_id', $classSchedule->batchDay->batch_id)->get();
        foreach ($students as $student) {
            if (!AttendanceRecord::where('batch_day_date_id', $classSchedule->id)->where('student_id', $student->student_id)->exists()) {
                AttendanceRecord::create([
                    'batch_day_date_id' => $classSchedule->id,
                    'student_id' => $student->student_id
                ]);
            }
        }

        $records = $classSchedule->attendance;
        return view('admin.attendance.attendance', compact('classSchedule', 'records'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
