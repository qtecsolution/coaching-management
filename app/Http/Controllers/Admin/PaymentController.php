<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\Payment;
use App\Models\Student;
use App\Models\StudentPayment;
use Illuminate\Http\Request;
use App\Traits\ExceptionHandler;

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
            'student_id' => 'required',
            'batch_id' => 'required',
            'amount' => 'required',
            'date' => 'required|date',
        ]);

        $studentPayment = StudentPayment::where('student_id', $request->student_id)
            ->where('batch_id', $request->batch_id)
            ->first();

        if ($studentPayment && $studentPayment->due_amount <= $request->amount) {
            $studentPayment->update([
                'due_amount' => $studentPayment->due_amount - $request->amount,
            ]);

            Payment::create([
                'student_id' => $request->student_id,
                'batch_id' => $request->batch_id,
                'amount' => $request->amount,
                'date' => $request->date,
                'note' => $request->note,
            ]);
        } else {
            return redirect()->back()->with('error', 'Invalid amount');
        }

        $this->getAlert('success', 'Payment added successfully.');
        return redirect()->route('admin.payments.index');
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
            'student_id' => 'required',
            'batch_id' => 'required',
            'amount' => 'required',
            'date' => 'required|date',
        ]);

        $studentPayment = StudentPayment::where('student_id', $request->student_id)
            ->where('batch_id', $request->batch_id)
            ->first();

        if ($studentPayment && $studentPayment->due_amount <= $request->amount) {
            $studentPayment->update([
                'due_amount' => $studentPayment->due_amount - $request->amount,
            ]);
        } else {
            return redirect()->back()->with('error', 'Invalid amount');
        }

        $payment = Payment::findOrFail($id);
        $payment->update([
            'student_id' => $request->student_id,
            'batch_id' => $request->batch_id,
            'amount' => $request->amount,
            'date' => $request->date,
            'note' => $request->note,
        ]);

        $this->getAlert('success', 'Payment updated successfully.');
        return redirect()->route('admin.payments.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (!auth()->user()->can('delete_payment')) {
            abort(403, 'Unauthorized action.');
        }
    }

    public function getInfo(Request $request)
    {
        $info = StudentPayment::where('student_id', $request->student_id)
            ->where('batch_id', $request->batch_id)
            ->first();

        return response()->json([
            'info' => $info
        ]);
    }
}
