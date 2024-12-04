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
            if ($request->filled('date_from')) {
                $date_from = $request->date_from;
            }
            if ($request->filled('date_to')) {
                $date_to = $request->date_to;
            }

            $payments_due = Payment::query()
                ->where('status', 1)
                ->with([
                    'student_batch.student',
                    'student_batch.batch'
                ]);
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
}
