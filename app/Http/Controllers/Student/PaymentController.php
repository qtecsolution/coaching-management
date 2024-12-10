<?php


namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\Payment;
use App\Models\Setting;
use App\Models\Student;
use App\Models\StudentBatch;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class PaymentController extends Controller
{
    private ?Student $student;

    public function __construct()
    {
        $this->student = Student::where('user_id', auth()->id())->first();
    }

    public function getStudent()
    {
        return $this->student;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $student = $this->getStudent();
        $student_batch_ids = StudentBatch::where('student_id', $student->id)->get()->pluck('id');
        $payments = Payment::where('status', 1)->whereIn('student_batch_id', $student_batch_ids)->with(['student_batch.batch'])->latest();
        
        if (request()->ajax()) {
            return DataTables::of($payments)
                ->addIndexColumn()
                ->addColumn('DT_RowIndex', '')
                ->addColumn('batch', function ($row) {
                    return $row->student_batch->batch->name;
                })
                ->addColumn('action', function ($row) {
                    return '<a href="' . route('user.payments.show', $row->id) . '" class="btn btn-sm btn-info">
                        <i class="bi bi-printer" title="Print Invoice"></i>
                    </a>';
                })
                ->editColumn('amount', function ($row) {
                    return number_format($row->amount, 2);
                })
                ->editColumn('transaction_id', function ($row) {
                    return $row->transaction_id;
                })
                ->editColumn('month', function ($row) {
                    return Carbon::createFromFormat('Y-m', $row->month)->format('F, Y');
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

        return view('student.payments.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $settings = Setting::all();
        $payment = Payment::with('student_batch.student', 'student_batch.batch')->find($id);
        return view('student.payments.invoice', compact('payment', 'settings'));
    }


    /**
     * The function retrieves and displays due payments for a student based on batch and month filters
     * using DataTables in Laravel.
     * 
     * @param Request request The `due` function you provided is a controller method that handles AJAX
     * requests for fetching due payments data. It retrieves the student, active batches, and processes
     * the request parameters to filter payments due based on batch ID and month.
     * 
     * @return The function `due` is returning a view named 'student.payments.due' with the variable
     * 'batches' compacted. If the request is an AJAX request, it will return the data in a DataTable
     * format with specific columns and actions for each row.
     */
    public function due(Request $request)
    {
        $student = $this->getStudent();
        $batches = Batch::active()->get();

        if (request()->ajax()) {
            $batchId = null;
            $month = null;
            $reg_id = $student->reg_id;

            if ($request->filled('batch_id')) {
                $batchId = $request->batch_id;
            }

            if ($request->filled('month')) {
                $month = $request->month;
            }

            $payments_due = Payment::query()
                ->where('status', '!=', 1)
                ->whereHas('student_batch', function ($query) use ($batchId, $reg_id) {
                    $query->when($batchId, fn($q) => $q->where('batch_id', $batchId));
                    $query->when($reg_id, fn($q) => $q->whereHas('student', fn($q) => $q->where('reg_id', $reg_id)));
                })
                ->with(['student_batch.batch']);

            if (!empty($month)) {
                $payments_due = $payments_due->where('month', $month);
            }

            return DataTables::of($payments_due)
                ->addIndexColumn()
                ->addColumn('DT_RowIndex', '')
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
                    return '<a href="' . route('user.payments.show', $row->id) . '" class="btn btn-sm btn-info">
                        <i class="bi bi-printer" title="Print Invoice"></i>
                    </a>';
                })
                ->rawColumns(['name', 'reg_id', 'batch', 'amount', 'month', 'action', 'status'])
                ->make(true);
        }
        return view('student.payments.due', compact('batches'));
    }
}
