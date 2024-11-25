@extends('layouts.master')

@section('title', 'Add Payment')

@section('content')
<div class="page-heading">
    <x-page-title title="Add Payment" subtitle="" pageTitle="Add Payment" />

    <section class="section">
        <div class="card">
            {{-- <div class="card-header"><h5 class="card-title"></h5></div> --}}
            <div class="card-body">
                <form action="{{ route('admin.payments.store') }}" method="POST">
                    @csrf

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="batch" class="form-label">Batch</label>
                                <select name="batch" class="form-control" id="batch">
                                    <option value="" selected disabled>Select a Batch</option>

                                    @foreach ($batches as $batch)
                                    <option
                                        value="{{ $batch->id }}"
                                        data-tuition-fee="{{ $batch->tuition_fee }}"
                                        data-students="{{ optional($batch->students)->toJson() }}">
                                        {{ $batch->name }}
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
                                <label for="student_id" class="form-label">Student</label>
                                <select name="student_id" class="form-control select2" id="student_id">
                                    <option value="" selected disabled>Select a Student</option>
                                </select>
                                @error('student_id')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="amount" class="form-label">Amount</label>
                                <input type="number" name="amount" id="amount" placeholder="amount" class="form-control" value="{{ old('amount') }}" required>
                                @error('amount')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="month" class="form-label">Month<sup
                                        class="text-danger">*</sup></label>
                                <input type="month" name="month" id="month"
                                    placeholder="Enter Month" class="form-control" value="{{ old('month') }}"
                                    required>

                                @error('month')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="date_of_birth" class="form-label">Date<sup
                                            class="text-danger">*</sup></label>
                                    <input type="date" name="date_of_birth" id="date_of_birth"
                                        placeholder="Date of Birth" class="form-control" value="{{ old('date_of_birth') }}"
                                        required>

                                    @error('date_of_birth')
                                    <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="mother_name" class="form-label">Payment Method</label>
                                <select name="batch" class="form-control" id="batch">
                                    <option value="" selected disabled>Select a Method</option>
                                    <option>Cash</option>
                                    <option>Bank</option>
                                    <option>Bkash</option>
                                </select>

                                @error('mother_name')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="mother_name" class="form-label">Transaction Id<sup
                                        class="text-danger">*</sup></label>
                                <input type="text" name="mother_name" id="mother_name" placeholder="Mother Name"
                                    class="form-control" value="{{ old('mother_name') }}" required>

                                @error('mother_name')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="address" class="form-label">Note<sup
                                        class="text-danger">*</sup></label>
                                <textarea name="address" id="address" rows="5" class="form-control" placeholder="Address" required>{{ old('address') }}</textarea>

                                @error('address')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12 text-end mt-2">
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
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
    document.addEventListener('DOMContentLoaded', function() {
        const batchSelect = document.getElementById('batch');
        const studentSelect = document.getElementById('student_id');
        const amountInput = document.getElementById('amount');

        batchSelect.addEventListener('change', function() {
            const selectedOption = batchSelect.options[batchSelect.selectedIndex];

            // Get tuition fee from the selected batch
            const tuitionFee = selectedOption.getAttribute('data-tuition-fee');
            amountInput.value = tuitionFee;

            // Get students from the selected batch
            const students = JSON.parse(selectedOption.getAttribute('data-students'));
            console.log(students);
            // Populate the students dropdown
            studentSelect.innerHTML = '<option value="" selected disabled>Select a Student</option>';
            students.forEach(student => {
                const option = document.createElement('option');
                option.value = student.id;
                option.textContent = student.name;
                studentSelect.appendChild(option);
            });
        });
    });
</script>
@endpush