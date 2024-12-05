<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\BatchDay;
use App\Models\Level;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class BatchController extends Controller
{
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
                $query = Batch::latest();
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
                ->editColumn('class', function ($row) {
                    return $row->level->name ?? '--';
                })
                ->editColumn('tuition_fee', function ($row) {
                    return number_format($row->tuition_fee, 2);
                })
                ->rawColumns(['action', 'weekly_classes', 'status'])
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
        $subjects = Subject::active()->latest()->get();
        $levels = Level::active()->latest()->get();

        return view('admin.batch.create', compact('teachers', 'subjects', 'levels'));
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
            'tuition_fee' => 'required|numeric',
            'days' => 'required',
            'level' => 'nullable|exists:levels,id'
        ]);

        try {
            $batch = Batch::create([
                'name' => $request->name,
                'tuition_fee' => $request->tuition_fee,
                'level_id' => $request->level
            ]);

            $days = json_decode($request->days);
            foreach ($days as $day) {
                BatchDay::create([
                    'batch_id' => $batch->id,
                    'day' => $day->day,
                    'start_time' => $day->start_time,
                    'end_time' => $day->end_time,
                    'user_id' => $day->teacher,
                    'subject_id' => $day->subject
                ]);
            }

            return response()->json([
                'status' => true,
                'message' => 'Batch added successfully.',
            ]);
        } catch (\Throwable $th) {
            Log::info($th->getMessage() . ' on line ' . $th->getLine() . ' in ' . $th->getFile());

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

        $batch = Batch::with(['level', 'batch_days'])->findOrFail($id);
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
        $subjects = Subject::active()->latest()->get();
        $levels = Level::active()->latest()->get();

        return view('admin.batch.edit', compact('batch', 'teachers', 'subjects', 'levels'));
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
            'tuition_fee' => 'required|numeric',
            'days' => 'required',
            'level' => 'nullable|exists:levels,id',
            'status' => 'nullable|in:0,1,2'
        ]);

        try {
            $batch = Batch::findOrFail($id);

            $batch->update([
                'name' => $request->name,
                'tuition_fee' => $request->tuition_fee,
                'level_id' => $request->level,
                'status' => $request->status ?? $batch->status
            ]);

            $days = json_decode($request->days, true);
            foreach ($days as $day) {
                $data = [
                    'day' => $day['day'],
                    'start_time' => $day['start_time'],
                    'end_time' => $day['end_time'],
                    'user_id' => $day['teacher'],
                    'subject_id' => $day['subject']
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
            Log::error($th->getMessage(), ['line' => $th->getLine(), 'file' => $th->getFile()]);

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
            Log::error($th->getMessage() . ' on line ' . $th->getLine() . ' in ' . $th->getFile());
            return false;
        }
    }
}
