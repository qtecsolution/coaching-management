@extends('layouts.master')

@section('title', 'Messages')

@section('content')
    <div class="page-heading">
        <x-page-title title="Messages" />

        <!-- Basic Tables start -->
        <section class="section">
            <div class="card">
                <div class="card-body">
                    <div class="col-12 mb-4 text-end">
                        <a href="{{ route('admin.messages.create') }}" class="btn btn-primary">
                            Send Message
                        </a>
                    </div>

                    <div class="table-responsive">
                        <table class="table" id="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Batch</th>
                                    <th>Student</th>
                                    <th>Message</th>
                                    <th>Date/Time</th>
                                    <th>Status</th>
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
        $(document).ready(function() {
            $('.select2').select2();
        });

        let datatable = $("#table").DataTable({
            responsive: true,
            serverSide: true,
            processing: true,
            ajax: "{{ route('admin.messages.index') }}",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },
                {
                    data: 'batch',
                    name: 'batch'
                },
                {
                    data: 'student',
                    name: 'student'
                },
                {
                    data: 'message',
                    name: 'message'
                },
                {
                    data: 'date_time',
                    name: 'date_time'
                },
                {
                    data: 'status',
                    name: 'status'
                }
            ],
        });
    </script>
@endpush
