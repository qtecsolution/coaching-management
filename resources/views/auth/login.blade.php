@extends('layouts.auth')

@section('title', 'Login')

@section('content')
    <div class="row h-100">
        <div class="col-lg-5 d-none d-lg-block">
            <div id="auth-right"></div>
        </div>
        <div class="col-lg-7 col-12">
            <div id="auth-left">
                <div class="auth-logo">
                    @php
                        $logo = asset('assets/static/images/logo/logo.svg');
                        $logoSetting = \App\Models\Setting::where('key', 'app_logo')->value('value');

                        if ($logoSetting) {
                            $logo = asset('storage/' . $logoSetting);
                        }
                    @endphp
                    <a href="index.html"><img src="{{ $logo }}" alt="Logo"></a>
                </div>
                <h1 class="auth-title">Log in.</h1>
                <p class="auth-subtitle mb-5">Log in with your data that you entered during registration.</p>

                @foreach ($errors->all() as $error)
                    <div class="alert alert-danger mb-2">{{ $error }}</div>
                @endforeach

                <form action="{{ route('auth.login') }}" method="post">
                    @csrf

                    <div class="form-group position-relative has-icon-left mb-4">
                        <input type="tel" name="phone"
                            class="form-control form-control-xl @error('phone') border-danger @enderror" placeholder="Phone"
                            value="{{ old('phone') }}">
                        <div class="form-control-icon">
                            <i class="bi bi-phone"></i>
                        </div>
                    </div>
                    <div class="form-group position-relative has-icon-left mb-4">
                        <input type="password" name="password"
                            class="form-control form-control-xl @error('password') border-danger @enderror"
                            placeholder="Password">
                        <div class="form-control-icon">
                            <i class="bi bi-shield-lock"></i>
                        </div>
                    </div>
                    <div class="form-check form-check-lg d-flex align-items-end">
                        <input class="form-check-input me-2" type="checkbox" name="remember_me"
                            {{ old('remember_me') ? 'checked' : '' }} id="remember_me">
                        <label class="form-check-label text-gray-600" for="remember_me">
                            Keep me logged in
                        </label>
                    </div>
                    <button class="btn btn-primary btn-block btn-lg shadow-lg mt-5" type="submit">Log in</button>
                </form>
                <div class="text-center mt-3 text-lg fs-4">
                    {{-- <p class="text-gray-600">Don't have an account? <a href="{{ route('auth.signup.show') }}"
                            class="font-bold">Sign
                            up</a>.</p> --}}
                    <p><a class="font-bold" href="{{ route('auth.forgot-password.show') }}">Forgot password?</a></p>
                </div>
            </div>
        </div>
    </div>
@endsection
