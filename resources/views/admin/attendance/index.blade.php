@extends('layouts.master')

@section('title', 'Attendance List')

@section('content')
<div class="page-heading">
    <x-page-title title="Attendance List" subtitle="" pageTitle="Attendance List" />

    <!-- Basic Tables start -->
    <section class="section">
        <div class="card">
            {{-- <div class="card-header"><h5 class="card-title"></h5></div> --}}
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table" id="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Batch</th>
                                <th>Date</th>
                                <th>Total Student</th>
                                <th>Total Absent</th>
                                <th>Total Present</th>
                                <th>Total Late</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
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
    let datatable = $("#table").DataTable({
        responsive: true,
        serverSide: true,
        processing: true,
        ajax: "{{ route('admin.attendance.index') }}",
        columns: [{
                data: 'DT_RowIndex',
                name: 'DT_RowIndex'
            },
            {
                data: 'batch_name',
                name: 'batch_name'
            },
            {
                data: 'date',
                name: 'date'
            },
            {
                data: 'total_student',
                name: 'total_student'
            },
            {
                data: 'total_absent',
                name: 'total_absent'
            },
            {
                data: 'total_present',
                name: 'total_present'
            },
            {
                data: 'total_late',
                name: 'total_late'
            },
            {
                data: 'action',
                name: 'action'
            },
        ],
    })
</script>
@endpush