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
            $query = ClassMaterial::where('batch_id', $user?->student?->currentBatch->batch_id);

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('DT_RowIndex', '')
                ->addColumn('batch', function ($row) {
                    return $row->batch->title;
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
