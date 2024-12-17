@extends('layouts.master')

@section('title', 'Users')

@section('content')
    <div class="page-heading">
        <x-page-title title="Roles" subtitle="" pageTitle="Roles" />

        <section class="section">
            <div class="card">
                <div class="card-header">
                    <div class="text-end">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addRoleModal">
                            Add Role
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="xenTable bg-white w-100 border-0">
                        <div class="">
                            <table class="table" style="width: 100%">
                                <thead class="xenTable-header">
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Total Permissions</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($roles as $key => $role)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td> {{ $role->name }} </td>
                                            <td>{{ $role->permissions->count() }}</td>
                                            <td>
                                                <div class="btn-group">
                                                    @can('role_permissions')
                                                        <a href="{{ route('admin.users.roles.show', $role->id) }}"
                                                            class="btn btn-sm btn-primary">
                                                            <i class="bi bi-gear"></i>
                                                        </a>
                                                    @endcan
                                                    @can('delete_role')
                                                        <a href="javascript:void(0)" onclick="deleteResource('{{ route('admin.users.roles.destroy', $role->id) }}')"
                                                            class="btn btn-sm btn-danger">
                                                            <i class="bi bi-trash"></i>
                                                        </a>
                                                    @endcan
                                                    @can('update_role')
                                                        <button type="button" class="btn btn-sm btn-primary"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#editModal{{ $role->id }}">
                                                            <i class="bi bi-pencil"></i>
                                                        </button>
                                                    @endcan
                                                </div>

                                                <!-- Edit Role Modal -->
                                                <div class="modal fade" id="editModal{{ $role->id }}" tabindex="-1"
                                                    aria-labelledby="editModalLabel{{ $role->id }}" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <form action="{{ route('admin.users.roles.update', $role->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title fs-5" id="exampleModalLabel">
                                                                        Edit Role
                                                                    </h5>
                                                                    <button type="button" class="close"
                                                                        data-bs-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="form-group">
                                                                        <label for="name-{{ $role->id }}"
                                                                            class="form-label">Name:</label>
                                                                        <input type="text" name="name"
                                                                            value="{{ $role->name }}"
                                                                            id="name-{{ $role->id }}"
                                                                            class="form-control @error('name') border border-danger @enderror">
                                                                        @error('name')
                                                                            <small
                                                                                class="text-danger">{{ $message }}</small>
                                                                        @enderror
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="submit"
                                                                        class="btn btn-primary btn-sm">Save</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="">
                                {{ $roles->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Add Role Modal -->
    <div class="modal fade" id="addRoleModal" tabindex="-1" aria-labelledby="addRoleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('admin.users.roles.store') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addRoleModalLabel">
                            Add Role
                        </h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name" class="form-label">Name <sup class="text-danger">*</sup>:</label>
                            <input type="text" name="name" id="name"
                                class="form-control @error('name') border border-danger @enderror">
                            @error('name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary btn-sm" type="submit">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
