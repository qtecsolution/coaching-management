@extends('layouts.master')

@section('title', 'Attendance')

@section('content')
    <div class="page-heading">
        <x-page-title title="Attendance" />

        <!-- Basic Tables start -->
        <section class="section">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hove" id="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>History</th>
                                    <th>Attendance</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($records->attendance as $record)
                                    <tr>
                                        <td>{{ $record?->student->reg_id }}</td>
                                        <td>{{ $record?->student->name }}</td>
                                        <td>
                                            <span class="text-sm d-block">Absent: {{ $record?->student?->currentBatch?->total_absent() }}</span>
                                            <span class="text-sm d-block">Present: {{ $record?->student?->currentBatch?->total_present() }}</span>
                                            <span class="text-sm d-block">Late: {{ $record?->student?->currentBatch?->total_late() }}</span>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <button class="btn btn-white border btn-sm btn-hover {{ $record->status == 0 ? 'bg-primary text-white' : '' }}">Absent</button>
                                                <button class="btn btn-white border btn-sm btn-hover {{ $record->status == 1 ? 'bg-primary text-white' : '' }}">Present</button>
                                                <button class="btn btn-white border btn-sm btn-hover {{ $record->status == 2 ? 'bg-primary text-white' : '' }}">Late</button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">No student found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
        <!-- Basic Tables end -->
    </div>
@endsection
