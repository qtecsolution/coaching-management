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
                                    <label for="batch_id" class="form-label">Batch<sup class="text-danger">*</sup></label>
                                    <select name="batch_id" class="form-control select2" id="batch_id" required>
                                        <option value="" selected disabled>--Select a Batch--</option>
                                        @foreach ($batches as $batch)
                                            <option {{ old('batch_id', $request->batch_id) == $batch->id ? 'selected' : '' }}
                                                value="{{ $batch->id }}" data-tuition-fee="{{ $batch->tuition_fee }}"
                                                data-students="{{ optional($batch->students)->toJson() }}">
                                                {{ $batch->name }}
                                            </option>
                                        @endforeach
                                    </select>

                                    @error('batch_id')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="student_batch_id" class="form-label">Student<sup
                                            class="text-danger">*</sup></label>
                                    <select name="student_batch_id" class="form-control select2" id="student_batch_id"
                                        required>
                                        <option value="" selected disabled>--Select a Student--</option>
                                    </select>
                                    @error('student_batch_id')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="amount" class="form-label">Amount<sup class="text-danger">*</sup></label>
                                    <input type="number" readonly name="amount" id="amount" placeholder="Enter Amount"
                                        class="form-control" value="{{ old('amount') }}" required>
                                    @error('amount')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="month" class="form-label">Month<sup class="text-danger">*</sup></label>
                                    <input type="month" name="month" id="month" placeholder="Enter Month"
                                        class="form-control" value="{{ old('month', $request->month) }}" required>

                                    @error('month')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="form-group">
                                        <label for="date" class="form-label">Date</label>
                                        <input type="date" name="date" id="date"
                                            class="form-control flatpickr-date" value="{{ old('date', date('Y-m-d')) }}">

                                        @error('date')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="payment_method" class="form-label">--Payment Method--<sup
                                            class="text-danger">*</sup></label>
                                    <select name="payment_method" class="form-control form-select" id="payment_method" required>
                                        <option selected disabled>Select a Payment Method</option>
                                        @foreach ($paymentMethods as $paymentMethod)
                                        <option {{ old('payment_method') == $paymentMethod ? 'selected' : '' }}>{{ $paymentMethod }}</option>
                                        @endforeach
                                    </select>

                                    @error('payment_method')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="transaction_id" class="form-label">Transaction ID</label>
                                    <input type="text" name="transaction_id" id="transaction_id"
                                        placeholder="Enter Transaction ID" class="form-control"
                                        value="{{ old('transaction_id') }}">

                                    @error('transaction_id')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="note" class="form-label">Note</label>
                                    <input type="text" name="note" placeholder="Enter Note" value="{{ old('note') }}" id="note" class="form-control">

                                    @error('note')
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
            const batchSelect = document.getElementById('batch_id');
            const studentSelect = document.getElementById('student_batch_id');
            const amountInput = document.getElementById('amount');

            // Check if there's an old batch ID and select it
            const oldBatchId = "{{ old('batch_id', $request->batch_id) }}";
            if (oldBatchId) {
                batchSelect.value = oldBatchId;
                // Trigger change event to update students and tuition fee
                const selectedOption = batchSelect.options[batchSelect.selectedIndex];
                const tuitionFee = selectedOption.getAttribute('data-tuition-fee');
                amountInput.value = tuitionFee;

                // Get students from the selected batch
                const students = JSON.parse(selectedOption.getAttribute('data-students'));
                populateStudentsDropdown(students);

                // Check for old reg_id and select it
                const oldStudentId = "{{ old('student_batch_id', $request->student_batch_id) }}";
                if (oldStudentId) {
                    studentSelect.value = oldStudentId;
                }
            }

            batchSelect.addEventListener('change', function() {
                const selectedOption = batchSelect.options[batchSelect.selectedIndex];

                // Get tuition fee from the selected batch
                const tuitionFee = selectedOption.getAttribute('data-tuition-fee');
                amountInput.value = tuitionFee;

                // Get students from the selected batch
                const students = JSON.parse(selectedOption.getAttribute('data-students'));
                populateStudentsDropdown(students);
            });

            function populateStudentsDropdown(students) {
                studentSelect.innerHTML = '<option value="" selected disabled>Select a Student</option>';
                students.forEach(student => {
                    const option = document.createElement('option');
                    option.value = student.id;
                    option.textContent = student.student.name + ' (' + student.student.reg_id + ')';
                    studentSelect.appendChild(option);
                });
            }
        });
    </script>
@endpush
