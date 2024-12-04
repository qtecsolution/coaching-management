@extends('layouts.master')

@section('title', 'Dashboard')

@php
    $schedules = $user?->batch_days ?? [];
    $daysOfWeek = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
    $currentDayIndex = Carbon\Carbon::now()->dayOfWeek;

    // Find the next class day
    $nextClassDay = null;
    foreach ($schedules as $schedule) {
        $scheduleDayIndex = array_search($schedule->day_name, $daysOfWeek);
        if ($scheduleDayIndex > $currentDayIndex) {
            $nextClassDay = $schedule->day_name;
            break;
        }
    }

    // If no next day is found, assume the first day of the next week
    if (!$nextClassDay) {
        $nextClassDay = $schedules->first()?->day_name;
    }
@endphp

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
                                        <th>Day</th>
                                        <th>Time</th>
                                        <th>Subject</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (count($schedules) > 0)
                                        @foreach ($schedules as $schedule)
                                            <tr class="{{ $schedule->day_name === $nextClassDay ? 'highlighted-row' : '' }} border">
                                                <td>{{ $schedule->day_name }}</td>
                                                <td>
                                                    {{ Carbon\Carbon::parse($schedule->start_time)->format('h:i A') }} -
                                                    {{ Carbon\Carbon::parse($schedule->end_time)->format('h:i A') }}
                                                </td>
                                                <td>{{ $schedule->subject_name }}</td>
                                                <td>
                                                    <div class="btn-group">
                                                        <a href="#" class="btn btn-sm btn-light" title="Student List">
                                                            <i class="bi bi-list-task"></i>
                                                        </a>
                                                        <a href="#" class="btn btn-sm btn-light" title="Attendence">
                                                            <i class="bi bi-calendar3"></i>
                                                        </a>
                                                        <a href="#" class="btn btn-sm btn-light" title="Grades">
                                                            <i class="bi bi-bookmark-fill"></i>
                                                        </a>
                                                        {{-- <a href="#" class="btn btn-sm btn-light" title="Online Class">
                                                            <i class="bi bi-person-video"></i>
                                                        </a> --}}
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="4" class="text-center">No schedule found.</td>
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