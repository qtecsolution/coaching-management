<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ReportController extends Controller
{
    public function dailyCollection(Request $request)
    {
        if (!auth()->user()->can('view_payments')) {
            abort(403, 'Unauthorized action.');
        }
        $batches = Batch::active()->get();
        if (request()->ajax()) {
            $date_from = null;
            $date_to = null;
            $batchId = null;
            $month = null;
            if ($request->filled('batch_id')) {
                $batchId = $request->batch_id;
            }

            if ($request->filled('month')) {
                $month = $request->month;
            }
            if ($request->filled('date_from')) {
                $date_from = $request->date_from;
            }
            if ($request->filled('date_to')) {
                $date_to = $request->date_to;
            }

            $payments_due = Payment::query()
                ->where('status', 1)
                ->whereHas('student_batch', function ($query) use ($batchId) {
                    $query->when($batchId, fn($q) => $q->where('batch_id', $batchId));
                })
                ->with([
                    'student_batch.student',
                    'student_batch.batch'
                ]);
            if (!empty($month)) {
                $payments_due = $payments_due->where('month', $month);
            }
            if (!empty($date_from)|| !empty($date_to)) {
                $payments_due = $payments_due->whereBetween('date',  [$date_from,$date_to]);
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
               ->editColumn('transaction_id', function ($row) {
                return $row->transaction_id;
               })
                ->editColumn('month', function ($row) {
                    return Carbon::createFromFormat('Y-m', $row->month)->format('M-Y');
                })
                ->editColumn('date', function ($row) {
                    return $row->date;
                })
                ->rawColumns(['name', 'reg_id', 'batch', 'amount', 'month','transaction_id','date'])
                ->make(true);
        }
        return view('admin.reports.payments.daily-collection', compact('batches'));
    }
    public function duePayments(Request $request)
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
                    return $row->amount;
                })
                ->editColumn('month', function ($row) {
                    return Carbon::createFromFormat('Y-m', $row->month)->format('M-Y');
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
        return view('admin.reports.payments.due', compact('batches'));
    }
}
