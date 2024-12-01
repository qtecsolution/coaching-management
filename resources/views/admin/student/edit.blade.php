@extends('layouts.master')

@section('title', 'Edit Student')

@section('content')
    <div class="page-heading">
        <x-page-title title="Edit Student" subtitle="" pageTitle="Edit Student" />

        <section class="section">
            <div class="card">
                {{-- <div class="card-header"><h5 class="card-title"></h5></div> --}}
                <div class="card-body">
                    <form action="{{ route('admin.students.update', $student->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="batch" class="form-label">Batch</label>
                                    <select name="batch" class="form-control select2" id="batch">
                                        <option value="" selected disabled>Select a Batch</option>

                                        @foreach ($batches as $batch)
                                            <option value="{{ $batch->id }}"
                                                {{ old('batch', $student?->currentBatch?->batch?->id) == $batch->id ? 'selected' : '' }}>{{ $batch->name }}
                                            </option>
                                        @endforeach
                                    </select>

                                    @error('batch')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name" class="form-label">Name<sup class="text-danger">*</sup></label>
                                    <input type="text" name="name" id="name" placeholder="Name"
                                        class="form-control" value="{{ old('name', $student->user->name) }}" required>

                                    @error('name')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone" class="form-label">Phone<sup class="text-danger">*</sup></label>
                                    <input type="tel" name="phone" id="phone" placeholder="Phone"
                                        class="form-control" value="{{ old('phone', $student->user->phone) }}" required>

                                    @error('phone')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" name="email" id="email" placeholder="Email"
                                        class="form-control" value="{{ old('email', $student->user->email) }}">

                                    @error('email')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" name="password" id="password" placeholder="Password"
                                        class="form-control">

                                    @error('password')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="school" class="form-label">School Name</label>
                                    <input type="text" name="school_name" id="school" placeholder="School Name"
                                        class="form-control" value="{{ old('school_name', $student->school_name) }}">

                                    @error('school')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="class" class="form-label">Class</label>
                                    <input type="text" name="class" id="class" placeholder="Class"
                                        class="form-control" value="{{ old('class', $student->class) }}">

                                    @error('class')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="date_of_birth" class="form-label">Date of Birth<sup
                                            class="text-danger">*</sup></label>
                                    <input type="date" name="date_of_birth" id="date_of_birth"
                                        placeholder="Date of Birth" class="form-control"
                                        value="{{ old('date_of_birth', $student->date_of_birth) }}" required>

                                    @error('date_of_birth')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="father_name" class="form-label">Father Name<sup
                                            class="text-danger">*</sup></label>
                                    <input type="text" name="father_name" id="father_name" placeholder="Father Name"
                                        class="form-control" value="{{ old('father_name', $student->father_name) }}"
                                        required>

                                    @error('father_name')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="mother_name" class="form-label">Mother Name<sup
                                            class="text-danger">*</sup></label>
                                    <input type="text" name="mother_name" id="mother_name" placeholder="Mother Name"
                                        class="form-control" value="{{ old('mother_name', $student->mother_name) }}"
                                        required>

                                    @error('mother_name')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status" class="form-label">Status<sup
                                            class="text-danger">*</sup></label>
                                    <select name="status" id="status" class="form-control" required>
                                        <option value="1" {{ old('status', $student->status) == 1 ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ old('status', $student->status) == 0 ? 'selected' : '' }}>Inactive</option>
                                    </select>

                                    @error('status')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="address" class="form-label">Address<sup
                                            class="text-danger">*</sup></label>
                                    <textarea name="address" id="address" rows="5" class="form-control" placeholder="Address" required>{{ old('address', $student->address) }}</textarea>

                                    @error('address')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            @php
                                $emergency_contact = json_decode($student->emergency_contact, true);
                            @endphp

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="contact_name" class="form-label">Emergency Contact Name<sup
                                            class="text-danger">*</sup></label>
                                    <input type="text" name="contact_name" id="contact_name"
                                        value="{{ old('contact_name', $emergency_contact['name'] ?? '') }}"
                                        placeholder="Emergency Contact Name" class="form-control" required>

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
                                        value="{{ old('contact_phone', $emergency_contact['phone'] ?? '') }}"
                                        placeholder="Emergency Contact Phone" class="form-control" required>

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
        $(document).ready(function() {
            $('.select2').select2();
        });
    </script>
@endpush
