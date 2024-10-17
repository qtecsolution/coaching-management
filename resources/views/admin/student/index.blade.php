@extends('layouts.master')

@section('title', 'Student List')

@push('css')
    <style>
        table.dataTable {
            table-layout: auto !important;
        }
    </style>
@endpush

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
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Phone</th>
                                    <th>Email</th>
                                    <th>School</th>
                                    <th>Father Name</th>
                                    <th>Mother Name</th>
                                    <th>Emergency Contact</th>
                                    <th>Address</th>
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
            ajax: "{{ route('admin.students.index') }}",
            columns: [{
                    data: 'student_id',
                    name: 'student_id'
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'phone',
                    name: 'phone'
                },
                {
                    data: 'email',
                    name: 'email'
                },
                {
                    data: 'school_name',
                    name: 'school_name'
                },
                {
                    data: 'father_name',
                    name: 'father_name'
                },
                {
                    data: 'mother_name',
                    name: 'mother_name'
                },
                {
                    data: 'emergency_contact',
                    name: 'emergency_contact'
                },
                {
                    data: 'address',
                    name: 'address'
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
