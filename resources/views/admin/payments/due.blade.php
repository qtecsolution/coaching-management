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
                    <div class="col-4">
                        <div class="form-group">
                            <label><strong>Batch :</strong></label>
                            <select id='batch' class="form-control" name="batch_id">
                                <option value="">--Select Batch--</option>
                                @foreach ($batches as $batch )
                                <option value="{{$batch->id}}">{{$batch->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label><strong>Month :</strong></label>
                            <input type="month" value="{{now()->format('Y-m')}}" class="form-control" name="month" id="month">
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label><strong>Student Id :</strong></label>
                            <input type="student_id" class="form-control" placeholder="Enter student id" name="student_id" id="student_id">
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
            url: "{{ route('admin.payments.due') }}",
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
                data: 'student_id',
                name: 'student_id'
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
                data: 'action',
                name: 'action'
            },
        ],
    });

    // Attach onchange event listeners to filters
    $('#batch, #month ,#student_id').on('change', function() {
        table.ajax.reload();
    });
</script>
@endpush