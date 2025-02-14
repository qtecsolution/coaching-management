@extends('layouts.master')

@section('title', 'Edit Payment')

@section('content')
    <div class="page-heading">
        <x-page-title title="Edit Payment" :url="route('admin.payments.index')" />

        <section class="section">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.payments.update', $payment->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="student_id" class="form-label">Student<sup class="text-danger">*</sup></label>
                                    <select name="student_id" id="student" class="form-control select2" required>
                                        <option value="" disabled>Select Student</option>
                                        @foreach ($students as $student)
                                            <option value="{{ $student->id }}"
                                                {{ (old('student_id', $payment->student_id) == $student->id) ? 'selected' : '' }}>
                                                {{ $student->name }} ({{ $student->reg_id }})
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
                                        <option value="" disabled>Select Batch</option>
                                        @foreach ($batches as $batch)
                                            <option value="{{ $batch->id }}"
                                                {{ (old('batch_id', $payment->batch_id) == $batch->id) ? 'selected' : '' }}>
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
                                    <label for="paid" class="form-label">Total Paid</label>
                                    <input type="number" id="paid" class="form-control" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="due" class="form-label">Total Due</label>
                                    <input type="number" id="due" class="form-control" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row" id="payment_form">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="amount" class="form-label">Amount<sup class="text-danger">*</sup></label>
                                    <input type="number" name="amount" id="amount" placeholder="Amount"
                                        class="form-control" value="{{ old('amount', $payment->amount) }}" required min="0">
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
                                        class="form-control" value="{{ old('date', $payment->date) }}" required>

                                    @error('date')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="note" class="form-label">Note</label>
                                    <textarea name="note" id="note" class="form-control" rows="3"
                                        placeholder="Payment note">{{ old('note', $payment->note) }}</textarea>

                                    @error('note')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-12 text-end mt-2">
                            <button type="submit" class="btn btn-primary">Update</button>
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

            // Load initial payment info
            getPaymentInfo($('#student').val(), $('#batch').val());

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
                    $(this).val('').trigger('change');
                    return;
                }

                if (batchId && studentId) {
                    getPaymentInfo(studentId, batchId);
                }
            });

            $('#amount').on('input', function() {
                const maxAmount = parseFloat($('#max_amount').text());
                const amount = parseFloat($(this).val());
                const originalAmount = {{ $payment->amount }};

                if (amount > (maxAmount + originalAmount)) {
                    toastr.error('Amount cannot exceed due amount');
                    $(this).val(maxAmount + originalAmount);
                }
            });
        });

        function getPaymentInfo(studentId, batchId) {
            if (!studentId || !batchId) return;

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
                        const originalAmount = {{ $payment->amount }};

                        $('#paid').val(info.total_paid || 0);
                        $('#due').val(info.total_due || 0);
                        $('#max_amount').text((info.total_due + originalAmount) || 0);
                    } else {
                        toastr.error('No payment information found.');
                    }
                },
                error: function(xhr) {
                    toastr.error(xhr.responseJSON?.message || 'Failed to fetch payment information');
                }
            });
        }
    </script>
@endpush
