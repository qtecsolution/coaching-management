@extends('layouts.master')

@section('title', 'Add Batch')

@section('content')
    <div class="page-heading">
        <x-page-title title="Add Batch" subtitle="" pageTitle="Add Batch" />

        <section class="section">
            <div class="card">
                {{-- <div class="card-header"><h5 class="card-title"></h5></div> --}}
                <div class="card-body" id="app">
                    <create-batch :route="{{ json_encode(route('admin.batches.store')) }}" />
                </div>
            </div>
        </section>
    </div>
@endsection
