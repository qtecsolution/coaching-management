<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\ClassMaterial;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ClassMaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        
        if ($request->ajax()) {
            $user = User::with(['student.currentbatch'])->find(auth()->id());
            $batchDayIds = $user?->student?->currentbatch?->batch?->batch_days->pluck('id') ?? [];
            $query = ClassMaterial::whereIn('batch_day_id', $batchDayIds)->latest();

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('DT_RowIndex', '')
                ->addColumn('batch', function ($row) {
                    return $row->batch_day->batch->name;
                })
                ->editColumn('url', function ($row) {
                    return '<a href="' . absolutePath($row->url) . '" target="_blank"><i class="bi bi-eye"></i> View</a>';
                })
                ->rawColumns(['batch', 'subject', 'DT_RowIndex', 'url'])
                ->make(true);
        }

        return view('student.class-material.index');
    }
}
