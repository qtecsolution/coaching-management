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
                                    <label for="student_id" class="form-label">Student<sup
                                            class="text-danger">*</sup></label>
                                    <select name="student_id" id="student" class="form-control select2" required>
                                        <option value="" selected disabled>Select Student</option>
                                        @foreach ($students as $student)
                                            <option value="{{ $student->id }}"
                                                {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                                {{ $student->name }}
                                            </option>
                                        @endforeach
                                    </select>

                                    @error('student_id')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="batch_id" class="form-label">Batch<sup class="text-danger">*</sup></label>
                                    <select name="batch_id" id="batch" class="form-control select2" required>
                                        <option value="" selected disabled>Select Batch</option>
                                        @foreach ($batches as $batch)
                                            <option value="{{ $batch->id }}"
                                                {{ old('batch_id') == $batch->id ? 'selected' : '' }}>
                                                {{ $batch->title }}
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
                                        class="form-control" value="{{ old('amount') }}" required min="0">
                                    <small class="text-muted">Maximum allowed: <span id="max_amount">0</span></small>

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

                                    @error('date')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="note" class="form-label">Note</label>
                                    <textarea name="note" id="note" class="form-control" rows="3" placeholder="Payment note">{{ old('note') }}</textarea>

                                    @error('note')
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

            $('#student').on('change', function() {
                var studentId = $(this).val();
                $('#payment_form').addClass('d-none');
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
                    $(this).val('').trigger('change');
                    return;
                }

                if (batchId && studentId) {
                    getPaymentInfo(studentId, batchId);
                }
            });

            // Validate amount input
            $('#amount').on('input', function() {
                const maxAmount = parseFloat($('#max_amount').text());
                const amount = parseFloat($(this).val());

                if (amount > maxAmount) {
                    toastr.error('Amount cannot exceed due amount');
                    $(this).val(maxAmount);
                }
            });
        });

        function getPaymentInfo(studentId, batchId) {
            const paymentForm = $('#payment_form');

            $.ajax({
                url: `${window.location.origin}/admin/payments/get-info`,
                method: "POST",
                data: {
                    student_id: studentId,
                    batch_id: batchId,
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    if (response.success && response.info) {
                        const info = response.info;
                        $('#paid').val(info.total_paid || 0);
                        $('#due').val(info.total_due || 0);
                        $('#max_amount').text(info.total_due || 0);
                        paymentForm.removeClass('d-none');
                    } else {
                        toastr.error('No payment information found.');
                        paymentForm.addClass('d-none');
                    }
                },
                error: function(xhr) {
                    toastr.error(xhr.responseJSON?.message || 'Failed to fetch payment information');
                    paymentForm.addClass('d-none');
                }
            });
        }
    </script>
@endpush
