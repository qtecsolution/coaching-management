<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\Payment;
use App\Models\Student;
use App\Models\StudentPayment;
use Illuminate\Http\Request;
use App\Traits\ExceptionHandler;
use Illuminate\Support\Facades\Log;
use Throwable;
use Yajra\DataTables\Facades\DataTables;

class PaymentController extends Controller
{
    use ExceptionHandler;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!auth()->user()->can('view_payments')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            return DataTables::of(Payment::query())
                ->addIndexColumn()
                ->addColumn('DT_RowIndex', '')
                ->addColumn('action', function ($row) {
                    return view('admin.payment.action', compact('row'));
                })
                ->editColumn('date', function ($row) {
                    return date('d F, Y', strtotime($row->date));
                })
                ->editColumn('amount', function ($row) {
                    return number_format($row->amount, 2);
                })
                ->addColumn('student_name', function ($row) {
                    return $row->student->name;
                })
                ->addColumn('batch_name', function ($row) {
                    return $row->batch->title;
                })
                ->filterColumn('student_name', function ($query, $keyword) {
                    $query->whereHas('student.user', function ($query) use ($keyword) {
                        $query->where('name', 'like', '%' . $keyword . '%');
                    });
                })
                ->filterColumn('batch_name', function ($query, $keyword) {
                    $query->whereHas('batch', function ($query) use ($keyword) {
                        $query->where('title', 'like', '%' . $keyword . '%');
                    });
                })
                ->rawColumns(['action', 'student_name', 'batch_name'])
                ->make(true);
        }

        return view('admin.payment.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!auth()->user()->can('create_payment')) {
            abort(403, 'Unauthorized action.');
        }

        $students = Student::active()->latest()->get();
        $batches = Batch::active()->latest()->get();

        return view('admin.payment.create', compact('students', 'batches'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!auth()->user()->can('create_payment')) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'student_id' => 'required|exists:students,id',
            'batch_id' => 'required|exists:batches,id',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'note' => 'nullable|string|max:255'
        ]);


        try {
            $studentPayment = StudentPayment::where('student_id', $request->student_id)
                ->where('batch_id', $request->batch_id)
                ->firstOrFail();

            if ($request->amount > $studentPayment->total_due) {
                return redirect()->back()->withInput();
            }

            $studentPayment->update([
                'total_due' => $studentPayment->total_due - $request->amount,
                'total_paid' => $studentPayment->total_paid + $request->amount,
            ]);

            Payment::create([
                'student_id' => $request->student_id,
                'batch_id' => $request->batch_id,
                'amount' => $request->amount,
                'date' => $request->date,
                'note' => $request->note,
                'method' => $request->method ?? 'cash',
            ]);

            $this->getAlert('success', 'Payment added successfully.');
            return redirect()->route('admin.payments.index');
        } catch (\Throwable $th) {
            $this->logException($th);
            return redirect()->back()->withInput();
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
        if (!auth()->user()->can('update_payment')) {
            abort(403, 'Unauthorized action.');
        }

        $payment = Payment::findOrFail($id);
        $students = Student::active()->latest()->get();
        $batches = Batch::active()->latest()->get();

        return view('admin.payment.edit', compact('payment', 'students', 'batches'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if (!auth()->user()->can('update_payment')) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'student_id' => 'required|exists:students,id',
            'batch_id' => 'required|exists:batches,id',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'note' => 'nullable|string|max:255'
        ]);

        try {
            $payment = Payment::findOrFail($id);
            $studentPayment = StudentPayment::where('student_id', $request->student_id)
                ->where('batch_id', $request->batch_id)
                ->firstOrFail();

            // Calculate the adjusted total_due by adding back the old payment amount
            $adjustedTotalDue = $studentPayment->total_due + $payment->amount;

            // Check if new payment amount exceeds the adjusted total due
            if ($request->amount > $adjustedTotalDue) {
                return redirect()->back()->withInput();
            }

            $payment->update([
                'student_id' => $request->student_id,
                'batch_id' => $request->batch_id,
                'amount' => $request->amount,
                'date' => $request->date,
                'note' => $request->note,
            ]);

            // Recalculate total paid amount
            $totalPaid = Payment::where('student_id', $request->student_id)
                ->where('batch_id', $request->batch_id)
                ->sum('amount');

            // Update student payment record
            $studentPayment->update([
                'total_paid' => $totalPaid,
                'total_due' => $studentPayment->final_amount - $totalPaid
            ]);

            $this->getAlert('success', 'Payment updated successfully.');
            return redirect()->route('admin.payments.index');
        } catch (\Exception $e) {
            $this->logException($e);
            return redirect()->back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (!auth()->user()->can('delete_payment')) {
            abort(403, 'Unauthorized action.');
        }

        try {
            $payment = Payment::findOrFail($id);

            // Restore the due amount in student payment
            $studentPayment = StudentPayment::where('student_id', $payment->student_id)
                ->where('batch_id', $payment->batch_id)
                ->firstOrFail();

            $studentPayment->update([
                'total_due' => $studentPayment->total_due + $payment->amount,
                'total_paid' => $studentPayment->total_paid - $payment->amount,
            ]);

            $payment->delete();

            return true;
        } catch (\Exception $e) {
            $this->logException($e);
            return false;
        }
    }

    public function getInfo(Request $request)
    {
        try {
            $request->validate([
                'student_id' => 'required|exists:students,id',
                'batch_id' => 'required|exists:batches,id',
            ]);

            $info = StudentPayment::where('student_id', $request->student_id)
                ->where('batch_id', $request->batch_id)
                ->firstOrFail();

            return response()->json([
                'success' => true,
                'info' => $info
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch payment information.',
                'error' => $e->getMessage()
            ], 422);
        }
    }
}
