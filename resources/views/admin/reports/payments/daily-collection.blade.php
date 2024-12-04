@extends('layouts.master')

@section('title', 'Payment paid report')

@section('content')
<div class="page-heading">
  <x-page-title title="Payment paid report" subtitle="" pageTitle="Payment paid report" />

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
              <input type="month" class="form-control" name="month" id="month">
            </div>
          </div>
          <div class="col-4">
            <div class="form-group">
              <label><strong>Date Range :</strong></label>
              <input type="date" class="form-control flatpickr-range-Y-m-d" name="date_range" id="date_range">
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
                <th>TXID</th>
                <th>Month</th>
                <th>Date</th>
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

<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css">
<script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>


<script>
  const table = $("#table").DataTable({
    responsive: true,
    serverSide: true,
    processing: true,
    paginate: false,
    searching: false,
    dom: 'lBfrtip', // Enables the buttons
    buttons: [{
        extend: 'excel',
        text: 'Export to Excel',
        className: 'btn'
      },
      {
        extend: 'pdf',
        text: 'Export to PDF',
        className: 'btn'
      },
      {
        extend: 'print',
        text: 'Print',
        className: 'btn'
      }
    ],
    ajax: {
      url: "{{ route('admin.reports.daily.collection') }}",
      data: function(d) {
        d.batch_id = $('#batch').val();
        d.month = $('#month').val();
        d.reg_id = $('#reg_id').val();
        let dateRange = $('#date_range').val();
        let [date_from, date_to] = dateRange.split(' to ');
        if (!date_to) {
          date_to = date_from;
        }
        d.date_from = date_from;
        d.date_to = date_to;
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
    ],
  });

  // Attach onchange event listeners to filters
  $('#batch, #month ,#reg_id, #date_range').on('change', function() {
    table.ajax.reload();
  });
</script>
@endpush