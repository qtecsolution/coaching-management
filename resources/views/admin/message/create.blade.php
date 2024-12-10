@extends('layouts.master')

@section('title', 'Send Message')

@section('content')
    <div class="page-heading">
        <x-page-title title="Send Message" subtitle="" pageTitle="Send Message" />

        <section class="section">
            <div class="card">
                {{-- <div class="card-header"><h5 class="card-title"></h5></div> --}}
                <div class="card-body">
                    <form action="{{ route('admin.messages.store') }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-12">
                                <div class="alert alert-info">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-info-circle-fill" viewBox="0 0 16 16">
                                        <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16m.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2" />
                                    </svg>
                                    Select <b>Student</b> to message an individual student or <b>Batch</b> to message all students in a batch.
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="student" class="form-label">Student</label>
                                    <select name="student" id="student" class="form-control select2">
                                        <option value="" selected>--Select a Student--</option>
                                        @foreach ($students as $student)
                                            <option value="{{ $student->id }}"
                                                {{ old('student') == $student->id ? 'selected' : '' }}>
                                                {{ $student->name }} ({{ $student->reg_id }})</option>
                                        @endforeach
                                    </select>

                                    @error('student')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="batch" class="form-label">Batch</label>
                                    <select name="batch" id="batch" class="form-control select2">
                                        <option value="" selected>--Select a Batch--</option>
                                        @foreach ($batches as $batch)
                                            <option value="{{ $batch->id }}"
                                                {{ old('batch') == $batch->id ? 'selected' : '' }}>
                                                {{ $batch->name }}</option>
                                        @endforeach
                                    </select>

                                    @error('batch')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="message" class="form-label">Message</label>
                                    <textarea name="message" id="message" rows="5" class="form-control" placeholder="Enter your Message">{{ old('message') }}</textarea>

                                    @error('note')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-12 text-end mt-2">
                            <button type="submit" class="btn btn-primary">Send</button>
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
