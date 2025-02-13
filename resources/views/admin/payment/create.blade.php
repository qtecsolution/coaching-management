@extends('layouts.master')

@section('title', 'Add Payment')

@section('content')
    <div class="page-heading">
        <x-page-title title="Add Payment" :url="route('admin.payments.index')" />

        <section class="section">
            <div class="card">
                {{-- <div class="card-header"><h5 class="card-title"></h5></div> --}}
                <div class="card-body">
                    <form action="{{ route('admin.payments.store') }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="student" class="form-label">Students<sup
                                            class="text-danger">*</sup></label>
                                    <select name="student" id="student" class="form-control select2" required>
                                        <option value="" selected disabled>Select Student</option>
                                        @foreach ($students as $student)
                                            <option value="{{ $student->id }}">{{ $student->name }}</option>
                                        @endforeach
                                    </select>

                                    @error('student')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="batch" class="form-label">Batch<sup class="text-danger">*</sup></label>
                                    <select name="batch" id="batch" class="form-control select2" required>
                                        <option value="" selected disabled>Select Batch</option>
                                        @foreach ($batches as $batch)
                                            <option value="{{ $batch->id }}">{{ $batch->title }}</option>
                                        @endforeach
                                    </select>

                                    @error('batch')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="paid" class="form-label">Total Paid<sup
                                            class="text-danger">*</sup></label>
                                    <input type="number" name="paid" id="paid" placeholder="Total Paid"
                                        class="form-control" value="{{ old('paid') }}" readonly>

                                    @error('paid')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="due" class="form-label">Total Due<sup
                                            class="text-danger">*</sup></label>
                                    <input type="number" name="due" id="due" placeholder="Total Due"
                                        class="form-control" value="{{ old('due') }}" readonly>

                                    @error('due')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row d-none" id="payment_form">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="amount" class="form-label">Amount<sup class="text-danger">*</sup></label>
                                    <input type="number" name="amount" id="amount" placeholder="Amount"
                                        class="form-control" value="{{ old('amount') }}" required>

                                    @error('amount')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="date" class="form-label">Date<sup class="text-danger">*</sup></label>
                                    <input type="date" name="date" id="date" placeholder="Date"
                                        class="form-control" value="{{ old('date', date('Y-m-d')) }}" required>

                                    @error('amount')
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
            // Initialize Select2
            $('.select2').select2();

            $('#student').on('change', function() {
                var studentId = $(this).val();

                if (!studentId) {
                    toastr.error('Please select a student first.');
                    $('#batch').val('').trigger('change');
                }
            });

            $('#batch').on('change', function() {
                var batchId = $(this).val();
                var studentId = $('#student').val();

                if (!studentId) {
                    toastr.error("Please select a student first.");
                    // $(this).val('').trigger('change');
                    return;
                }

                if (batchId && studentId) {
                    getPaymentInfo(studentId, batchId);
                }
            });
        });

        function getPaymentInfo(studentId, batchId) {
            console.log("Fetching payment info for Batch ID:", batchId, "Student ID:", studentId);

            const paymentForm = $('#payment_form');
            paymentForm.removeClass('d-none');

            // Perform AJAX request
            $.ajax({
                url: `${window.location.origin}/admin/payments/get-info`,
                method: "POST",
                data: {
                    student_id: studentId,
                    batch_id: batchId,
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    console.log("Payment Info:", response.info);

                    
                },
                error: function(xhr, status, error) {
                    console.error("Error fetching payment info:", error);
                }
            });
        }
    </script>
@endpush
