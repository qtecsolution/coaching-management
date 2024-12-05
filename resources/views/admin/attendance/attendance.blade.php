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
                                    {{-- <th>Comment</th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($attendance->records as $record)
                                    <tr>
                                        <td>{{ $record?->student->reg_id }}</td>
                                        <td>{{ $record?->student->name }}</td>
                                        <td>
                                            <span class="text-sm d-block">Absent: <span id="absent-count-{{ $record?->student->reg_id }}">{{ $record?->student?->currentBatch?->total_absent() }}</span></span>
                                            <span class="text-sm d-block">Present: <span id="present-count-{{ $record?->student->reg_id }}">{{ $record?->student?->currentBatch?->total_present() }}</span></span>
                                            <span class="text-sm d-block">Late: <span id="late-count-{{ $record?->student->reg_id }}">{{ $record?->student?->currentBatch?->total_late() }}</span></span>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <button class="btn btn-white border btn-sm btn-hover {{ $record->status == 0 ? 'bg-primary text-white' : '' }}"
                                                    data-reg-id="{{ $record?->student->reg_id }}"
                                                    onclick="attendance(0, {{ $attendance->id }}, this)"
                                                    id="absent-{{ $record?->student->reg_id }}">Absent</button>
                                                <button class="btn btn-white border btn-sm btn-hover {{ $record->status == 1 ? 'bg-primary text-white' : '' }}"
                                                    data-reg-id="{{ $record?->student->reg_id }}"
                                                    onclick="attendance(1, {{ $attendance->id }}, this)"
                                                    id="present-{{ $record?->student->reg_id }}">Present</button>
                                                <button class="btn btn-white border btn-sm btn-hover {{ $record->status == 2 ? 'bg-primary text-white' : '' }}"
                                                    data-reg-id="{{ $record?->student->reg_id }}"
                                                    onclick="attendance(2, {{ $attendance->id }}, this)"
                                                    id="late-{{ $record?->student->reg_id }}">Late</button>
                                            </div>
                                        </td>
                                        {{-- <td>
                                            <a href="" class="btn btn-sm btn-light">
                                                <i class="bi bi-chat-left-text"></i>
                                            </a>
                                        </td> --}}
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

@push('js')
    <script>
        function attendance(status, attendanceId, element) {
            let regId = element.dataset.regId;

            // Ensure the statusText matches the button ID convention
            let statusText;
            switch (status) {
                case 1:
                    statusText = 'present';
                    break;
                case 2:
                    statusText = 'late';
                    break;
                default:
                    statusText = 'absent';
                    break;
            }

            $.ajax({
                url: "{{ route('admin.attendance.store') }}",
                method: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    reg_id: regId,
                    status: status,
                    attendance_id: attendanceId,
                },
                success: function(response) {
                    console.log(response);

                    if (response.success) {
                        // Remove `bg-primary` from all buttons in the same group
                        $(`[data-reg-id="${regId}"]`).removeClass('bg-primary text-white');

                        // Add `bg-primary` to the clicked button
                        $(`#${statusText}-${regId}`).addClass('bg-primary text-white');

                        // Update the count
                        $(`#absent-count-${regId}`).text(response.attendance.total_absent);
                        $(`#present-count-${regId}`).text(response.attendance.total_present);
                        $(`#late-count-${regId}`).text(response.attendance.total_late);
                    } else {
                        alert(response.message || "Something went wrong.");
                    }
                },
                error: function() {
                    alert("An error occurred while saving attendance.");
                }
            });
        }
    </script>
@endpush
