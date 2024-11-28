@extends('layouts.auth')

@section('title', 'Sign up')

@section('content')
    <div class="row h-100">
        <div class="col-lg-5 d-none d-lg-block">
            <div id="auth-right">

            </div>
        </div>
        <div class="col-lg-7 col-12">
            <div id="auth-left">
                <div class="auth-logo">
                    <a href="index.html"><img src="{{ asset('assets/static/images/logo/logo.svg') }}" alt="Logo"></a>
                </div>
                <h1 class="auth-title">Sign up</h1>
                <p class="auth-subtitle mb-5">Input your data to sign up to our panel.</p>

                @foreach ($errors->all() as $error)
                    <div class="alert alert-danger mb-2">{{ $error }}</div>
                @endforeach

                <form action="{{ route('auth.signup') }}" method="post">
                    @csrf

                    <div class="form-group position-relative has-icon-left mb-4">
                        <input type="text" name="name" value="{{ old('name') }}" class="form-control form-control-xl @error('name') border-danger @enderror" placeholder="Name">
                        <div class="form-control-icon">
                            <i class="bi bi-person"></i>
                        </div>
                    </div>
                    <div class="form-group position-relative has-icon-left mb-4">
                        <input type="tel" name="phone" value="{{ old('phone') }}" class="form-control form-control-xl @error('phone') border-danger @enderror" placeholder="Phone">
                        <div class="form-control-icon">
                            <i class="bi bi-phone"></i>
                        </div>
                    </div>
                    <div class="form-group position-relative has-icon-left mb-4">
                        <input type="password" name="password" value="{{ old('password') }}" class="form-control form-control-xl @error('password') border-danger @enderror" placeholder="Password">
                        <div class="form-control-icon">
                            <i class="bi bi-shield-lock"></i>
                        </div>
                    </div>
                    <div class="form-group position-relative has-icon-left mb-4">
                        <input type="password" name="password_confirmation" class="form-control form-control-xl @error('password') border-danger @enderror" placeholder="Confirm Password">
                        <div class="form-control-icon">
                            <i class="bi bi-shield-lock"></i>
                        </div>
                    </div>
                    <button class="btn btn-primary btn-block btn-lg shadow-lg mt-5" type="submit">Sign up</button>
                </form>
                <div class="text-center mt-5 text-lg fs-4">
                    <p class='text-gray-600'>Already have an account? <a href="{{ route('auth.login.show') }}" class="font-bold">Log
                            in</a>.</p>
                </div>
            </div>
        </div>
    </div>
@endsection
