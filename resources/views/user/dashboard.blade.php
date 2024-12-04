@extends('layouts.master')

@section('title', 'Dashboard')

@php
    $schedules = $user?->student?->currentbatch->batch?->batch_days ?? [];
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
                        <h3>Hello, {{ $user->name }}.</h3>
                        <p>
                            @if (isset($user?->student?->currentBatch?->batch?->name))
                                You're enrolled to the <b>{{ $user->student->currentBatch?->batch?->name }}</b>. See the
                                following
                                table to check your class schedules.
                            @else
                                You're not enrolled to any batch.
                            @endif
                        </p>
                    </div>
                    <div class="col-12">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Day</th>
                                        <th>Time</th>
                                        <th>Subject</th>
                                        <th>Teacher</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (count($schedules) > 0)
                                        @foreach ($schedules as $schedule)
                                            <tr @if ($schedule->day_name === $nextClassDay) class="highlighted-row" @endif>
                                                <td>{{ $schedule->day_name }}</td>
                                                <td>
                                                    {{ Carbon\Carbon::parse($schedule->start_time)->format('h:i A') }} -
                                                    {{ Carbon\Carbon::parse($schedule->end_time)->format('h:i A') }}
                                                </td>
                                                <td>{{ $schedule->subject_name }}</td>
                                                <td>{{ $schedule->teacher_name }}</td>
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

@push('js')
    <!-- Need: Apexcharts -->
    <script src="{{ asset('assets/extensions/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/static/js/pages/dashboard.js') }}"></script>
@endpush
