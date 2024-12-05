@extends('layouts.master')

@section('title', 'Payment List')

@section('content')
<div class="page-heading">
    <x-page-title title="Payment List" subtitle="" pageTitle="Payment List" />

    <!-- Basic Tables start -->
    <section class="section">
        <div class="card">
            {{-- <div class="card-header"><h5 class="card-title"></h5></div> --}}
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table" id="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Student</th>
                                <th>Batch</th>
                                <th>Amount</th>
                                <th>Transaction ID</th>
                                <th>Month</th>
                                <th>Payment Date</th>
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
    let datatable = $("#table").DataTable({
        responsive: true,
        serverSide: true,
        processing: true,
        ajax: "{{ route('admin.payments.index') }}",
        columns: [{
                data: 'DT_RowIndex',
                name: 'DT_RowIndex'
            },
            {
                data: 'student',
                name: 'student'
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
                data: 'transaction_id',
                name: 'transaction_id'
            },
            {
                data: 'month',
                name: 'month'
            },
            {
                data: 'date',
                name: 'date'
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
    })
</script>
@endpush