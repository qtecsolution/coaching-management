@extends('layouts.master')

@section('title', 'Payment Due')

@section('content')
<div class="page-heading">
    <x-page-title title="Payment Due" subtitle="" pageTitle="Payment Due" />

    <!-- Basic Tables start -->
    <section class="section">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <!-- <div class="col-4">
                        <div class="form-group">
                            <label><strong>Batch :</strong></label>
                            <select id='batch' class="form-control" name="batch_id">
                                <option value="">--Select Batch--</option>
                                @foreach ($batches as $batch )
                                <option value="{{$batch->id}}">{{$batch->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div> -->
                    <div class="col-4">
                        <div class="form-group">
                            <label><strong>Month :</strong></label>
                            <input type="month" class="form-control" name="month" id="month">
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table" id="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Student Id</th>
                                <th>Batch</th>
                                <th>Amount</th>
                                <th>Month</th>
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
        language: {
            "emptyTable": "No unpaid student found"
        },
        ajax: {
            url: "{{ route('user.payments.due') }}",
            data: function(d) {
                d.batch_id = $('#batch').val();
                d.month = $('#month').val();
            }
        },
        columns: [{
                data: 'DT_RowIndex',
                name: 'DT_RowIndex'
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
                data: 'month',
                name: 'month'
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
    $('#batch, #month').on('change', function() {
        table.ajax.reload();
    });
</script>
@endpush