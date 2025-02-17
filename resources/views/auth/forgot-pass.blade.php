@extends('layouts.auth')

@section('title', 'Forgot Password')

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
                <h1 class="auth-title">Forgot Password</h1>
                <p class="auth-subtitle mb-5">Input your email and we will send you reset password link.</p>

                @foreach ($errors->all() as $error)
                    <div class="alert alert-danger mb-2">{{ $error }}</div>
                @endforeach

                <form action="{{ route('auth.forgot-password') }}" method="post">
                    @csrf

                    <div class="form-group position-relative mb-4">
                        <label for="data" class="form-label">Phone or Email<sup class="text-danger">*</sup></label>
                        <input type="text" class="form-control form-control-xl" name="data"
                            placeholder="Enter your phone or email" required>
                        {{-- <div class="form-control-icon">
                            <i class="bi bi-envelope"></i>
                        </div> --}}
                        @error('data')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <button class="btn btn-primary btn-block btn-lg shadow-lg">Send</button>
                </form>
                <div class="text-center mt-3 text-lg fs-4">
                    <p class='text-gray-600'>Remember your account? <a href="{{ route('auth.login.show') }}"
                            class="font-bold">Log in</a>.
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
