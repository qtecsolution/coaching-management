@extends('layouts.master')

@php
    $title = Str::replace('-', ' ', Str::upper($type));
@endphp
@section('title', 'Edit ' . $title)

@section('content')
    <div class="page-heading">
        <x-page-title title="Edit {{ $title }}" subtitle="" pageTitle="Edit Settings" />

        <section class="section">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.settings.update') }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="mail_host" class="form-label">Mail Host</label>
                                    <input type="text" name="MAIL_HOST" id="mail_host" placeholder="Mail Host"
                                        class="form-control" value="{{ old('MAIL_HOST', @$settings->where('key', 'MAIL_HOST')->first()->value ?? '') }}">

                                    @error('MAIL_HOST')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="mail_port" class="form-label">Mail Port</label>
                                    <input type="text" name="MAIL_PORT" id="mail_port" placeholder="Mail Port"
                                        class="form-control" value="{{ old('MAIL_PORT', @$settings->where('key', 'MAIL_PORT')->first()->value ?? '') }}">

                                    @error('MAIL_PORT')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="mail_username" class="form-label">Mail Username</label>
                                    <input type="text" name="MAIL_USERNAME" id="mail_username" placeholder="Mail Username"
                                        class="form-control" value="{{ old('MAIL_USERNAME', @$settings->where('key', 'MAIL_USERNAME')->first()->value ?? '') }}">

                                    @error('MAIL_USERNAME')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="mail_password" class="form-label">Mail Password</label>
                                    <input type="text" name="MAIL_PASSWORD" id="mail_password" placeholder="Mail Password"
                                        class="form-control" value="{{ old('MAIL_PASSWORD', @$settings->where('key', 'MAIL_PASSWORD')->first()->value ?? '') }}">

                                    @error('MAIL_PASSWORD')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="mail_encryption" class="form-label">Mail Encryption</label>
                                    <input type="text" name="MAIL_ENCRYPTION" id="mail_encryption" placeholder="Mail Encryption"
                                        class="form-control" value="{{ old('MAIL_ENCRYPTION', @$settings->where('key', 'MAIL_ENCRYPTION')->first()->value ?? '') }}">

                                    @error('MAIL_ENCRYPTION')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="mail_from_address" class="form-label">Mail From Address</label>
                                    <input type="text" name="MAIL_FROM_ADDRESS" id="mail_from_address" placeholder="Mail From Address"
                                        class="form-control" value="{{ old('MAIL_FROM_ADDRESS', @$settings->where('key', 'MAIL_FROM_ADDRESS')->first()->value ?? '') }}">

                                    @error('MAIL_FROM_ADDRESS')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="mail_from_name" class="form-label">Mail From Name</label>
                                    <input type="text" name="MAIL_FROM_NAME" id="mail_from_name" placeholder="Mail From Name"
                                        class="form-control" value="{{ old('MAIL_FROM_NAME', @$settings->where('key', 'MAIL_FROM_NAME')->first()->value ?? '') }}">

                                    @error('MAIL_FROM_NAME')
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
