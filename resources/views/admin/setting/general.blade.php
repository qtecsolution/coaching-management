@extends('layouts.master')

@php
    $title = Str::replace('-', ' ', Str::upper($type));
@endphp
@section('title', 'Edit ' . $title . ' Settings')

@section('content')
    <div class="page-heading">
        <x-page-title title="Edit {{ $title }} Settings" subtitle="" pageTitle="Edit Settings" />

        <section class="section">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.settings.update') }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="app_name" class="form-label">App Name</label>
                                    <input type="text" name="APP_NAME" id="app_name" placeholder="App Name"
                                        class="form-control" value="{{ old('APP_NAME', @$settings->where('key', 'APP_NAME')->first()->value ?? '') }}">

                                    @error('APP_NAME')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="time_zone" class="form-label">Time Zone</label>
                                    <input type="text" name="APP_TIMEZONE" id="time_zone" placeholder="Time Zone"
                                        class="form-control" value="{{ old('APP_TIMEZONE', @$settings->where('key', 'APP_TIMEZONE')->first()->value ?? '') }}">

                                    @error('APP_TIMEZONE')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="app_url" class="form-label">App URL</label>
                                    <input type="text" name="APP_URL" id="app_url" placeholder="App URL"
                                        class="form-control" value="{{ old('APP_URL', @$settings->where('key', 'APP_URL')->first()->value ?? '') }}">

                                    @error('APP_URL')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-12 text-end mt-2">
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
@endsection
