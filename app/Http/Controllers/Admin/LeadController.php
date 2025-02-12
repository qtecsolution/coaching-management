<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\LeadDynamicField;
use App\Models\Level;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;
use App\Traits\ExceptionHandler;

class LeadController extends Controller
{
    use ExceptionHandler;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!auth()->user()->can('view_leads')) {
            abort(403, 'Unauthorized action.');
        }

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
        if (!auth()->user()->can('create_lead')) {
            abort(403, 'Unauthorized action.');
        }

        return view('admin.lead.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!auth()->user()->can('create_lead')) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'name' => 'required',
            'phone' => 'required|unique:leads,phone',
            'email' => 'nullable|unique:leads,email|email'
        ]);

        try {
            $lead = Lead::create([
                'name' => $request->name,
                'phone' => $request->phone,
                'email' => $request->email,
                'note' => $request->note
            ]);

            // Filter fields
            $fields = [];
            foreach ($request->field_name as $index => $name) {
                $fields[] = [
                    'lead_id' => $lead->id,
                    'name' => $name,
                    'value' => $request->field_value[$index],
                ];
            }

            // Save fields
            foreach ($fields as $field) {
                LeadDynamicField::create($field);
            }
    
            $this->getAlert('success', 'Lead created successfully.');
            return to_route('admin.leads.index');
        } catch (\Throwable $th) {
            $this->logException($th);
            return back()->withInput($request->all());
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
        if (!auth()->user()->can('update_lead')) {
            abort(403, 'Unauthorized action.');
        }

        $lead = Lead::findOrFail($id);
        return view('admin.lead.edit', compact('lead'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if (!auth()->user()->can('update_lead')) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'name' => 'required',
            'phone' => 'required|unique:leads,phone,' . $id,
            'email' => 'nullable|email|unique:leads,email,' . $id,
            'status' => 'required|integer'
        ]);

        try {
            $lead = Lead::findOrFail($id);

            $lead->update([
                'name' => $request->name,
                'phone' => $request->phone,
                'email' => $request->email,
                'note' => $request->note,
                'status' => $request->status
            ]);

            // Fetch existing fields
            $existingFields = $lead->dynamicFields;

            // Update or create new fields
            foreach ($request->field_name as $index => $name) {
                $field = $existingFields[$index] ?? new LeadDynamicField();
                $field->lead_id = $lead->id;
                $field->name = $name;
                $field->value = $request->field_value[$index];
                $field->save();
            }

            // Remove extra fields (if any)
            if (count($request->field_name) < $existingFields->count()) {
                $extraFields = $existingFields->slice(count($request->field_name));
                foreach ($extraFields as $extraField) {
                    $extraField->delete();
                }
            }
    
            $this->getAlert('success', 'Lead updated successfully.');
            return to_route('admin.leads.index');
        } catch (\Throwable $th) {
            $this->logException($th);
            return back()->withInput($request->all());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            if (!auth()->user()->can('delete_lead')) {
                abort(403, 'Unauthorized action.');
            }

            $lead = Lead::findOrFail($id);
            $lead->delete();

            return true;
        } catch (\Throwable $th) {
            $this->logException($th);
            return false;
        }
    }
}
