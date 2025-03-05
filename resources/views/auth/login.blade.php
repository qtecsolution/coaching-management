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
                        $logo = asset('assets/static/images/logo/logo.png');
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

                <button id="demo-credentials-btn" class="btn text-primary">Show Demo Credentials</button>
                <div id="demo-credentials-popup" style="width: 350px"
                    class="d-none position-fixed top-50 start-50 translate-middle bg-white p-4 shadow-lg rounded">
                    <button id="close-popup" class="btn-close position-absolute top-0 end-0 m-2"></button>
                    <h5 class="text-center">Demo Credentials:</h5>
                    <div class="card-body">
                        <div class="mb-0 d-flex justify-content-between">
                            <div>
                                <strong>Admin:</strong><br>
                                Phone: <code id="admin-phone">1234567890</code><br>
                                Password: <code id="admin-password">password</code>
                            </div>
                            <div>
                                <button class="btn btn-sm btn-primary ms-2"
                                    onclick="copyToInput('admin-phone', 'admin-password')">Copy</button>
                            </div>
                        </div>
                        <div class="mb-0 d-flex justify-content-between">
                            <div>
                                <strong>Student:</strong><br>
                                Phone: <code id="student-phone">1234567891</code><br>
                                Password: <code id="student-password">password</code>
                            </div>
                            <div>
                                <button class="btn btn-sm btn-primary ms-2"
                                    onclick="copyToInput('student-phone', 'student-password')">Copy</button>
                            </div>
                        </div>
                        <div class="mb-0 d-flex justify-content-between">
                            <div>
                                <strong>Teacher:</strong><br>
                                Phone: <code id="teacher-phone">1234567892</code><br>
                                Password: <code id="teacher-password">password</code>
                            </div>
                            <div>
                                <button class="btn btn-sm btn-primary ms-2"
                                    onclick="copyToInput('teacher-phone', 'teacher-password')">Copy</button>
                            </div>
                        </div>
                    </div>
                </div>

                <script>
                    document.getElementById('demo-credentials-btn').addEventListener('click', function() {
                        document.getElementById('demo-credentials-popup').classList.remove('d-none');
                    });

                    document.getElementById('close-popup').addEventListener('click', function() {
                        document.getElementById('demo-credentials-popup').classList.add('d-none');
                    });

                    function copyToInput(phoneId, passwordId) {
                        const phone = document.getElementById(phoneId).textContent;
                        const password = document.getElementById(passwordId).textContent;

                        document.querySelector('input[name="phone"]').value = phone;
                        document.querySelector('input[name="password"]').value = password;

                        document.getElementById('demo-credentials-popup').classList.add('d-none');
                    }
                </script>
            </div>
        </div>
    </div>
@endsection
