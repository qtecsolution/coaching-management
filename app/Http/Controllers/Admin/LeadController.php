<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\Level;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class LeadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()->ajax()) {
            return DataTables::of(Lead::latest())
                ->addIndexColumn()
                ->addColumn('DT_RowIndex', '')
                ->addColumn('action', function ($row) {
                    return view('admin.lead.action', compact('row'));
                })
                ->editColumn('status', function ($row) {
                    if ($row->status == 1) {
                        return '<span class="badge bg-success">Done</span>';
                    } else {
                        return '<span class="badge bg-danger">Pending</span>';
                    }
                })
                ->editColumn('email', function ($row) {
                    return $row->email ? $row->email : '--';
                })
                ->editColumn('school_name', function ($row) {
                    return $row->school_name ? $row->school_name : '--';
                })
                ->editColumn('class', function ($row) {
                    return $row->class ? $row->class : '--';
                })
                ->editColumn('note', function ($row) {
                    return $row->note ? $row->note : '--';
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }

        return view('admin.lead.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $levels = Level::active()->latest()->get();
        return view('admin.lead.create', compact('levels'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'phone' => 'required|unique:leads,phone',
            'email' => 'nullable|unique:leads,email|email'
        ]);

        try {
            Lead::create([
                'name' => $request->name,
                'phone' => $request->phone,
                'email' => $request->email,
                'class' => $request->class,
                'school_name' => $request->school_name,
                'note' => $request->note
            ]);
    
            alert('Yahoo!', 'Lead added successfully.', 'success');
            return to_route('admin.leads.index');
        } catch (\Throwable $th) {
            Log::error($th->getMessage() . ' on line ' . $th->getLine() . ' in file ' . $th->getFile());

            alert('Oops!', $th->getMessage(), 'error');
            return back();
        }
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
        $lead = Lead::findOrFail($id);
        $levels = Level::active()->latest()->get();

        return view('admin.lead.edit', compact('lead', 'levels'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required',
            'phone' => 'required|unique:leads,phone,' . $id,
            'email' => 'nullable|email|unique:leads,email,' . $id
        ]);

        try {
            $lead = Lead::findOrFail($id);

            $lead->update([
                'name' => $request->name,
                'phone' => $request->phone,
                'email' => $request->email,
                'class' => $request->class,
                'school_name' => $request->school_name,
                'note' => $request->note
            ]);
    
            alert('Yahoo!', 'Lead updated successfully.', 'success');
            return to_route('admin.leads.index');
        } catch (\Throwable $th) {
            Log::error($th->getMessage() . ' on line ' . $th->getLine() . ' in file ' . $th->getFile());

            alert('Oops!', $th->getMessage(), 'error');
            return back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $lead = Lead::findOrFail($id);
            $lead->delete();
            return true;
        } catch (\Throwable $th) {
            Log::error($th->getMessage() . ' on line ' . $th->getLine() . ' in file ' . $th->getFile());
            return false;
        }
    }
}
