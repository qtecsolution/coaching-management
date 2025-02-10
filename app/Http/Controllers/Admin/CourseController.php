<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Traits\ExceptionHandler;
use Exception;
use Illuminate\Support\Str;

class CourseController extends Controller
{
    use ExceptionHandler;

    public function index()
    {
        if (request()->ajax()) {
            return DataTables::of(Course::query())
                ->addIndexColumn()
                ->addColumn('DT_RowIndex', '')
                ->addColumn('action', function ($row) {
                    return view('admin.course.action', compact('row'));
                })
                ->editColumn('status', function ($row) {
                    if ($row->status == 1) {
                        return '<span class="badge bg-success">Active</span>';
                    } else {
                        return '<span class="badge bg-danger">Inactive</span>';
                    }
                })
                ->editColumn('image', function ($row) {
                    return '<img src="' . absolutePath($row->image) . '" width="80" alt="' . $row->title . '" class="rounded">';
                })
                ->rawColumns(['action', 'status', 'image'])
                ->make(true);
        }

        return view('admin.course.index');
    }

    public function create()
    {
        return view('admin.course.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        try {
            $image = null;
            if ($request->hasFile('image')) {
                $image = fileUpload($request->file('image'), 'media/courses');
            }

            $slug = Str::slug($request->title);
            if (Course::where('slug', $slug)->exists()) {
                $slug .= '-' . time();
            }

            Course::create([
                'title' => $request->title,
                'slug' => $slug,
                'description' => $request->description,
                'image' => $image,
            ]);

            $this->getAlert('success', 'Course added successfully.');
            return to_route('admin.courses.index');
        } catch (\Throwable $th) {
            $this->logException($th);
            $this->getAlert('error', 'Something went wrong.');

            return back()->withInput($request->all());
        }
    }

    public function edit($id)
    {
        $course = Course::findOrFail($id);
        return view('admin.course.edit', compact('course'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'status' => 'nullable|in:0,1',
        ]);

        try {
            $course = Course::findOrFail($id);

            $image = $course->image;
            if ($request->hasFile('image')) {
                fileRemove($course->image);
                $image = fileUpload($request->file('image'), 'media/courses');
            }

            $slug = Str::slug($request->title);
            if (Course::where('slug', $slug)->exists()) {
                $slug .= '-' . time();
            }

            $course->update([
                'title' => $request->title,
                'slug' => $slug,
                'description' => $request->description,
                'image' => $image,
                'status' => $request->status ? 1 : 0,
            ]);

            $this->getAlert('success', 'Course updated successfully.');
            return to_route('admin.courses.index');
        } catch (\Throwable $th) {
            $this->logException($th);
            $this->getAlert('error', 'Something went wrong.');

            return back()->withInput($request->all());
        }
    }

    public function destroy($id)
    {
        try {
            $course = Course::findOrFail($id);

            fileRemove($course->image);
            $course->delete();
            
            return true;
        } catch (\Throwable $th) {
            $this->logException($th);
            throw new Exception('Something went wrong.');
        }
    }
}
