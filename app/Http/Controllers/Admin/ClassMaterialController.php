<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\BatchDay;
use App\Models\ClassMaterial;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Traits\ExceptionHandler;
use Exception;

class ClassMaterialController extends Controller
{
    use ExceptionHandler;

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
                $batchDayIds = BatchDay::where('user_id', auth()->id())->pluck('batch_id');
                $query = ClassMaterial::whereIn('batch_id', $batchDayIds);
            } else {
                $query = ClassMaterial::query();
            }

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('DT_RowIndex', '')
                ->addColumn('action', function ($row) {
                    return view('admin.class-material.action', compact('row'));
                })
                ->addColumn('batch', function ($row) {
                    return $row->batch->title;
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
            'file' => 'mimes:pdf,doc,docx,ppt,pptx,jpg,jpeg,png|max:2048|file|required_if:url,null',
            'url' => 'url|required_if:file,null',
            'batch' => 'required|exists:batches,id'
        ]);

        try {
            if ($request->hasFile('file')) {
                $url = fileUpload($request->file('file'), 'media/class_materials');
                $isFile = true;
            } else {
                $url = $request->url;
                $isFile = false;
            }

            ClassMaterial::create([
                'batch_id' => $request->batch,
                'title' => $request->title,
                'url' => $url,
                'is_file' => $isFile
            ]);

            $this->getAlert('success', 'Class material added successfully.');
            return to_route('admin.class-materials.index', ['batch' => $request->batch]);
        } catch (\Throwable $th) {
            $this->logException($th);
            return back()->withInput();
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
            'file' => 'mimes:pdf,doc,docx,ppt,pptx,jpg,jpeg,png|max:2048|file|required_if:url,null',
            'url' => 'url|required_if:file,null',
            'batch' => 'required|exists:batches,id'
        ]);

        try {
            $classMaterial = ClassMaterial::findOrFail($id);

            if ($request->hasFile('file')) {
                $classMaterial->is_file && fileRemove($classMaterial->url);
                $url = fileUpload($request->file('file'), 'media/class_materials');
                $isFile = true;
            } else {
                $url = $request->url;
                $isFile = false;
            }

            $classMaterial->update([
                'batch_id' => $request->batch,
                'title' => $request->title,
                'url' => $url,
                'is_file' => $isFile
            ]);

            $this->getAlert('success', 'Class material updated successfully.');
            return to_route('admin.class-materials.index', ['batch' => $request->batch]);
        } catch (\Throwable $th) {
            $this->logException($th);
            return back()->withInput();
        }
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
            $this->logException($th);
            throw new Exception($th->getMessage());
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
