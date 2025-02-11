@extends('layouts.master')

@section('title', 'Show Batch')

@section('content')
    <div class="page-heading">
        <x-page-title title="Show Batch" :url="route('admin.batches.index')" />

        <section class="section">
            <div class="card">
                {{-- <div class="card-header"><h5 class="card-title"></h5></div> --}}
                <div class="card-body" id="app">
                    <show-batch :batch="{{ json_encode($batch) }}" :edit_route="{{ json_encode(route('admin.batches.edit', $batch->id)) }}" />
                </div>
            </div>
        </section>
    </div>
@endsection
