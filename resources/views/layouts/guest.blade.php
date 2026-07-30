<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/rc-mark.svg') }}">
    <title>@yield('title', 'Account') · {{ config('app.name', 'Rathna Collections') }}</title>
    @vite(['resources/scss/app.scss', 'resources/js/app.js'])
</head>
<body class="bg-body-tertiary">
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100 py-5">
            <div class="col-md-6 col-lg-5">
                <div class="text-center mb-4">
                    <a href="{{ route('home') }}">
                        <img src="{{ asset('images/rc-logo.svg') }}" alt="Rathna Collections" class="auth-logo">
                    </a>
                </div>

                <div class="card shadow-sm border-0">
                    <div class="card-body p-4 p-sm-5">
                        @yield('content')
                    </div>
                </div>

                <p class="text-center small text-muted mt-3 mb-0">
                    &copy; {{ date('Y') }} Rathna Collections
                </p>
            </div>
        </div>
    </div>
</body>
</html>
