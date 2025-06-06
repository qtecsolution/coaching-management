<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\Student;
use App\Models\StudentBatch;
use App\Models\StudentDynamicField;
use App\Models\StudentPayment;
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
                    return $row->user->email ?? '--';
                })
                ->editColumn('qualification', function ($row) {
                    return $row->qualification ?? '--';
                })
                ->editColumn('occupation', function ($row) {
                    return $row->occupation ?? '--';
                })
                ->addColumn('status', function ($row) {
                    if ($row->status) {
                        return '<span class="badge bg-primary">Active</span>';
                    } else {
                        return '<span class="badge bg-danger">Inactive</span>';
                    }
                })
                ->addColumn('batch', function ($row) {
                    $currentBatch = $row?->currentBatch?->batch;

                    if ($currentBatch && $currentBatch->status == 1) {
                        return $row?->currentBatch?->batch?->title ?? '--';
                    } else {
                        return '--';
                    }
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
            'batch' => 'required|exists:batches,id',
            'nid_number' => 'required|unique:students,nid_number',
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
                'reg_id' => rand(1000, 9999) . $user->id,
                'qualification' => $request->qualification,
                'occupation' => $request->occupation,
                'nid_number' => $request->nid_number,
                'date_of_birth' => $request->date_of_birth,
                'father_name' => $request->father_name,
                'mother_name' => $request->mother_name,
                'address' => $request->address,
                'emergency_contact' => json_encode([
                    'name' => $request->contact_name,
                    'phone' => $request->contact_phone
                ])
            ]);

            // Filter fields
            $fields = [];
            foreach ($request->field_name as $index => $name) {
                $fields[] = [
                    'student_id' => $student->id,
                    'name' => $name,
                    'value' => $request->field_value[$index],
                ];
            }

            // Save fields
            foreach ($fields as $field) {
                StudentDynamicField::create($field);
            }

            $studentBatch = StudentBatch::create([
                'student_id' => $student->id,
                'batch_id' => $request->batch
            ]);

            $batch = $studentBatch?->batch;
            $totalDue = $batch->price - ($batch->discount_type == 'percentage'? ($batch->discount / 100) * $batch->price : $batch->discount);

            StudentPayment::create([
                'student_id' => $student->id,
                'batch_id' => $batch->id,
                'amount' => $batch->price,
                'discount_type' => $batch->discount_type,
                'discount' => $batch->discount,
                'total_due' => $totalDue,
                'final_amount' => $totalDue
            ]);

            if ($request->has('batch')) $this->countBatchStudents($request->batch);

            alert('Success!', 'Student added successfully.', 'success');
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
        return view('admin.student.id-card', compact('student'));
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
            'batch' => 'required|exists:batches,id',
            'nid_number' => 'required|unique:students,nid_number,' . $student->id,
        ]);

        try {
            $student->user->update([
                'name' => $request->name,
                'phone' => $request->phone,
                'email' => $request->email,
                'password' => $request->password ? bcrypt($request->password) : $student->user->password,
            ]);

            $student->update([
                'qualification' => $request->qualification,
                'occupation' => $request->occupation,
                'nid_number' => $request->nid_number,
                'date_of_birth' => $request->date_of_birth,
                'father_name' => $request->father_name,
                'mother_name' => $request->mother_name,
                'address' => $request->address,
                'emergency_contact' => json_encode([
                    'name' => $request->contact_name,
                    'phone' => $request->contact_phone
                ]),
                'status' => $request->status
            ]);

            // Fetch existing fields
            $existingFields = $student->dynamicFields;

            if ($request->field_name != null) {
                // Update or create new fields
                foreach ($request->field_name as $index => $name) {
                    $field = $existingFields[$index] ?? new StudentDynamicField();
                    $field->student_id = $student->id;
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
            } else {
                // Remove all fields
                foreach ($existingFields as $field) {
                    $field->delete();
                }
            }

            $currentBatch = $student->currentBatch;
            if ($currentBatch && $currentBatch?->batch?->status == 1) {
                $currentBatch->update(['batch_id' => $request->batch]);

                $newBatch = Batch::findOrFail($request->batch);
                StudentPayment::where('student_id', $student->id)
                    ->where('batch_id', $currentBatch->batch->id)
                    ->update([
                        'batch_id' => $request->batch,
                        'amount' => $newBatch->price,
                        'discount_type' => $newBatch->discount_type,
                        'discount' => $newBatch->discount,
                        'total_due' => $newBatch->price - ($newBatch->discount_type == 'percentage' ? ($newBatch->discount / 100) * $newBatch->price : $newBatch->discount)
                    ]);
            } else {
                StudentBatch::create([
                    'student_id' => $student->id,
                    'batch_id' => $request->batch
                ]);

                $batch = Batch::findOrFail($request->batch);
                $batch->increment('total_students', 1);

                $totalDue = $batch->price - ($batch->discount_type == 'percentage'? ($batch->discount / 100) * $batch->price : $batch->discount);

                StudentPayment::create([
                    'student_id' => $student->id,
                    'batch_id' => $batch->id,
                    'amount' => $batch->price,
                    'discount_type' => $batch->discount_type,
                    'discount' => $batch->discount,
                    'total_due' => $totalDue,
                    'final_amount' => $totalDue
                ]);
            }

            if ($request->has('batch')) $this->countBatchStudents($request->batch);

            alert('Success!', 'Student updated successfully.', 'success');
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

            if ($student?->currentBatch?->batch_id) {
                $batch = Batch::findOrFail($student->currentBatch->batch_id);
                $batch->decrement('total_students');
            }

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

            alert('Success!', 'Status updated successfully.', 'success');
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
            $studentCount = StudentBatch::where('batch_id', $batch->id)->count();
            $batch->update([
                'total_students' => $studentCount
            ]);

            return true;
        }

        return false;
    }

    public function idCard($id)
    {
        $student = Student::findOrFail($id);
        return view('admin.student.id-card', compact('student'));
    }
}
