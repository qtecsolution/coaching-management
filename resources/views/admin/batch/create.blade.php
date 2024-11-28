@extends('layouts.master')

@section('title', 'Add Batch')

@section('content')
    <div class="page-heading">
        <x-page-title title="Add Batch" subtitle="" pageTitle="Add Batch" />

        <section class="section">
            <div class="card">
                {{-- <div class="card-header"><h5 class="card-title"></h5></div> --}}
                <div class="card-body" id="app">
                    <create-batch :route="{{ json_encode(route('admin.batches.store')) }}"
                        :teachers="{{ json_encode($teachers) }}" :levels="{{ json_encode($levels) }}"
                        :subjects="{{ json_encode($subjects) }}" />
                </div>
            </div>
        </section>
    </div>
@endsection
