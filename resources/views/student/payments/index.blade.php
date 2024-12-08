@extends('layouts.master')

@section('title', 'Payment History')

@section('content')
<div class="page-heading">
    <x-page-title title="Payment History" subtitle="" pageTitle="Payment History" />

    <!-- Basic Tables start -->
    <section class="section">
        <div class="card">
            {{-- <div class="card-header"><h5 class="card-title"></h5></div> --}}
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table" id="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Batch</th>
                                <th>Amount</th>
                                <th>Transaction ID</th>
                                <th>Month</th>
                                <th>Date</th>
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
        ajax: "{{ route('user.payments.index') }}",
        columns: [{
                data: 'DT_RowIndex',
                name: 'DT_RowIndex'
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