@extends('layouts.master')

@section('title', 'Edit User')

@section('content')
    <div class="page-heading">
        <x-page-title title="Edit User" subtitle="" pageTitle="Edit User" />

        <section class="section">
            <div class="card">
                {{-- <div class="card-header"><h5 class="card-title"></h5></div> --}}
                <div class="card-body">
                    <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="is_admin" value="1">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" name="name" id="name" placeholder="Name"
                                        class="form-control" value="{{ $user->name }}">

                                    @error('name')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone" class="form-label">Phone</label>
                                    <input type="tel" name="phone" id="phone" placeholder="Phone"
                                        class="form-control" value="{{ old('phone', $user->phone) }}">

                                    @error('phone')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" name="email" id="email" placeholder="Email"
                                        class="form-control" value="{{ old('email', $user->email) }}">

                                    @error('email')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" name="password" id="password" placeholder="Password"
                                        class="form-control" value="{{ old('password') }}">

                                    @error('password')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="role" class="form-label">Role</label>
                                    <select name="role" id="role" class="form-select form-control">
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->name }}"
                                                {{ in_array($role->name, $user->getRoleNames()->toArray()) ? 'selected' : '' }}>
                                                {{ $role->name }}</option>
                                        @endforeach
                                    </select>

                                    @error('role')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status" class="form-label">Status<sup class="text-danger">*</sup></label>
                                    <select name="status" id="status" class="form-control" required>
                                        <option value="1" {{ old('status', $user->status) == 1 ? 'selected' : '' }}>
                                            Active</option>
                                        <option value="0" {{ old('status', $user->status) == 0 ? 'selected' : '' }}>
                                            Inactive</option>
                                    </select>

                                    @error('status')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="user_type" class="form-label">User Type<sup
                                            class="text-danger">*</sup></label>
                                    <select name="user_type" id="user_type" class="form-select form-control" required>
                                        <option value="admin"
                                            {{ old('user_type', $user->user_type) == 'admin' ? 'selected' : '' }}>Admin
                                        </option>
                                        <option value="teacher"
                                            {{ old('user_type', $user->user_type) == 'teacher' ? 'selected' : '' }}>Teacher
                                        </option>
                                    </select>

                                    @error('user_type')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row d-none" id="teacher_info">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="school" class="form-label">School Name</label>
                                    <input type="text" name="school_name" value="{{ old('school_name', $user->teacher->school_name) }}"
                                        id="school" class="form-control" placeholder="School Name">

                                    @error('school_name')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="school" class="form-label">NID Number<sup
                                            class="text-danger">*</sup></label>
                                    <input type="number" name="nid_number" value="{{ old('nid_number', $user->teacher->nid_number) }}"
                                        id="school" class="form-control" placeholder="NID Number">

                                    @error('nid_number')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="address" class="form-label">Address<sup
                                            class="text-danger">*</sup></label>
                                    <textarea name="address" id="address" rows="5" class="form-control" placeholder="Address">{{ old('address', $user->teacher->address) }}</textarea>

                                    @error('address')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            @php
                                $emergency_contact = json_decode($user->teacher->emergency_contact);
                            @endphp
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="contact_name" class="form-label">Emergency Contact Name<sup
                                            class="text-danger">*</sup></label>
                                    <input type="text" name="contact_name" id="contact_name"
                                        value="{{ old('contact_name', $emergency_contact->name ?? '') }}" placeholder="Emergency Contact Name"
                                        class="form-control">

                                    @error('contact_name')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="contact_phone" class="form-label">Emergency Contact Phone<sup
                                            class="text-danger">*</sup></label>
                                    <input type="tel" name="contact_phone" id="contact_phone"
                                        value="{{ old('contact_phone', $emergency_contact->phone ?? ''  ) }}" placeholder="Emergency Contact Phone"
                                        class="form-control">

                                    @error('contact_phone')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="col-12 text-end mt-2">
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('js')
    <script>
        $('#user_type').on('change', function() {
            if ($(this).val() == 'teacher') {
                $('#teacher_info').removeClass('d-none');
            } else {
                $('#teacher_info').addClass('d-none');
            }
        });

        @if ($user->user_type == 'teacher')
            $('#teacher_info').removeClass('d-none');
        @endif
    </script>
@endpush
