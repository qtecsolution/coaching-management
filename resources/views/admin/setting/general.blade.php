@extends('layouts.master')

@section('title', 'Edit ' . Str::ucfirst($type) . ' Settings')

@section('content')
    <div class="page-heading">
        <x-page-title title="Edit {{ Str::ucfirst($type) }} Settings" subtitle="" pageTitle="Edit Settings" />

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
                                        class="form-control" value="{{ old('APP_NAME', config('app.name')) }}">

                                    @error('APP_NAME')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="time_zone" class="form-label">Time Zone</label>
                                    <input type="text" name="APP_TIMEZONE" id="time_zone" placeholder="Time Zone"
                                        class="form-control" value="{{ old('APP_TIMEZONE', config('app.timezone')) }}">

                                    @error('APP_TIMEZONE')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="app_url" class="form-label">App URL</label>
                                    <input type="text" name="APP_URL" id="app_url" placeholder="App URL"
                                        class="form-control" value="{{ old('APP_URL', config('app.url')) }}">

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
