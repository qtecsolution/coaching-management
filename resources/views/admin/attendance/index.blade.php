@extends('layouts.master')

@section('title', 'Attendance List')

@section('content')
<div class="page-heading">
    <x-page-title title="Attendance List" subtitle="" pageTitle="Attendance List" />

    <!-- Basic Tables start -->
    <section class="section">
        <div class="card">
            {{-- <div class="card-header"><h5 class="card-title"></h5></div> --}}
            <div class="card-body">
                <div class="row my-4">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="" class="form-label">Batch</label>
                            <select name="batch_id" id="batch_id" class="form-control form-select">
                                <option value="">--Select Batch--</option>
                                @foreach ($batches as $batch )
                                <option value="{{$batch->id}}">{{$batch->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="" class="form-label">Date</label>
                            <input type="date" class="form-control" name="date" id="date" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" max="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table" id="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Batch</th>
                                <th>Date</th>
                                <th>Total Student</th>
                                <th>Total Absent</th>
                                <th>Total Present</th>
                                <th>Total Late</th>
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
    let batchId = null;
    let date = null;

    let datatable = $("#table").DataTable({
        responsive: true,
        serverSide: true,
        processing: true,
        ajax: {
            url: "{{ route('admin.attendance.index') }}",
            data: function(d) {
                d.batch_id = batchId;
                d.date = date;
            }
        },
        columns: [{
                data: 'DT_RowIndex',
                name: 'DT_RowIndex'
            },
            {
                data: 'batch_name',
                name: 'batch_name'
            },
            {
                data: 'date',
                name: 'date'
            },
            {
                data: 'total_student',
                name: 'total_student'
            },
            {
                data: 'total_absent',
                name: 'total_absent'
            },
            {
                data: 'total_present',
                name: 'total_present'
            },
            {
                data: 'total_late',
                name: 'total_late'
            },
            {
                data: 'action',
                name: 'action'
            },
        ],
    });

    $('#batch_id').on('change', function() {
        batchId = $(this).val();
        datatable.ajax.reload();
    });

    $('#date').on('change', function() {
        date = $(this).val();
        datatable.ajax.reload();
    });
</script>
@endpush