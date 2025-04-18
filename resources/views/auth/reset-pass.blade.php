@extends('layouts.auth')

@section('title', 'Reset Password')

@section('content')
    <div class="row h-100">
        <div class="col-lg-5 d-none d-lg-block">
            <div id="auth-right"></div>
        </div>
        <div class="col-lg-7 col-12">
            <div id="auth-left">
                <div class="auth-logo">
                    @php
                        $logo = asset('assets/static/images/logo/logo.png');
                        $logoSetting = \App\Models\Setting::where('key', 'app_logo')->value('value');

                        if ($logoSetting) {
                            $logo = asset('storage/' . $logoSetting);
                        }
                    @endphp
                    <a href="index.html"><img src="{{ $logo }}" alt="Logo"></a>
                </div>
                <h1 class="auth-title">Reset Password</h1>
                <p class="auth-subtitle mb-5">Input your new password to reset your password.</p>

                @foreach ($errors->all() as $error)
                    <div class="alert alert-danger mb-2">{{ $error }}</div>
                @endforeach

                <form action="{{ route('auth.reset-password') }}" method="post">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token->token }}">

                    <div class="form-group position-relative has-icon-left mb-4">
                        <input type="password" class="form-control form-control-xl" name="password" placeholder="Enter your new password">
                        <div class="form-control-icon">
                            <i class="bi bi-shield-lock"></i>
                        </div>
                        @error('password')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group position-relative has-icon-left mb-4">
                        <input type="password" class="form-control form-control-xl" name="password_confirmation" placeholder="Enter your confirm password">
                        <div class="form-control-icon">
                            <i class="bi bi-shield-lock"></i>
                        </div>
                        @error('password_confirmation')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <button class="btn btn-primary btn-block btn-lg shadow-lg">Send</button>
                </form>
            </div>
        </div>
    </div>
@endsection
