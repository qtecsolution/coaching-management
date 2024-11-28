@extends('layouts.master')

@section('title', 'Edit Batch')

@section('content')
    <div class="page-heading">
        <x-page-title title="Edit Batch" subtitle="" pageTitle="Edit Batch" />

        <section class="section">
            <div class="card">
                {{-- <div class="card-header"><h5 class="card-title"></h5></div> --}}
                <div class="card-body" id="app">
                    <edit-batch :route="{{ json_encode(route('admin.batches.update', $batch->id)) }}"
                        :teachers="{{ json_encode($teachers) }}" :batch="{{ json_encode($batch) }}"
                        :subjects="{{ json_encode($subjects) }}" :levels="{{ json_encode($levels) }}" />
                </div>
            </div>
        </section>
    </div>
@endsection
