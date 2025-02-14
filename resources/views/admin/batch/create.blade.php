@extends('layouts.master')

@section('title', 'Add Batch')

@section('content')
    <div class="page-heading">
        <x-page-title title="Add Batch" :url="route('admin.batches.index')" />

        <section class="section">
            <div class="card">

                <div class="card-body" id="app">
                    <create-batch :route="{{ json_encode(route('admin.batches.store')) }}"
                        :teachers="{{ json_encode($teachers) }}" :courses="{{ json_encode($courses) }}" />
                </div>
            </div>
        </section>
    </div>
@endsection
