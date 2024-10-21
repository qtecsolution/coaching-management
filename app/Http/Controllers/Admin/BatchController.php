<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\BatchDay;
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
                ->addColumn('teacher', function ($row) {
                    return auth()->user()->name;
                })
                ->rawColumns(['action', 'teacher'])
                ->make(true);
        }

        return view('admin.batch.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.batch.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'subject' => 'required',
            'days' => 'required|array',
            'times' => 'required|array',
        ]);

        $batch = Batch::create([
            'name' => $request->name,
            'subject' => $request->subject,
            'class' => $request->class
        ]);

        foreach ($request->days as $day) {
            BatchDay::create([
                'batch_id' => $batch->id,
                'day' => $day,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time
            ]);
        }

        toast('Batch added successfully.', 'success');
        return to_route('admin.batches.index');
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
        $batch = Batch::with('batch_days')->find($id);
        return view('admin.batch.edit', compact('batch'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required',
            'subject' => 'required',
            'days' => 'required|array',
            'times' => 'required|array',
        ]);

        $batch = Batch::with('batch_days')->find($id);

        $batch->update([
            'name' => $request->name,
            'subject' => $request->subject,
            'class' => $request->class
        ]);

        foreach ($request->days as $key => $day) {
            $checkExist = BatchDay::where('batch_id', $batch->id)->where('day', $day)
                ->where('start_time', $request->times[$key]['start_time'])
                ->where('end_time', $request->times[$key]['end_time'])
                ->first();

            if (!$checkExist) {
                BatchDay::create([
                    'batch_id' => $batch->id,
                    'day' => $day,
                    'start_time' => $request->times[$key]['start_time'],
                    'end_time' => $request->times[$key]['end_time']
                ]);
            } else {
                $checkExist->update([
                    'day' => $day,
                    'start_time' => $request->times[$key]['start_time'],
                    'end_time' => $request->times[$key]['end_time']
                ]);
            }
        }

        toast('Batch updated successfully.', 'success');
        return to_route('admin.batches.index');
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
            Log::error($th->getMessage());
            return false;
        }
    }
}
