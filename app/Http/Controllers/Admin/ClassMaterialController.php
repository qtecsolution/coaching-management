<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\ClassMaterial;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

use function App\Http\Helpers\fileRemove;
use function App\Http\Helpers\fileUpload;

class ClassMaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of(ClassMaterial::latest())
                ->addIndexColumn()
                ->addColumn('DT_RowIndex', '')
                ->addColumn('action', function ($row) {
                    return view('admin.class-material.action', compact('row'));
                })
                ->addColumn('batch', function ($row) {
                    return $row->batch->name;
                })
                ->addColumn('subject', function ($row) {
                    return $row->subject->name;
                })
                ->rawColumns(['action', 'batch', 'subject', 'DT_RowIndex'])
                ->make(true);
        }

        return view('admin.class-material.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $batches = Batch::active()->latest()->get();
        $subjects = Subject::active()->latest()->get();

        return view('admin.class-material.create', compact('batches', 'subjects'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'file' => 'nullable|mimes:pdf,doc,docx,ppt,pptx,jpg,jpeg,png|max:2048|file|required_if:url,null',
            'url' => 'nullable|url|required_if:file,null',
            'batch' => 'required|exists:batches,id',
        ]);

        if ($request->hasFile('file')) {
            $url = fileUpload($request->file('file'), 'class_material');
        } else {
            $url = $request->url;
        }

        ClassMaterial::create([
            'batch_id' => $request->batch,
            'title' => $request->title,
            'url' => $url,
        ]);

        alert('Yahoo!', 'Class material added successfully.', 'success');
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
        $classMaterial = ClassMaterial::findOrFail($id);
        return view('admin.class-material.edit', compact('classMaterial'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'title' => 'required',
            'file' => 'nullable|mimes:pdf,doc,docx,ppt,pptx,jpg,jpeg,png|max:2048|file|required_if:url,null',
            'url' => 'nullable|url|required_if:file,null',
            'batch' => 'required|exists:batches,id',
        ]);

        $classMaterial = ClassMaterial::findOrFail($id);

        if ($request->hasFile('file')) {
            fileRemove($classMaterial->url);
            $url = fileUpload($request->file('file'), 'class_material');
        } else {
            $url = $request->url;
        }

        $classMaterial->update([
            'batch_id' => $request->batch,
            'title' => $request->title,
            'url' => $url,
        ]);

        alert('Yahoo!', 'Class material updated successfully.', 'success');
        return to_route('admin.class-materials.index', ['batch' => $request->batch]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $classMaterial = ClassMaterial::findOrFail($id);
            $classMaterial->delete();

            return true;
        } catch (\Throwable $th) {
            Log::error($th->getMessage() . ' on line ' . $th->getLine() . ' in file ' . $th->getFile());

            return false;
        }
    }
}
