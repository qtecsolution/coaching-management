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
                ->addColumn('student', function ($row) use ($student) {
                    return $student->name . '<br>' . $student->reg_id;
                })
                ->addColumn('batch', function ($row) {
                    return $row->student_batch->batch->name;
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
                    return $row->amount;
                })
                ->editColumn('month', function ($row) {
                    return Carbon::createFromFormat('Y-m', $row->month)->format('M-Y');
                })
                ->editColumn('status', function ($row) {
                    return $row->status_badge;
                })
                ->addColumn('action', function ($row) {
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
                })
                ->rawColumns(['name', 'reg_id', 'batch', 'amount', 'month', 'action', 'status'])
                ->make(true);
        }
        return view('student.payments.due', compact('batches'));
    }
}
