@extends('layouts.master')

@section('title', 'Batch List')

@section('content')
    <div class="page-heading">
        <x-page-title title="Student List" subtitle="" pageTitle="Student List" />

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
                                    <th>Name</th>
                                    <th>Subject</th>
                                    <th>Class</th>
                                    <th>Weekly Classes</th>
                                    <th>Total Students</th>
                                    <th>Status</th>
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
            autoWidth: false,
            ajax: "{{ route('admin.batches.index') }}",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'subject',
                    name: 'subject'
                },
                {
                    data: 'class',
                    name: 'class'
                },
                {
                    data: 'weekly_classes',
                    name: 'weekly_classes'
                },
                {
                    data: 'total_students',
                    name: 'total_students'
                },
                {
                    data: 'status',
                    name: 'status'
                },
                {
                    data: 'action',
                    name: 'action'
                },
            ],
        })
    </script>
@endpush
