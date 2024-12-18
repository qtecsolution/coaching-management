@extends('layouts.master')

@section('title', $role->name . ' Permissions')

@section('content')
    <div class="page-heading">
        <x-page-title title="{{ $role->name }} Permissions" subtitle="" pageTitle="{{ $role->name }} Permissions" />

        <!-- Basic Tables start -->
        <section class="section">
            <div class="card">
                {{-- <div class="card-header"><h5 class="card-title"></h5></div> --}}
                <div class="card-body">
                    <form action="{{ route('admin.users.roles.updatePermissions', $role->id) }}" method="POST" class="row">
                        @csrf

                        <div class="col-12 mb-3">
                            <div class="d-flex justify-content-center flex-wrap">
                                <button type="button" class="btn btn-danger btn-sm m-1 toggle-all"
                                    onclick="checkAll(this, 'all')">Check All</button>
                                <button type="button" class="btn btn-success btn-sm m-1"
                                    onclick="checkAll(this, 'view')">View Permissions</button>
                                <button type="button" class="btn btn-primary btn-sm m-1"
                                    onclick="checkAll(this, 'create')">Create Permissions</button>
                                <button type="button" class="btn btn-info btn-sm m-1"
                                    onclick="checkAll(this, 'update')">Update Permissions</button>
                                <button type="button" class="btn btn-warning btn-sm m-1"
                                    onclick="checkAll(this, 'delete')">Delete Permissions</button>
                            </div>
                        </div>

                        @php
                            function getPermissionType($permissionName)
                            {
                                $firstLetter = substr($permissionName, 0, 1);
                                switch ($firstLetter) {
                                    case 'v':
                                        return 'view';
                                    case 'c':
                                        return 'create';
                                    case 'u':
                                        return 'update';
                                    default:
                                        return 'delete';
                                }
                            }
                        @endphp

                        @foreach ($permissions as $permission)
                            <div class="col-md-3 border-bottom">
                                <div class="my-3">
                                    <input class="checkbox" type="checkbox" id="permission-{{ $permission->id }}"
                                        name="permissions[]" value="{{ $permission->name }}"
                                        data-type="{{ getPermissionType($permission->name) }}"
                                        {{ in_array($permission->id, $rolePermissions) ? 'checked' : '' }}>
                                    <label for="permission-{{ $permission->id }}" class="mb-0">
                                        <span class="checkbox-text">{{ formatSlug($permission->name) }}</span>
                                    </label>
                                </div>
                            </div>
                        @endforeach


                        <div class="col-12 mt-3">
                            <button type="submit" class="btn btn-primary btn-sm float-right">Update Permissions</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
        <!-- Basic Tables end -->
    </div>
@endsection

@push('js')
    <script>
        function checkAll(event, type) {
            var checkboxes;

            if (type === 'all') {
                // Select all checkboxes
                checkboxes = $('.checkbox');
            } else {
                // Filter checkboxes by data-type
                checkboxes = $('.checkbox').filter(function() {
                    return $(this).data('type') === type;
                });
            }

            // Check if any checkbox is checked
            var anyChecked = checkboxes.filter(':checked').length > 0;

            if (type === 'all') {
                // Toggle the checked state
                checkboxes.prop('checked', !anyChecked);

                // Update button text based on whether any checkbox is checked
                if (anyChecked) {
                    $('.toggle-all').text('Check All');
                } else {
                    $('.toggle-all').text('Uncheck All');
                }
            } else {
                // Toggle the checked state
                checkboxes.prop('checked', true);

                // Update button text
                $('.toggle-all').text('Uncheck All');
            }
        }
    </script>
@endpush
