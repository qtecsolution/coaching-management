@extends('layouts.master')

@section('title', 'Edit Batch')

@section('content')
    <div class="page-heading">
        <x-page-title title="Edit Batch" :url="route('admin.batches.index')" />

        <section class="section">
            <div class="card">

                <div class="card-body" id="app">
                    <edit-batch :route="{{ json_encode(route('admin.batches.update', $batch->id)) }}"
                        :teachers="{{ json_encode($teachers) }}" :batch="{{ json_encode($batch) }}"
                        :courses="{{ json_encode($courses) }}" />
                </div>
            </div>
        </section>
    </div>
@endsection
