<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\Payment;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
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
            return DataTables::of(Payment::where('status', 1)->with(['student_batch.student', 'student_batch.batch'])->latest())
                ->addIndexColumn()
                ->addColumn('DT_RowIndex', '')
                ->addColumn('student', function ($row) {
                    return $row->student_batch->student->name . ' (' . $row?->student_batch?->student->reg_id . ')';
                })
                ->addColumn('batch', function ($row) {
                    return $row->student_batch->batch->name;
                })
                ->addColumn('action', function ($row) {
                    return view('admin.payments.action', compact('row'));
                })
                ->editColumn('amount', function ($row) {
                    return number_format($row->amount, 2);
                })
                ->editColumn('transaction_id', function ($row) {
                    return $row->transaction_id ?? '--';
                })
                ->editColumn('month', function ($row) {
                    return date('F, Y', strtotime($row->month));
                })
                ->editColumn('date', function ($row) {
                    return Carbon::parse($row->date)->format('d/m/Y');
                })
                ->editColumn('status', function ($row) {
                    return $row->status_badge;
                })
                ->rawColumns(['student', 'batch', 'action', 'amount', 'transaction_id', 'month', 'date', 'status'])
                ->make(true);
        }

        return view('admin.payments.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        if (!auth()->user()->can('create_payment')) {
            abort(403, 'Unauthorized action.');
        }

        // Get all batches
        $batches = Batch::active()->has('students')->with('students.student')->get();
        if ($batches->isEmpty()) {
            alert('Warning!', 'No batch found.', 'warning');
            return redirect()->back();
        }

        $paymentMethods = Payment::$paymentMethods;
        return view('admin.payments.create', compact('batches', 'request', 'paymentMethods'));
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
            'student_batch_id' => 'required|exists:student_batches,id',
            'amount' => 'required|numeric|min:0',
            'month' => 'required|string',
            'date' => 'nullable|date',
            'payment_method' => 'nullable|string',
            'transaction_id' => 'nullable|string',
            'note' => 'nullable|string',
        ], [
            'student_batch_id.required' => 'The student field is required.',
            'student_batch_id.exists' => 'The selected student is invalid.',
        ]);

        if (!$validated['date']) {
            $validated['date'] = Carbon::today()->toDateString();
        }

        $validated['status'] = 1; // payment success

        $payment = Payment::where('status', '!=', 1)->where('student_batch_id', $request->student_batch_id)
            ->where('month', $request->month)->first();

        if ($payment) {
            $payment->update($validated);
        } else {
            return redirect()->back()
                ->withInput($request->all())
                ->withErrors(['month' => 'The student does not have any due payment for this month.']);
        }

        alert('Success!', 'Payment added successfully.', 'success');
        return to_route('admin.payments.index');
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
        $payment = Payment::with('student_batch.student', 'student_batch.batch')->find($id);

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

        $payment = Payment::with('student_batch')->find($id);
        $batches = Batch::active()->has('students')->with('students.student')->get();

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
            'student_batch_id' => 'required|exists:student_batches,id',
            'amount' => 'required|numeric|min:0',
            'month' => 'required|string',
            'date' => 'nullable|date',
            'payment_method' => 'nullable|string',
            'transaction_id' => 'nullable|string',
            'note' => 'nullable|string',
        ], [
            'student_batch_id.required' => 'The student field is required.',
            'student_batch_id.exists' => 'The selected student is invalid.',
        ]);

        $validated['status'] = 1; // payment success
        $payment->update($request->only(['date', 'payment_method', 'transaction_id', 'note']));

        alert('Success!', 'Payment updated successfully.', 'success');
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

    // Show due payments
    public function due(Request $request)
    {
        if (!auth()->user()->can('view_payments')) {
            abort(403, 'Unauthorized action.');
        }

        $batches = Batch::get();

        if (request()->ajax()) {
            $batchId = null;
            $month = null;
            $reg_id = null;

            if ($request->filled('batch_id')) {
                $batchId = $request->batch_id;
            }

            if ($request->filled('month')) {
                $month = $request->month;
            }

            if ($request->filled('reg_id')) {
                $reg_id = $request->reg_id;
            }

            $payments_due = Payment::query()
                ->where('status', '!=', 1)
                ->whereHas('student_batch', function ($query) use ($batchId, $reg_id) {
                    $query->when($batchId, fn($q) => $q->where('batch_id', $batchId));
                    $query->when($reg_id, fn($q) => $q->whereHas('student', fn($q) => $q->where('reg_id', $reg_id)));
                })
                ->with([
                    'student_batch.student',
                    'student_batch.batch'
                ]);

            if (!empty($month)) {
                $payments_due = $payments_due->where('month', $month);
            }

            $payments_due = $payments_due->get();

            return DataTables::of($payments_due)
                ->addIndexColumn()
                ->addColumn('DT_RowIndex', '')
                ->addColumn('name', function ($row) {
                    return $row->student_batch->student->name;
                })
                ->addColumn('reg_id', function ($row) {
                    return $row->student_batch->student->reg_id;
                })
                ->addColumn('batch', function ($row) {
                    return $row->student_batch->batch->name;
                })
                ->editColumn('amount', function ($row) {
                    return number_format($row->amount, 2);
                })
                ->editColumn('month', function ($row) {
                    return Carbon::createFromFormat('Y-m', $row->month)->format('F, Y');
                })
                ->editColumn('status', function ($row) {
                    return $row->status_badge;
                })
                ->addColumn('action', function ($row) {
                    if (auth()->user()->can('update_payment')) {
                        return '
                            <div class="btn-group">
                                <a href="' . route('admin.payments.create', [
                            'student_batch_id' => $row->student_batch->id,
                            'batch_id' => $row->student_batch->batch->id,
                            'month' => $row->month
                        ]) . '" class="btn btn-sm btn-primary">
                                    Collection
                                </a>
                            </div>';
                    }

                    return ''; // Return an empty string if the user does not have permission
                })
                ->rawColumns(['name', 'reg_id', 'batch', 'amount', 'month', 'action', 'status'])
                ->make(true);
        }
        return view('admin.payments.due', compact('batches'));
    }

    // Generate payments for a specific month
    public function generatePayments(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'month' => 'nullable|date_format:Y-m',
            ]);

            $month = $request->input('month', now()->format('Y-m'));

            try {
                $isPaymentGenerated = Payment::where('month', $month)->exists();
                if ($isPaymentGenerated) {
                    alert('Info!', 'Payment already generated for this month', 'info');
                    return redirect()->back()->withInput();
                }

                $exitCode = Artisan::call('payments:generate', ['month' => $month]);
                $output = Artisan::output();

                if ($exitCode !== 0) {
                    alert('Error!', $output, 'error');
                    return redirect()->back()->withInput();
                }

                alert('Success!', $output, 'success');
                return redirect()->back();
            } catch (\Exception $e) {
                alert('Error!', $e->getMessage(), 'error');
                return redirect()->back()->withInput();
            }
        }

        return view('admin.payments.generate');
    }
}
