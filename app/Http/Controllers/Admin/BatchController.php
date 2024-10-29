<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\BatchDay;
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
        if (request()->ajax()) {
            return DataTables::of(Batch::orderBy('id', 'desc'))
                ->addIndexColumn()
                ->addColumn('DT_RowIndex', '')
                ->addColumn('action', function ($row) {
                    return view('admin.batch.action', compact('row'));
                })
                ->addColumn('weekly_classes', function ($row) {
                    return $row->batch_days->count() . ' days';
                })
                ->editColumn('status', function ($row) {
                    if ($row->status == 1) {
                        return '<span class="badge bg-success">Active</span>';
                    } else {
                        return '<span class="badge bg-danger">Inactive</span>';
                    }
                })
                ->editColumn('class', function ($row) {
                    return $row->class ?? 'N/A';
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
        $teachers = User::where('user_type', 'teacher')->orderBy('id', 'desc')->get();
        return view('admin.batch.create', compact('teachers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'subject' => 'required',
            'days' => 'required'
        ]);

        try {
            $batch = Batch::create([
                'name' => $request->name,
                'subject' => $request->subject,
                'class' => $request->class
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
        $batch = Batch::with('batch_days')->findOrFail($id);
        return view('admin.batch.show', compact('batch'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $batch = Batch::with('batch_days')->find($id);
        $teachers = User::where('user_type', 'teacher')->orderBy('id', 'desc')->get();

        return view('admin.batch.edit', compact('batch', 'teachers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'subject' => 'required',
            'days' => 'required'
        ]);

        try {
            $batch = Batch::findOrFail($id);

            $batch->update([
                'name' => $request->name,
                'subject' => $request->subject,
                'class' => $request->class
            ]);

            BatchDay::where('batch_id', $batch->id)->delete();

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
                'message' => 'Batch updated successfully.',
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
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $batch = Batch::find($id);
            $batch->delete();

            return true;
        } catch (\Throwable $th) {
            Log::error($th->getMessage() . ' on line ' . $th->getLine() . ' in ' . $th->getFile());
            return false;
        }
    }
}
