<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\Payment;
use App\Models\Setting;
use App\Models\Student;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\DataTables;

class PaymentController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!auth()->user()->can('view_payments')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            return DataTables::of(Payment::with('student', 'batch')->latest())
                ->addIndexColumn()
                ->addColumn('DT_RowIndex', '')
                ->addColumn('student', function ($row) {
                    return $row->student->name . '<br>' . $row->student->student_id;
                })
                ->addColumn('batch', function ($row) {
                    return $row->batch->name;
                })
                ->addColumn('action', function ($row) {
                    return view('admin.payments.action', compact('row'));
                })
                ->editColumn('amount', function ($row) {
                    return $row->amount;
                })
                ->editColumn('transaction_id', function ($row) {
                    return $row->transaction_id;
                })
                ->editColumn('month', function ($row) {
                    return $row->month;
                })
                ->editColumn('date', function ($row) {
                    return $row->date;
                })
                ->editColumn('status', function ($row) {
                    if ($row->status == 1) {
                        return '<span class="badge bg-success">Success</span>';
                    } else {
                        return '<span class="badge bg-danger">Pending</span>';
                    }
                })
                ->rawColumns(['student', 'batch', 'action', 'amount', 'transaction_id', 'month', 'date', 'status'])
                ->make(true);
        }

        return view('admin.payments.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!auth()->user()->can('create_payment')) {
            abort(403, 'Unauthorized action.');
        }
        $batches = Batch::active()->has('students')->with('students')->get();
        if ($batches->isEmpty()) {
            alert('Warning!', 'No batch found.', 'warning');
            return redirect()->back();
        }
        return view('admin.payments.create', compact('batches'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!auth()->user()->can('create_payment')) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'batch_id' => 'required|exists:batches,id',
            'amount' => 'required|numeric|min:0',
            'month' => 'required|string',
            'date' => 'nullable|date',
            'payment_method' => 'nullable|string',
            'transaction_id' => 'nullable|string',
            'note' => 'nullable|string',
        ], [
            'student_id.required' => 'The student field is required.',
            'student_id.exists' => 'The selected student is invalid.',
            'batch_id.required' => 'The batch field is required.',
            'batch_id.exists' => 'The selected batch is invalid.',
        ]);

        // Check if the student has already paid for the same batch and month
        $existingPayment = Payment::where('student_id', $request->student_id)
            ->where('batch_id', $request->batch_id)
            ->where('month', $request->month)
            ->exists();
        if ($existingPayment) {
            return redirect()->back()
                ->withInput($request->all())
                ->withErrors(['month' => 'The student has already paid for this month. Please select another month.']);
        }
        if (!$validated['date']) {
            $validated['date'] = Carbon::today()->toDateString();
        }
        $validated['status'] = 1; // payment success
        $payment = Payment::create($validated);
        if (!$payment) {
            alert('Warning!', 'Failed to save payment', 'error');
            return redirect()->back()->withInput($request->all());
        }
        alert('Success!', 'Student payment added', 'success');
        return to_route('admin.payments.create');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        if (!auth()->user()->can('view_payments')) {
            abort(403, 'Unauthorized action.');
        }

        $settings = Setting::all();
        $payment = Payment::with('student', 'batch')->find($id);
        return view('admin.payments.invoice', compact('payment', 'settings'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        if (!auth()->user()->can('update_payment')) {
            abort(403, 'Unauthorized action.');
        }

        $payment = Payment::find($id);
        $batches = Batch::active()->has('students')->with('students')->get();
        return view('admin.payments.edit', compact('payment', 'batches'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        if (!auth()->user()->can('update_payment')) {
            abort(403, 'Unauthorized action.');
        }

        $payment = Payment::findOrFail($id);
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'batch_id' => 'required|exists:batches,id',
            'amount' => 'required|numeric|min:0',
            'month' => 'required|string',
            'date' => 'nullable|date',
            'payment_method' => 'nullable|string',
            'transaction_id' => 'nullable|string',
            'note' => 'nullable|string',
        ], [
            'student_id.required' => 'The student field is required.',
            'student_id.exists' => 'The selected student is invalid.',
            'batch_id.required' => 'The batch field is required.',
            'batch_id.exists' => 'The selected batch is invalid.',
        ]);
        $validated['status'] = 1; // payment success
        $payment->update($validated);
        alert('Success!', 'Student payment updated', 'success');
        return to_route('admin.payments.index');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        abort_if(!auth()->user()->can('delete_payment'), 403);
        $payment = Payment::findOrFail($id);
        $payment->delete();
        return true;
    }
    public function due(Request $request)
    {
        if (!auth()->user()->can('view_payments')) {
            abort(403, 'Unauthorized action.');
        }

        $batches = Batch::active()->get();
        if (request()->ajax()) {
            $batchId = null;
            $month = now()->format('Y-m');
            if ($request->filled('batch_id')) {
                $batchId = $request->batch_id;
            }

            if ($request->filled('month')) {
                $month = $request->month;
            }
            $unpaidStudents = Student::whereHas('batch', function ($query) use ($batchId) {
                if ($batchId) {
                    $query->where('id', $batchId);
                }
            })
                ->whereDoesntHave('payments', function ($query) use ($batchId, $month) {
                    $query->when($batchId, fn($q) => $q->where('batch_id', $batchId))
                        ->when($month, fn($q) => $q->where('month', $month));
                })
                ->with('batch')
                ->get();
            return DataTables::of($unpaidStudents)
                ->addIndexColumn()
                ->addColumn('DT_RowIndex', '')
                ->addColumn('name', function ($row) {
                    return $row->name;
                })
                ->addColumn('student_id', function ($row) {
                    return $row->student_id;
                })
                ->addColumn('batch', function ($row) {
                    return $row->batch->name;
                })
                ->editColumn('amount', function ($row) {
                    return $row->batch->tuition_fee;
                })
                ->editColumn('month', $month)
                ->addColumn('action', function ($row) {
                    if (auth()->user()->can('update_payment')) {
                        return '
        <div class="btn-group">
            <a href="' . route('admin.payments.edit', $row->id) . '" class="btn btn-sm btn-primary">
                <i class="bi bi-pencil"></i>
            </a>
        </div>';
                    }
                    return '';
                })
                ->rawColumns(['name', 'student_id', 'batch', 'amount', 'month', 'action'])
                ->make(true);
        }
        return view('admin.payments.due', compact('batches'));
    }
}
