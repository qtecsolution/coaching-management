@extends('errors.master')

@section('title', '403')

@section('content')
    <div class="error-page container">
        <div class="col-md-8 col-12 offset-md-2">
            <div class="text-center">
                <img class="img-error" src="{{ asset('assets/static/images/errors/error-403.svg') }}" width="80%"
                    alt="Forbidden">
                <div class="my-4">
                    <h1 class="error-title">Unauthorized Access</h1>
                    <p class="fs-5 text-gray-600">You are unauthorized to see this page.</p>
                    <a href="{{ url()->previous() }}" class="btn btn-lg btn-outline-primary mt-3">Go Back</a>
                </div>
            </div>
        </div>
    </div>
@endsection
