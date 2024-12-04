@extends('layouts.master')

@section('title', 'Dashboard')

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
                            @if (isset($user?->student?->batch?->name))
                                You're enrolled to the <b>{{ $user->student->batch->name }}</b> batch. See the following
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
                                    @php
                                        $schedules = $user?->student?->currentbatch->batch?->batch_days ?? [];
                                    @endphp
                                
                                    @if (count($schedules) > 0)
                                        @foreach ($schedules as $schedule)
                                            <tr>
                                                <td>{{ $schedule->day_name }}</td>
                                                <td>{{ \Carbon\Carbon::parse($schedule->start_time)->format('h:i A') }} -
                                                    {{ \Carbon\Carbon::parse($schedule->end_time)->format('h:i A') }}</td>
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
