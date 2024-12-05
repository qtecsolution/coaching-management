@extends('layouts.master')

@section('title', 'Payment summary')

@section('content')
<div class="page-heading">
  <x-page-title title="Payment summary" subtitle="" pageTitle="Payment summary" />

  <!-- Basic Tables start -->
  <section class="section">
    <div class="card">
      <div class="card-body">
        <caption>Filter</caption>
        <div class="row">
          <div class="col-4">
            <div class="form-group">
              <label><strong>Month From :</strong></label>
              <input type="month" class="form-control" value="{{ \Carbon\Carbon::now()->format('Y-m') }}" name="month_from" id="month_from">
            </div>
          </div>
          <div class="col-4">
            <div class="form-group">
              <label><strong>Month To :</strong></label>
              <input type="month" class="form-control" value="{{ \Carbon\Carbon::now()->format('Y-m') }}" name="month_to" id="month_to">
            </div>
          </div>
          <div class="col-4 text-end no-print">
            <div class="form-group">
              <br>
              <button class="btn btn-info" id="print-button">Print</button>
            </div>
          </div>
        </div>
        <div class="row">
          <caption>Summary</caption>
          <table class="table table-responsive" id="table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Month</th>
                <th>Collectable</th>
                <th>Collected</th>
                <th>Due</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
        <div class="row">
          <caption>Paid & due List</caption>
          <table class="table table-responsive" id="table2">
            <thead>
              <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Student Id</th>
                <th>Batch</th>
                <th>Amount</th>
                <th>Month</th>
                <th>Status</th>
                <!--<th>Action</th> -->
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>
    </div>
</div>
</section>
<!-- Basic Tables end -->
</div>
@endsection

@push('js')

<!-- <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css">
<script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script> -->
<script>
  const table = $("#table").DataTable({
    responsive: true,
    serverSide: true,
    processing: true,
    paginate: false,
    searching: false,
    info: false,
    // dom: 'lBfrtip',
    // Enables the buttons
    // buttons: [{
    //     extend: 'excel',
    //     text: 'Export to Excel',
    //     className: 'btn'
    //   },
    //   {
    //     extend: 'pdf',
    //     text: 'Export to PDF',
    //     className: 'btn'
    //   },
    //   {
    //     extend: 'print',
    //     text: 'Print',
    //     className: 'btn'
    //   }
    // ],
    ajax: {
      url: "{{ route('admin.reports.payments.summary',['payment_report'=>1]) }}",
      data: function(d) {
        d.batch_id = $('#batch').val();
        d.month_from = $('#month_from').val();
        d.month_to = $('#month_to').val();
        d.reg_id = $('#reg_id').val();
      }
    },
    columns: [{
        data: 'DT_RowIndex',
        name: 'DT_RowIndex'
      },
      {
        data: 'month',
        name: 'month'
      },
      {
        data: 'estimated_collection_amount',
        name: 'estimated_collection_amount'
      },
      {
        data: 'collected_amount',
        name: 'collected_amount'
      },

      {
        data: 'due_amount',
        name: 'due_amount'
      },
    ],
  });
  // Attach onchange event listeners to filters
  const table2 = $("#table2").DataTable({
    responsive: true,
    serverSide: true,
    processing: true,
    paginate: false,
    searching: false,
    info: false,
    // dom: 'lBfrtip', // Enables the buttons
    // buttons: [{
    //     extend: 'excel',
    //     text: 'Export to Excel',
    //     className: 'btn'
    //   },
    //   {
    //     extend: 'pdf',
    //     text: 'Export to PDF',
    //     className: 'btn'
    //   },
    //   {
    //     extend: 'print',
    //     text: 'Print',
    //     className: 'btn'
    //   }
    // ],
    ajax: {
      url: "{{ route('admin.reports.payments.summary',['payments'=>1]) }}",
      data: function(d) {
        d.batch_id = $('#batch').val();
        d.month_from = $('#month_from').val();
        d.month_to = $('#month_to').val();
        d.reg_id = $('#reg_id').val();
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
      // {
      //   data: 'action',
      //   name: 'action'
      // },
    ],
  });
  // Attach onchange event listeners to filters
  $('#batch,#month_from,#month_to, #reg_id').on('change', function() {
    table.ajax.reload();
    table2.ajax.reload();
  });



  document.getElementById('print-button').addEventListener('click', function() {
    window.print();
  });
</script>
@endpush
@push('css')
<style>
  #table_wrapper .row:nth-child(2) {
    overflow-x: hidden !important;
  }

  @media print {
    .no-print {
      display: none;
    }
  }
</style>
@endpush