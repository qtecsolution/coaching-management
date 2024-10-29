@extends('layouts.master')

@section('title', 'Users')

@section('content')
    <div class="page-heading">
        <x-page-title title="Users" subtitle="" pageTitle="Users" />

        <!-- Basic Tables start -->
        <section class="section">
            <div class="card">
                {{-- <div class="card-header"><h5 class="card-title"></h5></div> --}}
                <div class="card-body">
                    <div class="my-4">
                        <button class="btn btn-secondary bg-primary" id="admin-btn" onclick="showUsers(event, 'admin')">Admin Users</button>
                        <button class="btn btn-secondary" id="teacher-btn" onclick="showUsers(event, 'teacher')">Teachers</button>
                    </div>

                    <div class="table-responsive">
                        <table class="table" id="table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Phone</th>
                                    <th>Email</th>
                                    <th>Role</th>
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
            ajax: "{{ route('admin.users.index') }}?user_type=admin",
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
                    data: 'role',
                    name: 'role'
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

        function showUsers(event, userType) {
            event.preventDefault();

            // Update the DataTable URL with the selected user type
            $("#table").DataTable().ajax.url('{{ route('admin.users.index') }}?user_type=' + userType).load();

            // Update the button text
            $("#admin-btn").removeClass("bg-primary");
            $("#teacher-btn").removeClass("bg-primary");
            $("#" + userType + "-btn").addClass("bg-primary");
        }
    </script>
@endpush
