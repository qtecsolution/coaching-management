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
        $payments = Payment::whereIn('student_batch_id', $student_batch_ids)->with(['student_batch.batch'])->latest();
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
        $student = $this->student;
        $batches = Batch::active()->get();
        if (request()->ajax()) {
            $batchId = $student->batch_id;
            $month = now()->format('Y-m');
            $student_id = $student->id;
            if ($request->filled('batch_id')) {
                $batchId = $request->batch_id;
            }

            if ($request->filled('month')) {
                $month = $request->month;
            }
            if ($request->filled('student_id')) {
                $student_id = $request->student_id;
            }
            $compareDate = $this->endOfMonthWithDate($month);
            $unpaidStudents = Student::where('created_at', '<=', $compareDate)
                ->whereHas('batch', function ($query) use ($batchId) {
                    if ($batchId) {
                        $query->where('id', $batchId);
                    }
                })
                ->whereDoesntHave('payments', function ($query) use ($batchId, $month) {
                    $query->when($batchId, fn($q) => $q->where('batch_id', $batchId))
                        ->when($month, fn($q) => $q->where('month', $month));
                })
                ->when($student_id, function ($query) use ($student_id) {
                    return $query->where('student_id', $student_id);
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
                ->editColumn('month', Carbon::createFromFormat('Y-m', $month)->format('M-Y'))

                ->addColumn('action', function ($row) use ($month) {
                    if (auth()->user()->can('update_payment')) {
                        return '
        <div class="btn-group">
            <a href="' . route('admin.payments.create', [
                            'student_id' => $row->id,
                            'batch_id' => $row->batch->id,
                            'month' => $month
                        ]) . '" class="btn btn-sm btn-primary">
                Collection
            </a>
        </div>';
                    }
                    return ''; // Return an empty string if the user does not have permission
                })
                ->rawColumns(['name', 'student_id', 'batch', 'amount', 'month', 'action'])
                ->make(true);
        }
        return view('student.payments.due', compact('batches'));
    }
    private function endOfMonthWithDate($yearMonth)
    {
        [$year, $mon] = explode('-', $yearMonth);
        return Carbon::createFromDate($year, $mon, 1)->endOfMonth();
    }
}
