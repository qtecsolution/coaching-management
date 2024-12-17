<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\BatchDay;
use App\Models\ClassMaterial;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class ClassMaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (!auth()->user()->can('view_class_materials')) {
            abort(403, 'Unauthorized action.');
        }

        if ($request->ajax()) {
            if (auth()->user()->user_type == 'teacher') {
                $batchDayIds = BatchDay::where('user_id', auth()->id())->pluck('id');
                $query = ClassMaterial::whereIn('batch_day_id', $batchDayIds)->latest();
            } else {
                $query = ClassMaterial::latest();
            }

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('DT_RowIndex', '')
                ->addColumn('action', function ($row) {
                    return view('admin.class-material.action', compact('row'));
                })
                ->addColumn('batch', function ($row) {
                    return $row->batch_day->batch->name;
                })
                ->editColumn('url', function ($row) {
                    return '<a href="' . absolutePath($row->url) . '" target="_blank"><i class="bi bi-eye"></i> View</a>';
                })
                ->rawColumns(['action', 'batch', 'subject', 'DT_RowIndex', 'url'])
                ->make(true);
        }

        return view('admin.class-material.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!auth()->user()->can('create_class_material')) {
            abort(403, 'Unauthorized action.');
        }

        if (auth()->user()->user_type == 'teacher') {
            $batchDayIds = BatchDay::where('user_id', auth()->id())->pluck('batch_id');
            $batches = Batch::whereIn('id', $batchDayIds)->latest()->get();
        } else {
            $batches = Batch::active()->latest()->get();
        }

        return view('admin.class-material.create', compact('batches'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!auth()->user()->can('create_class_material')) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'title' => 'required',
            'file' => 'nullable|mimes:pdf,doc,docx,ppt,pptx,jpg,jpeg,png|max:2048|file|required_if:url,null',
            'url' => 'nullable|url|required_if:file,null',
            'batch' => 'required|exists:batches,id',
            'subject' => 'required|integer',
        ]);

        if ($request->hasFile('file')) {
            $url = fileUpload($request->file('file'), 'media/class_materials');
            $isFile = true;
        } else {
            $url = $request->url;
            $isFile = false;
        }

        // NOTE: request subject is the batch day ID.

        ClassMaterial::create([
            'batch_day_id' => $request->subject,
            'title' => $request->title,
            'url' => $url,
            'is_file' => $isFile
        ]);

        alert('Success!', 'Class material added successfully.', 'success');
        return to_route('admin.class-materials.index', ['batch' => $request->batch]);
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
        if (!auth()->user()->can('update_class_material')) {
            abort(403, 'Unauthorized action.');
        }

        $classMaterial = ClassMaterial::findOrFail($id);
        $batches = Batch::active()->latest()->get();

        return view('admin.class-material.edit', compact('classMaterial', 'batches'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if (!auth()->user()->can('update_class_material')) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'title' => 'required',
            'file' => 'nullable|mimes:pdf,doc,docx,ppt,pptx,jpg,jpeg,png|max:2048|file|required_if:url,null',
            'url' => 'nullable|url|required_if:file,null',
            'batch' => 'required|exists:batches,id',
            'subject' => 'required|integer',
        ]);

        $classMaterial = ClassMaterial::findOrFail($id);

        if ($request->hasFile('file')) {
            $classMaterial->is_file && fileRemove($classMaterial->url);
            $url = fileUpload($request->file('file'), 'media/class_materials');
            $isFile = true;
        } else {
            $url = $request->url;
            $isFile = false;
        }

        // NOTE: request subject is the batch day ID.

        $classMaterial->update([
            'batch_day_id' => $request->subject,
            'title' => $request->title,
            'url' => $url,
            'is_file' => $isFile
        ]);

        alert('Success!', 'Class material updated successfully.', 'success');
        return to_route('admin.class-materials.index', ['batch' => $request->batch]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            if (!auth()->user()->can('delete_class_material')) {
                abort(403, 'Unauthorized action.');
            }

            $classMaterial = ClassMaterial::findOrFail($id);

            if ($classMaterial->is_file) {
                fileRemove($classMaterial->url);
            }

            $classMaterial->delete();

            return true;
        } catch (\Throwable $th) {
            Log::error($th->getMessage() . ' on line ' . $th->getLine() . ' in file ' . $th->getFile());

            return false;
        }
    }

    public function getDays(Request $request)
    {
        $request->validate([
            'batch' => 'required|exists:batches,id'
        ]);

        $batch = Batch::with('batch_days')->findOrFail($request->batch);
        
        return response()->json([
            'data' => $batch->batch_days
        ]);
    }
}
