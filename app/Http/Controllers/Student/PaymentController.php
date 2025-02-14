<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()->ajax()) {
            return DataTables::of(Payment::where('student_id', auth()->user()?->student?->id))
                ->addIndexColumn()
                ->addColumn('DT_RowIndex', '')
                ->editColumn('date', function ($row) {
                    return date('d F, Y', strtotime($row->date));
                })
                ->editColumn('amount', function ($row) {
                    return number_format($row->amount, 2);
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

        return view('student.payment.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
