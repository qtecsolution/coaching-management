@extends('layouts.master')

@section('title', 'User List')

@section('content')
    <div class="page-heading">
        <x-page-title title="User List" />

        <!-- Basic Tables start -->
        <section class="section">
            <div class="card">
                <div class="card-body">
                    <div class="my-4">
                        <button class="btn btn-secondary text-white" id="admin-btn"
                            onclick="showUsers(event, 'admin')">Admin Users</button>
                        <button class="btn btn-secondary text-white" id="teacher-btn"
                            onclick="showUsers(event, 'teacher')">Teachers</button>
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
        var activeUserType = 'admin';
        var currentFragment = window.location.hash.slice(1) || activeUserType;

        var datatable = $("#table").DataTable({
            responsive: true,
            serverSide: true,
            processing: true,
            ajax: `{{ route('admin.users.index') }}?user_type=${currentFragment}`,
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
                }
            ]
        });

        $(`#${currentFragment}-btn`).removeClass("btn-secondary").addClass("bg-primary");

        function showUsers(event, userType) {
            event.preventDefault();

            activeUserType = userType;
            window.location.hash = userType;

            datatable.ajax.url(`{{ route('admin.users.index') }}?user_type=${userType}`).load();

            $("#admin-btn, #teacher-btn").removeClass("bg-primary").addClass("btn-secondary");
            $(`#${userType}-btn`).removeClass("btn-secondary").addClass("bg-primary");
        }
    </script>
@endpush
