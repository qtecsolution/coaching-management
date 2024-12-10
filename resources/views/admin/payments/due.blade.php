@extends('layouts.master')

@section('title', 'Due Payments')

@section('content')
    <div class="page-heading">
        <x-page-title title="Due Payments" subtitle="" pageTitle="Due Payments" />

        <!-- Basic Tables start -->
        <section class="section">
            <div class="card">
                <div class="card-body">
                    <div class="row my-4">
                        <div class="col-4">
                            <div class="form-group">
                                <label class="form-label">Batch :</label>
                                <select id='batch' class="form-control form-select" name="batch_id">
                                    <option value="">--Select Batch--</option>
                                    @foreach ($batches as $batch)
                                        <option value="{{ $batch->id }}">{{ $batch->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label class="form-label">Month :</label>
                                <input type="month" class="form-control" name="month" id="month">
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label class="form-label">Student ID :</label>
                                <input type="text" class="form-control" placeholder="Enter Student ID" name="reg_id"
                                    id="reg_id">
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table" id="table">
                            <thead>
                                <tr>
                                    <th>Month</th>
                                    <th>Name</th>
                                    <th>Student ID</th>
                                    <th>Batch</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
        <!-- Basic Tables end -->
    </div>
@endsection

@push('js')
    <script>
        const table = $("#table").DataTable({
            responsive: true,
            serverSide: true,
            processing: true,
            ajax: {
                url: "{{ route('admin.payments.due') }}",
                data: function(d) {
                    d.batch_id = $('#batch').val();
                    d.month = $('#month').val();
                    d.reg_id = $('#reg_id').val();
                }
            },
            columns: [{
                    data: 'month',
                    name: 'month'
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'reg_id',
                    name: 'reg_id'
                },
                {
                    data: 'batch',
                    name: 'batch'
                },
                {
                    data: 'amount',
                    name: 'amount'
                },
                {
                    data: 'status',
                    name: 'status'
                },
                {
                    data: 'action',
                    name: 'action'
                },
            ],
        });

        // Attach onchange event listeners to filters
        $('#batch, #month ,#reg_id').on('change', function() {
            table.ajax.reload();
        });
    </script>
@endpush
