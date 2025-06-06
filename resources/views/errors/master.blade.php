<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', '') :: {{ config('app.name') }}</title>
    <link rel="shortcut icon" href="{{ asset('assets/static/images/logo/favicon.png') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('assets/static/images/logo/favicon.png') }}" type="image/png">

    <link rel="stylesheet" href="{{ asset('assets/scss/app.scss') }}">
    <link rel="stylesheet" href="{{ asset('assets/scss/pages/error.scss') }}">

    @vite('public/assets/scss/app.scss')
    @vite('public/assets/scss/pages/error.scss')
    @stack('css')
</head>

<body>
    <div id="auth">
        @yield('content')
    </div>

    @stack('js')
</body>

</html>
