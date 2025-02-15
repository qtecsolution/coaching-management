@extends('layouts.master')

@section('title', 'Dashboard')

@push('css')
    <style>
        tr.highlighted-row {
            background-color: rgb(99, 99, 56) !important;
        }

        .highlighted-row td {
            color: white !important;
        }
    </style>
@endpush

@section('content')
    <div class="page-heading">
        <h3>Dashboard</h3>
    </div>
    <div class="page-content">
        <section>
            <div class="card">
                <div class="card-body">
                    <div class="col-12">
                        <h3>Hello, {{ $user?->user->name }}.</h3>
                        <p>See the following table to check your class schedules.</p>
                    </div>
                    <div class="col-12">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Batch</th>
                                        <th>Day</th>
                                        <th>Time</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (count($classSchedules) > 0)
                                        @foreach ($classSchedules as $schedule)
                                            <tr>
                                                <td>{{ $schedule?->batchDay?->batch->title }}</td>
                                                <td>{{ Carbon\Carbon::parse($schedule->date)->format('d F, Y') }} ({{ $schedule->day_name }})</td>
                                                <td>
                                                    {{ Carbon\Carbon::parse($schedule?->batchDay?->start_time)->format('h:i A') }} -
                                                    {{ Carbon\Carbon::parse($schedule?->batchDay?->end_time)->format('h:i A') }}
                                                </td>
                                                <td>
                                                    <div class="btn-group">
                                                        <a href="{{ route('admin.batches.students', $schedule?->batchDay?->batch_id) }}" class="btn btn-sm btn-light" title="Student List">
                                                            <i class="bi bi-list-task"></i>
                                                        </a>
                                                        <a href="{{ route('admin.attendance.index', $schedule->id) }}" class="btn btn-sm btn-light" title="Attendence">
                                                            <i class="bi bi-calendar3"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="5" class="text-center">No schedule found.</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
