<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\DataTables;

class PayementController extends Controller
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
            return DataTables::of(Payment::latest())
                ->addIndexColumn()
                ->addColumn('DT_RowIndex', '')
                ->addColumn('action', function ($row) {
                    return view('admin.payments.action', compact('row'));
                })
                ->addColumn('amount', function ($row) {
                    return $row->amount;
                })
                ->addColumn('transaction_id', function ($row) {
                    return $row->transaction_id;
                })
                ->addColumn('month', function ($row) {
                    return Carbon::createFromFormat('Y-m', $row->month)->format('M-Y');
                })
                ->addColumn('date', function ($row) {
                    return $row->date;
                })
                ->editColumn('status', function ($row) {
                    if ($row->status == 1) {
                        return '<span class="badge bg-success">Success</span>';
                    } else {
                        return '<span class="badge bg-danger">Pending</span>';
                    }
                })
                ->rawColumns(['action','amount', 'transaction_id', 'month','date','status'])
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

        return view('admin.payments.create');
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
            'name' => 'required',
            'tuition_fee' => 'required|numeric',
            'days' => 'required',
            'level' => 'nullable|exists:levels,id'
        ]);

        try {
            $payment = Payment::create([
                'name' => $request->name,
                'tuition_fee' => $request->tuition_fee,
                'level_id' => $request->level
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Payment added successfully.',
            ]);
        } catch (\Throwable $th) {
            Log::info($th->getMessage() . ' on line ' . $th->getLine() . ' in ' . $th->getFile());

            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        if (!auth()->user()->can('view_paymentes')) {
            abort(403, 'Unauthorized action.');
        }

        $payment = Payment::with(['level', 'batch_days'])->findOrFail($id);
        return view('admin.payments.show', compact('payment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        if (!auth()->user()->can('update_payment')) {
            abort(403, 'Unauthorized action.');
        }

        $payment = Payment::with('batch_days')->find($id);

        return view('admin.payments.edit', compact('payment' ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        if (!auth()->user()->can('update_payment')) {
            abort(403, 'Unauthorized action.');
        }
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
    }
}

