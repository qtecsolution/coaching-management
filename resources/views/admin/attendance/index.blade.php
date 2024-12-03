@extends('layouts.master')

@section('title', 'Attendance')

@section('content')
<div class="page-heading">
  <x-page-title title="Attendance" subtitle="" pageTitle="Attendance" />

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
        </div>
        <div class="table-responsive">
          <table class="table" id="table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Student Id</th>
                <th>Batch</th>
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

</script>
@endpush