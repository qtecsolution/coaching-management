@extends('layouts.master')

@section('title', 'Attendance')

@push('css')
    <style>
        .btn-hover:hover {
            background-color: #435EBE !important;
            color: #FFFFFF !important;
        }
    </style>
@endpush

@section('content')
    <div class="page-heading">
        <x-page-title title="Attendance" subtitle="" pageTitle="Attendance" />

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
                                    <th>Comment</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($students as $student)
                                    <tr>
                                        <td>{{ $student?->student->reg_id }}</td>
                                        <td>{{ $student?->student->name }}</td>
                                        <td>
                                            <span class="text-sm d-block">Absent: {{ $student?->absent ?? 0 }}</span>
                                            <span class="text-sm d-block">Present: {{ $student?->absent ?? 0 }}</span>
                                            <span class="text-sm d-block">Late: {{ $student?->absent ?? 0 }}</span>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <button class="btn btn-white border btn-sm btn-hover">Absent</button>
                                                <button class="btn btn-white border btn-sm btn-hover">Present</button>
                                                <button class="btn btn-white border btn-sm btn-hover">Late</button>
                                            </div>
                                        </td>
                                        <td>
                                            <a href="" class="btn btn-sm btn-light">
                                                <i class="bi bi-chat-left-text"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center">No student found.</td>
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

@push('js')
    <script></script>
@endpush
