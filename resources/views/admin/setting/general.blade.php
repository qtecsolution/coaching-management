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
                    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="APP_LOGO" class="form-label">App Logo</label>
                                    <input type="file" name="APP_LOGO" id="app_logo" class="basic-filepond"
                                        accept="image/*"
                                        data-source="{{ @$settings->where('key', 'APP_LOGO')->first()?->value ? asset('storage/' . @$settings->where('key', 'APP_LOGO')->first()->value) : asset('assets/static/images/logo/logo.svg') }}">

                                    @error('APP_LOGO')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="app_name" class="form-label">App Name</label>
                                    <input type="text" name="APP_NAME" id="app_name" placeholder="App Name"
                                        class="form-control"
                                        value="{{ old('APP_NAME', @$settings->where('key', 'APP_NAME')->first()->value ?? '') }}">

                                    @error('APP_NAME')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-8 my-4 border"></div>

                            <h3>Contact Details</h3>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="contact_number" class="form-label">Contact Number</label>
                                    <input type="text" name="CONTACT_NUMBER" id="contact_number"
                                        placeholder="Contact Number" class="form-control"
                                        value="{{ old('CONTACT_NUMBER', @$settings->where('key', 'CONTACT_NUMBER')->first()->value ?? '') }}">

                                    @error('CONTACT_NUMBER')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="contact_email" class="form-label">Contact Email</label>
                                    <input type="email" name="CONTACT_EMAIL" id="contact_email"
                                        placeholder="Contact Email" class="form-control"
                                        value="{{ old('CONTACT_EMAIL', @$settings->where('key', 'CONTACT_EMAIL')->first()->value ?? '') }}">

                                    @error('CONTACT_EMAIL')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="contact_address" class="form-label">Contact Address</label>
                                    <textarea name="CONTACT_ADDRESS" id="contact_address" rows="5" placeholder="Contact Address" class="form-control">{{ @$settings->where('key', 'CONTACT_ADDRESS')->first()->value ?? '' }}</textarea>

                                    @error('CONTACT_ADDRESS')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-8 my-4 border"></div>

                            <h3>Social Links</h3>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="facebook_link" class="form-label">Facebook Link</label>
                                    <input type="url" name="FACEBOOK_LINK" id="facebook_link"
                                        placeholder="Facebook Link" class="form-control"
                                        value="{{ old('FACEBOOK_LINK', @$settings->where('key', 'FACEBOOK_LINK')->first()->value ?? '') }}">

                                    @error('FACEBOOK_LINK')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="twitter_link" class="form-label">Twitter Link</label>
                                    <input type="url" name="TWITTER_LINK" id="twitter_link" placeholder="Twitter Link"
                                        class="form-control"
                                        value="{{ old('TWITTER_LINK', @$settings->where('key', 'TWITTER_LINK')->first()->value ?? '') }}">

                                    @error('TWITTER_LINK')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="linkedin_link" class="form-label">LinkedIn Link</label>
                                    <input type="url" name="LINKEDIN_LINK" id="linkedin_link"
                                        placeholder="LinkedIn Link" class="form-control"
                                        value="{{ old('LINKEDIN_LINK', @$settings->where('key', 'LINKEDIN_LINK')->first()->value ?? '') }}">

                                    @error('LINKEDIN_LINK')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="youtube_link" class="form-label">Youtube Link</label>
                                    <input type="url" name="YOUTUBE_LINK" id="youtube_link"
                                        placeholder="Youtube Link" class="form-control"
                                        value="{{ old('YOUTUBE_LINK', @$settings->where('key', 'YOUTUBE_LINK')->first()->value ?? '') }}">

                                    @error('YOUTUBE_LINK')
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
