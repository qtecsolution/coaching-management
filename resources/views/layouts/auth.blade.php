<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', '') :: {{ config('app.name') }}</title>
    <link rel="shortcut icon" href="{{ asset('assets/static/images/logo/favicon.svg') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('assets/static/images/logo/favicon.png') }}" type="image/png">

    {{-- <link rel="stylesheet" href="{{ asset('assets/scss/app.scss') }}"> --}}
    {{-- <link rel="stylesheet" href="{{ asset('assets/scss/pages/auth.scss') }}"> --}}
    <link rel="stylesheet" href="{{ asset('assets/extensions/sweetalert2/sweetalert2.min.css') }}">

    @vite('public/assets/scss/app.scss')
    @vite('public/assets/scss/pages/auth.scss')
    @stack('css')
</head>

<body>
    <div id="auth">
        @yield('content')
    </div>

    <script src="{{ asset('assets/extensions/sweetalert2/sweetalert2.min.js') }}"></script>
    @stack('js')
    
    @include('sweetalert::alert')
</body>

</html>
