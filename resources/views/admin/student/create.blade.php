@extends('layouts.master')

@section('title', 'Add Student')

@section('content')
    <div class="page-heading">
        <x-page-title title="Add Student" subtitle="" pageTitle="Add Student" />

        <section class="section">
            <div class="card">
                {{-- <div class="card-header"><h5 class="card-title"></h5></div> --}}
                <div class="card-body">
                    <form action="{{ route('admin.students.store') }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="batch" class="form-label">Batch<sup class="text-danger">*</sup></label>
                                    <select name="batch" class="form-control select2" id="batch" required>
                                        <option value="" selected disabled>Select a Batch</option>

                                        @foreach ($batches as $batch)
                                            <option value="{{ $batch->id }}"
                                                {{ old('batch') == $batch->id ? 'selected' : '' }}>{{ $batch->name }}
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
                                        class="form-control" value="{{ old('name') }}" required>

                                    @error('name')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone" class="form-label">Phone<sup class="text-danger">*</sup></label>
                                    <input type="tel" name="phone" id="phone" placeholder="Phone"
                                        class="form-control" value="{{ old('phone') }}" required>

                                    @error('phone')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" name="email" id="email" placeholder="Email"
                                        class="form-control" value="{{ old('email') }}">

                                    @error('email')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password" class="form-label">Password<sup
                                            class="text-danger">*</sup></label>
                                    <input type="password" name="password" id="password" placeholder="Password"
                                        class="form-control" required>

                                    @error('password')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="qualification" class="form-label">Qualification</label>
                                    <input type="text" name="qualification" id="qualification" placeholder="Qualification"
                                        class="form-control" value="{{ old('qualification') }}">

                                    @error('qualification')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="occupation" class="form-label">Occupation</label>
                                    <input type="text" name="occupation" id="occupation" placeholder="Occupation"
                                        class="form-control" value="{{ old('occupation') }}">

                                    @error('occupation')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="school" class="form-label">NID Number<sup
                                            class="text-danger">*</sup></label>
                                    <input type="number" name="nid_number" value="{{ old('nid_number') }}"
                                        id="school" class="form-control" placeholder="NID Number">

                                    @error('nid_number')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="date_of_birth" class="form-label">Date of Birth<sup
                                            class="text-danger">*</sup></label>
                                    <input type="date" name="date_of_birth" id="date_of_birth"
                                        placeholder="Date of Birth" class="form-control" value="{{ old('date_of_birth') }}"
                                        required>

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
                                        class="form-control" value="{{ old('father_name') }}" required>

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
                                        class="form-control" value="{{ old('mother_name') }}" required>

                                    @error('mother_name')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="address" class="form-label">Address<sup
                                            class="text-danger">*</sup></label>
                                    <textarea name="address" id="address" rows="5" class="form-control" placeholder="Address" required>{{ old('address') }}</textarea>

                                    @error('address')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <fieldset class="border rounded p-3">
                                    <legend>Emergency Contact</legend>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="contact_name" class="form-label">Name<sup
                                                        class="text-danger">*</sup></label>
                                                <input type="text" name="contact_name" id="contact_name"
                                                    value="{{ old('contact_name') }}" placeholder="Name"
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
                                                    value="{{ old('contact_phone') }}" placeholder="Phone"
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
            $('.select2').select2();
        });
    </script>
@endpush
