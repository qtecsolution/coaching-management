<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\SmsController;
use App\Models\Batch;
use App\Models\Message;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!auth()->user()->can('view_messages')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            return DataTables::of(Message::latest())
                ->addIndexColumn()
                ->addColumn('DT_RowIndex', '')
                ->editColumn('status', function ($row) {
                    if ($row->status == 1) {
                        return '<span class="badge bg-success">Done</span>';
                    } else {
                        return '<span class="badge bg-danger">Pending</span>';
                    }
                })
                ->addColumn('student', function ($row) {
                    return $row?->user->name;
                })
                ->addColumn('batch', function ($row) {
                    return $row?->user?->student?->currentBatch?->batch->name;
                })
                ->addColumn('date_time', function ($row) {
                    return Carbon::parse($row->created_at)->format('F d, Y | h:i A');
                })
                ->rawColumns(['status'])
                ->make(true);
        }

        return view('admin.message.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!auth()->user()->can('create_message')) {
            abort(403, 'Unauthorized action.');
        }

        $students = Student::active()->get();
        $batches = Batch::active()->get();

        return view('admin.message.create', compact('students', 'batches'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!auth()->user()->can('create_message')) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'student' => 'required_without:batch',
            'batch' => 'required_without:student',
            'message' => 'required|string',
        ]);

        // Get SMS provider configuration
        $provider = config('SmsCredentials.active_provider');
        $config = config('SmsCredentials.providers')[$provider];

        $smsController = new SmsController();

        if ($request->filled('batch')) {
            $batch = Batch::with('students.student')->findOrFail($request->batch);

            foreach ($batch->students as $student) {
                $phone = $student?->student?->user->phone ?? null;

                if ($phone) {
                    $smsController->sendSms($provider, $config, $phone, $request->message);

                    Message::create([
                        'user_id' => $student?->student?->user_id,
                        'receiver' => $phone,
                        'message' => $request->message,
                        'status' => 1,
                    ]);
                }
            }
        } else {
            $student = Student::with('user')->findOrFail($request->student);
            $phone = $student?->user->phone ?? null;

            if ($phone) {
                $smsController->sendSms($provider, $config, $phone, $request->message);

                Message::create([
                    'user_id' => $student->user_id,
                    'receiver' => $phone,
                    'message' => $request->message,
                    'status' => 1,
                ]);
            }
        }

        alert('Success!', 'Message sent successfully.', 'success');
        return to_route('admin.messages.index');
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
