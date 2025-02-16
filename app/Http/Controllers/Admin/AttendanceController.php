<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AttendanceRecord;
use App\Models\Batch;
use App\Models\BatchDay;
use App\Models\BatchDayDate;
use App\Models\StudentBatch;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!auth()->user()->can('view_attendance')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            if (auth()->user()->user_type == 'teacher') {
                $classSchedule = BatchDayDate::query()
                    ->with(['batchDay.batch', 'attendance']) // Eager load relationships
                    ->whereHas('batchDay', function ($q) {
                        $q->where('user_id', auth()->id());
                    })
                    ->when(request()->date, function ($query) {
                        return $query->where('date', request()->date);
                    })
                    ->when(request()->batch_id, function ($query) {
                        $batch = Batch::findOrFail(request()->batch_id);
                        return $query->whereIn('batch_day_id', $batch->batch_days->pluck('id'));
                    });
            } else {
                $classSchedule = BatchDayDate::query()
                    ->with(['batchDay.batch', 'attendance'])
                    ->when(auth()->user()->user_type == 'teacher', function ($query) {
                        return $query->whereHas('batchDay', function ($q) {
                            $q->where('user_id', auth()->id());
                        });
                    })
                    ->when(request()->date, function ($query) {
                        return $query->where('date', request()->date);
                    })
                    ->when(request()->batch_id, function ($query) {
                        $batch = Batch::findOrFail(request()->batch_id);
                        return $query->whereIn('batch_day_id', $batch->batchDays->pluck('id'));
                    });
            }

            return DataTables::of($classSchedule)
                ->addIndexColumn()
                ->addColumn('batch_name', function ($row) {
                    return $row->batchDay->batch->title;
                })
                ->addColumn('total_student', function ($row) {
                    return number_format($row->batchDay->batch->total_students);
                })
                ->addColumn('total_absent', function ($row) {
                    return number_format($row->attendance->where('status', 0)->count());
                })
                ->addColumn('total_present', function ($row) {
                    return number_format($row->attendance->where('status', 1)->count());
                })
                ->addColumn('total_late', function ($row) {
                    return number_format($row->attendance->where('status', 2)->count());
                })
                ->addColumn('action', function ($row) {
                    return view('admin.attendance.action', compact('row'));
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        if (auth()->user()->user_type == 'teacher') {
            $batchDayIds = BatchDay::where('user_id', auth()->id())->pluck('batch_id');
            $batches = Batch::active()->whereIn('id', $batchDayIds)->get();
        } else {
            $batches = Batch::all();
        }

        return view('admin.attendance.index', compact('batches'));
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
        $records = BatchDayDate::with('attendance')->findOrFail($id);
        return view('admin.attendance.show', compact('records'));
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
