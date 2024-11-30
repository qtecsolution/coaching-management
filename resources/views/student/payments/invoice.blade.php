@extends('layouts.master')

@section('title', 'Payment Invoice')

@section('content')
<div class="page-heading">
  <!-- <x-page-title title="Payment Invoice" subtitle="" pageTitle="Payment Invoice" /> -->
  <div class="container">
    <div class="row">
      <!-- Main content -->
      <section class="invoice">
        <!-- title row -->
        <div class="row mb-4">
          <div class="col-4">
            <h2 class="page-header">
              {{@$settings->where('key', 'APP_NAME')->first()->value ?? 'Your App Name'}}
            </h2>
          </div>
          <div class="col-4">
            <h4 class="page-header">Invoice</h4>
          </div>
          <div class="col-4">
            <small class="float-right text-small">Date: {{date('d/m/Y')}}</small>
          </div>
          <!-- /.col -->
        </div>
        <!-- info row -->
        <div class="row invoice-info">
          <!-- /.col -->
          <div class="col-sm-5 invoice-col">
            To
            <address>
              Name: {{$payment->student->name??"N/A"}} ({{$payment->student->id??"N/A"}})<br>
              Batch: {{$payment->batch->name??"N/A"}}<br>
              Institution: {{$payment->student->school_name??"N/A"}}
            </address>
          </div>
          <div class="col-sm-4 invoice-col">
            <!-- <address>
              <strong>Name:</strong><br>
              Address:<br>
              Phone: <br>
              Email: <br>
            </address>  -->
          </div>
          <!-- /.col -->
          <div class="col-sm-3 invoice-col">
            Info <br>
            Payment ID #{{$payment->id}}<br>
            Payment Date: {{date('d/m/Y', strtotime($payment->date))}}<br>
            <!-- <br>
          <b>Payment Due:</b> 2/22/2014<br>
          <b>Account:</b> 968-34567 -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->

        <!-- Table row -->
        <div class="row">
          <div class="col-12 table-responsive">
            <table class="table">
              <thead>
                <tr style="font-weight: bold !important;">
                  <td>Transaction Id</td>
                  <td>Month</td>
                  <td>Amount</td>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>{{$payment->transaction_id}}</td>
                  <td>{{ \Carbon\Carbon::parse($payment->month)->format('M-Y') }}</td>
                  <td>{{number_format($payment->amount,2,'.',',')}}</td>
                </tr>
              </tbody>
            </table>
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->

        <div class="row">
          <!-- accepted payments column -->
          <div class="col-6">
            <!-- <p class="lead">Payment:Cash Paid</p> -->
            <small class="text-small text-bold">Payment Method: {{$payment->payment_method}}</small>
            <p class="text-muted well well-sm shadow-none" style="margin-top: 10px;">
              Note: {{$payment->note}}
            </p>
          </div>
          <!-- /.col -->
          <div class="col-6">
            <!-- <p class="lead">Amount Due 2/22/2014</p> -->

            <div class="table-responsive">
              <table class="table">
                <tr>
                  <td>Total:</td>
                  <td class="text-right">{{number_format($payment->amount,2,'.',',')}}</td>
                </tr>
              </table>
            </div>
          </div>
          <!-- /.col -->
        </div>
        <div class="row no-print">
          <div class="col-12">
            <button type="button" onclick="window.print()" class="btn btn-success float-right"><i class="fas fa-print"></i> Print</a>
            </button>
          </div>
        </div>
        <!-- /.row -->
      </section>
    </div>
  </div>
  <!-- /.content -->
</div>
@endsection

@push('css')
<style>
  .invoice {
    border: none !important;
  }

  @media print {
    .no-print {
      display: none;
    }

    header {
      display: none;
    }
  }
</style>
@endpush
@push('js')
<script>
  window.addEventListener("load", window.print());
</script>
@endpush