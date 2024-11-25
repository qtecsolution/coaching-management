<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!auth()->user()->can('view_students')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            return DataTables::of(Student::latest())
                ->addIndexColumn()
                ->addColumn('name', function ($row) {
                    return $row->user->name;
                })
                ->addColumn('phone', function ($row) {
                    return $row->user->phone;
                })
                ->addColumn('email', function ($row) {
                    return $row->user->email;
                })
                ->addColumn('status', function ($row) {
                    if ($row->status) {
                        return '<span class="badge bg-primary">Active</span>';
                    } else {
                        return '<span class="badge bg-danger">Inactive</span>';
                    }
                })
                ->addColumn('batch', function ($row) {
                    return $row?->batch?->name ?? '--';
                })
                ->editColumn('emergency_contact', function ($row) {
                    $emergencyContact = json_decode($row->emergency_contact, true);
                    return $emergencyContact['name'] . ' (' . $emergencyContact['phone'] . ')';
                })
                ->addColumn('action', function ($row) {
                    return view('admin.student.action', compact('row'));
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view('admin.student.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!auth()->user()->can('create_student')) {
            abort(403, 'Unauthorized action.');
        }

        $batches = Batch::active()->latest()->get();

        return view('admin.student.create', compact('batches'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!auth()->user()->can('create_student')) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'name' => 'required',
            'phone' => 'required|unique:users,phone',
            'email' => 'nullable|unique:users,email',
            'password' => 'required',
            'date_of_birth' => 'required|date',
            'father_name' => 'required',
            'mother_name' => 'required',
            'address' => 'required',
            'contact_name' => 'required',
            'contact_phone' => 'required',
            'batch' => 'nullable|exists:batches,id'
        ]);

        try {
            $user = User::create([
                'name' => $request->name,
                'phone' => $request->phone,
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);

            $student = Student::create([
                'user_id' => $user->id,
                'student_id' => rand(1000, 9999) . $user->id,
                'school_name' => $request->school_name,
                'class' => $request->class,
                'date_of_birth' => $request->date_of_birth,
                'father_name' => $request->father_name,
                'mother_name' => $request->mother_name,
                'address' => $request->address,
                'emergency_contact' => json_encode([
                    'name' => $request->contact_name,
                    'phone' => $request->contact_phone
                ]),
                'batch_id' => $request->batch
            ]);

            if ($request->has('batch')) $this->countBatchStudents($request->batch);

            alert('Yahoo!', 'Student added successfully.', 'success');
            return to_route('admin.students.index');
        } catch (\Throwable $th) {
            Log::error($th->getMessage() . ' on line ' . $th->getLine() . ' in file ' . $th->getFile());

            alert('Oops!', 'Something went wrong.', 'error');
            return back();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        if (!auth()->user()->can('view_students')) {
            abort(403, 'Unauthorized action.');
        }

        $student = Student::with('user')->find($id);
        return $student;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        if (!auth()->user()->can('update_student')) {
            abort(403, 'Unauthorized action.');
        }

        $batches = Batch::active()->latest()->get();
        $student = Student::with('user')->find($id);

        return view('admin.student.edit', compact('student', 'batches'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if (!auth()->user()->can('update_student')) {
            abort(403, 'Unauthorized action.');
        }

        $student = Student::with('user')->findOrFail($id);

        $request->validate([
            'name' => 'required',
            'phone' => 'required|unique:users,phone,' . $student->user->id,
            'email' => 'nullable|unique:users,email,' . $student->user->id,
            'password' => 'nullable',
            'date_of_birth' => 'required|date',
            'father_name' => 'required',
            'mother_name' => 'required',
            'address' => 'required',
            'contact_name' => 'required',
            'contact_phone' => 'required',
            'status' => 'required|boolean',
            'batch' => 'nullable|exists:batches,id'
        ]);

        try {
            $student->user->update([
                'name' => $request->name,
                'phone' => $request->phone,
                'email' => $request->email,
                'password' => $request->password ? bcrypt($request->password) : $student->user->password,
            ]);

            $student->update([
                'school_name' => $request->school_name,
                'class' => $request->class,
                'date_of_birth' => $request->date_of_birth,
                'father_name' => $request->father_name,
                'mother_name' => $request->mother_name,
                'address' => $request->address,
                'emergency_contact' => json_encode([
                    'name' => $request->contact_name,
                    'phone' => $request->contact_phone
                ]),
                'status' => $request->status,
                'batch_id' => $request->batch
            ]);

            if ($request->has('batch')) $this->countBatchStudents($request->batch);

            alert('Yahoo!', 'Student updated successfully.', 'success');
            return to_route('admin.students.index');
        } catch (\Throwable $th) {
            Log::error($th->getMessage() . ' on line ' . $th->getLine() . ' in file ' . $th->getFile());

            alert('Oops!', 'Something went wrong.', 'error');
            return back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            if (!auth()->user()->can('delete_student')) {
                abort(403, 'Unauthorized action.');
            }

            $student = Student::find($id);

            if($student->batch_id) $this->countBatchStudents($student->batch_id);

            $student->user->delete();

            return true;
        } catch (\Throwable $th) {
            Log::error($th->getMessage() . ' on line ' . $th->getLine() . ' in file ' . $th->getFile());
            
            return false;
        }
    }

    /**
     * Update the status of a student.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * 
     * This method updates the status of a student based on the given request
     * data. It assumes the request contains a valid student ID and the new status.
     * After updating, it provides a success toast message and redirects back.
     */
    public function updateStatus(Request $request)
    {
        if (!auth()->user()->can('update_student')) {
            abort(403, 'Unauthorized action.');
        }

        try {
            $student = Student::find($request->id);
            $student->update([
                'status' => $request->status
            ]);

            alert('Yahoo!', 'Status updated successfully.', 'success');
            return back();
        } catch (\Throwable $th) {
            Log::error($th->getMessage() . ' on line ' . $th->getLine() . ' in file ' . $th->getFile());

            alert('Oops!', 'Something went wrong.', 'error');
            return back();
        }
    }

    private function countBatchStudents($id)
    {
        $batch = Batch::find($id);
        if ($batch) {
            $studentCount = Student::where('batch_id', $batch->id)->count();
            $batch->update([
                'total_students' => $studentCount
            ]);

            return true;
        }

        return false;
    }
}
