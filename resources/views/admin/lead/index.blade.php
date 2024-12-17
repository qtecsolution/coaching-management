@extends('layouts.master')

@section('title', 'Leads')

@section('content')
    <div class="page-heading">
        <x-page-title title="Leads" subtitle="" pageTitle="Leads" />

        <!-- Basic Tables start -->
        <section class="section">
            <div class="card">
                {{-- <div class="card-header"><h5 class="card-title"></h5></div> --}}
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table" id="table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Phone</th>
                                    <th>Email</th>
                                    <th>School Name</th>
                                    <th>Note</th>
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
            ajax: "{{ route('admin.leads.index') }}",
            columns: [{
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
                    data: 'class',
                    name: 'class'
                },
                {
                    data: 'note',
                    name: 'note'
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
        });
    </script>
@endpush
