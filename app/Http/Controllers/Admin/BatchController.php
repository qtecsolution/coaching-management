<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\BatchDay;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Traits\ExceptionHandler;
use Exception;

class BatchController extends Controller
{
    use ExceptionHandler;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!auth()->user()->can('view_batches')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            if (auth()->user()->user_type == 'teacher') {
                $batchIds = BatchDay::where('user_id', auth()->id())->pluck('batch_id');
                $query = Batch::whereIn('id', $batchIds);
            } else {
                $query = Batch::query();
            }

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('DT_RowIndex', '')
                ->addColumn('action', function ($row) {
                    return view('admin.batch.action', compact('row'));
                })
                ->addColumn('weekly_classes', function ($row) {
                    return $row->batch_days->count() . ' days';
                })
                ->editColumn('status', function ($row) {
                    $status = Batch::$statusList[$row->status];
                    return '<span class="badge bg-success">' . $status . '</span>';
                })
                ->addColumn('course', function ($row) {
                    return $row->course->title;
                })
                ->rawColumns(['action', 'weekly_classes', 'status', 'course'])
                ->make(true);
        }

        return view('admin.batch.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!auth()->user()->can('create_batch')) {
            abort(403, 'Unauthorized action.');
        }

        $teachers = User::active()->where('user_type', 'teacher')->latest()->get();
        $courses = Course::active()->latest()->get();

        return view('admin.batch.create', compact('teachers', 'courses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!auth()->user()->can('create_batch')) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'name' => 'required',
            'course' => 'required|exists:courses,id',
            'days' => 'required'
        ]);

        try {
            $batch = Batch::create([
                'name' => $request->name,
                'course_id' => $request->course,
                'created_by' => auth()->id(),
                'updated_by' => auth()->id()
            ]);

            $days = json_decode($request->days);
            foreach ($days as $day) {
                BatchDay::create([
                    'batch_id' => $batch->id,
                    'day' => $day->day,
                    'start_time' => $day->start_time,
                    'end_time' => $day->end_time,
                    'user_id' => $day->teacher
                ]);
            }

            return response()->json([
                'status' => true,
                'message' => 'Batch added successfully.',
            ]);
        } catch (\Throwable $th) {
            $this->logException($th);

            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        if (!auth()->user()->can('view_batches')) {
            abort(403, 'Unauthorized action.');
        }

        $batch = Batch::with('batch_days')->findOrFail($id);
        return view('admin.batch.show', compact('batch'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        if (!auth()->user()->can('update_batch')) {
            abort(403, 'Unauthorized action.');
        }

        $batch = Batch::with('batch_days')->find($id);
        $teachers = User::active()->where('user_type', 'teacher')->latest()->get();
        $courses = Course::active()->latest()->get();

        return view('admin.batch.edit', compact('batch', 'teachers', 'courses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        if (!auth()->user()->can('update_batch')) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'name' => 'required',
            'course' => 'required|exists:courses,id',
            'days' => 'required',
            'status' => 'nullable|in:0,1,2'
        ]);

        try {
            $batch = Batch::findOrFail($id);

            $batch->update([
                'name' => $request->name,
                'course_id' => $request->course,
                'status' => $request->status ?? $batch->status,
                'updated_by' => auth()->id()
            ]);

            $days = json_decode($request->days, true);
            foreach ($days as $day) {
                $data = [
                    'day' => $day['day'],
                    'start_time' => $day['start_time'],
                    'end_time' => $day['end_time'],
                    'user_id' => $day['teacher']
                ];

                isset($day['id'])
                    ? BatchDay::findOrFail($day['id'])->update($data)
                    : BatchDay::create(array_merge($data, ['batch_id' => $batch->id]));
            }

            return response()->json([
                'status' => true,
                'message' => 'Batch updated successfully.',
            ]);
        } catch (\Throwable $th) {
            $this->logException($th);

            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            if (!auth()->user()->can('delete_batch')) {
                abort(403, 'Unauthorized action.');
            }

            $batch = Batch::find($id);

            BatchDay::where('batch_id', $batch->id)->delete();
            $batch->delete();

            return true;
        } catch (\Throwable $th) {
            $this->logException($th);
            throw new Exception($th->getMessage());
        }
    }
}
