@extends('layouts.master')

@section('title', 'Class Materials')

@section('content')
    <div class="page-heading">
        <x-page-title title="Class Materials" subtitle="" pageTitle="Class Materials" />

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
                                    <th>Batch</th>
                                    <th>Title</th>
                                    <th>URL</th>
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
            ajax: "{{ route('admin.class-materials.index') }}",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },
                {
                    data: 'batch',
                    name: 'batch'
                },
                {
                    data: 'title',
                    name: 'title'
                },
                {
                    data: 'url',
                    name: 'url'
                },
                {
                    data: 'action',
                    name: 'action'
                },
            ],
        });
    </script>
@endpush
