<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\StudentBatch;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class StudentBatchController extends Controller
{
    public function index($batchId)
    {
        if (request()->ajax()) {
            return DataTables::of(StudentBatch::where('batch_id', $batchId)->latest())
                ->addIndexColumn()
                ->addColumn('DT_RowIndex', '')
                ->addColumn('reg_id', function ($row) {
                    return $row?->student->reg_id;
                })
                ->addColumn('name', function ($row) {
                    return $row?->student?->user->name;
                })
                ->addColumn('phone', function ($row) {
                    return $row?->student?->user->phone;
                })
                ->editColumn('emergency_contact', function ($row) {
                    $emergencyContact = json_decode($row?->student->emergency_contact, true);
                    return $emergencyContact['name'] . ' (' . $emergencyContact['phone'] . ')';
                })
                ->addColumn('address', function ($row) {
                    return $row?->student?->address;
                })
                ->addColumn('status', function ($row) {
                    if ($row?->student->status) {
                        return '<span class="badge bg-primary">Active</span>';
                    } else {
                        return '<span class="badge bg-danger">Inactive</span>';
                    }
                })
                ->addColumn('action', function ($row) {
                    return view('admin.student.action', compact('row'));
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        $batch = Batch::findOrFail($batchId);
        return view('admin.batch.students', compact('batch'));
    }
}
