@extends('errors.master')

@section('title', '404')

@section('content')
    <div class="error-page container">
        <div class="col-md-8 col-12 offset-md-2">
            <div class="text-center">
                <img class="img-error" src="{{ asset('assets/static/images/errors/error-404.svg') }}" width="80%"
                    alt="Not Found">
                <div class="my-4">
                    <h1 class="error-title">Not Found</h1>
                    <p class='fs-5 text-gray-600'>The page you are looking not found.</p>
                    <a href="{{ url()->previous() }}" class="btn btn-lg btn-outline-primary mt-3">Go Back</a>
                </div>
            </div>
        </div>
    </div>
@endsection
