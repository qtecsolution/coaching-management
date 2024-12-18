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
                        <input type="hidden" name="user_type" value="{{ $user->user_type }}">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name" class="form-label">Name<sup class="text-danger">*</sup></label>
                                    <input type="text" name="name" id="name" placeholder="Name"
                                        class="form-control" value="{{ $user->name }}">

                                    @error('name')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone" class="form-label">Phone<sup class="text-danger">*</sup></label>
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
                                    <label for="password" class="form-label">Password<sup class="text-danger">*</sup></label>
                                    <input type="password" name="password" id="password" placeholder="Password"
                                        class="form-control" value="{{ old('password') }}">

                                    @error('password')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="role" class="form-label">Role<sup class="text-danger">*</sup></label>
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
                                    <select name="status" id="status" class="form-control form-select">
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
                        </div>

                        <div class="row d-none" id="teacher_info">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="qualification" class="form-label">
                                        Qualification<sup class="text-danger">*</sup>
                                        <x-tooltips message="Enter user's educational qualification here." position="top" />
                                    </label>
                                    <input type="text" name="qualification"
                                        value="{{ old('qualification', @$user->teacher->qualification) }}" id="qualification"
                                        class="form-control" placeholder="Qualification">

                                    @error('qualification')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="school" class="form-label">NID Number<sup
                                            class="text-danger">*</sup></label>
                                    <input type="number" name="nid_number"
                                        value="{{ old('nid_number', @$user->teacher->nid_number) }}" id="school"
                                        class="form-control" placeholder="NID Number">

                                    @error('nid_number')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="address" class="form-label">Address<sup
                                            class="text-danger">*</sup></label>
                                    <textarea name="address" id="address" rows="5" class="form-control" placeholder="Address">{{ old('address', @$user->teacher->address) }}</textarea>

                                    @error('address')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            @php
                                if (isset($user->teacher)) {
                                    $emergency_contact = json_decode($user->teacher->emergency_contact);
                                } else {
                                    $emergency_contact = (object) [
                                        'name' => '',
                                        'phone' => '',
                                    ];
                                }
                            @endphp
                            <div class="col-12">
                                <fieldset class="border rounded p-3">
                                    <legend>Emergency Contact</legend>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="contact_name" class="form-label">Name<sup
                                                        class="text-danger">*</sup></label>
                                                <input type="text" name="contact_name" id="contact_name"
                                                    value="{{ old('contact_name', $emergency_contact->name) }}" placeholder="Name"
                                                    class="form-control">

                                                @error('contact_name')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="contact_phone" class="form-label">Phone<sup
                                                        class="text-danger">*</sup></label>
                                                <input type="tel" name="contact_phone" id="contact_phone"
                                                    value="{{ old('contact_phone', $emergency_contact->phone) }}" placeholder="Phone"
                                                    class="form-control">

                                                @error('contact_phone')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
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
        $(document).ready(function() {
            // Attach event listener to radio buttons
            $('#user_type_yes, #user_type_no').on('change', function() {
                // Toggle visibility of #teacher_info based on the selected radio button
                if ($('#user_type_yes').is(':checked')) {
                    $('#teacher_info').removeClass('d-none');
                } else {
                    $('#teacher_info').addClass('d-none');
                }
            });

            // Trigger the change event on page load to set the initial state
            $('#user_type_yes, #user_type_no').trigger('change');

            // Check the radio button based on the user_type
            @if ($user->user_type == 'teacher')
                $('#teacher_info').removeClass('d-none');
            @endif
        });
    </script>
@endpush
