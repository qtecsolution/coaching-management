<?php


namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Setting;
use App\Models\Student;
use Yajra\DataTables\DataTables;

class PaymentController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $student = Student::where('user_id', auth()->id())->first();
        $payments = Payment::where('reg_id', $student->id)->with('batch')->latest();
        if (request()->ajax()) {
            return DataTables::of($payments)
                ->addIndexColumn()
                ->addColumn('DT_RowIndex', '')
                ->addColumn('student', function ($row) use ($student) {
                    return $student->name . '<br>' . $student->reg_id;
                })
                ->addColumn('batch', function ($row) {
                    return $row->batch->name;
                })
                ->addColumn('action', function ($row) {
                    return '<a href="' . route('user.payments.show', $row->id) . '" class="btn btn-sm btn-info">
        <i class="bi bi-printer" title="Print Invoice"></i>
    </a>';
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

        return view('student.payments.index');
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $settings = Setting::all();
        $payment = Payment::with('student', 'batch')->find($id);
        return view('student.payments.invoice', compact('payment', 'settings'));
    }
}
