<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#28013F">
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/rc-mark.svg') }}">

    <title>@yield('title', config('app.name', 'Rathna Collections'))</title>
    <meta name="description" content="@yield('meta_description', 'Shop the latest textile fashion for Men, Women & Kids at Rathna Collections.')">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    @vite(['resources/scss/app.scss', 'resources/js/app.js'])
    @stack('styles')
</head>
<body>
    @include('partials.frontend.navbar')

    <main class="py-4">
        <div class="container">
            @include('partials.flash')
        </div>

        @yield('content')
    </main>

    @include('partials.frontend.footer')

    @stack('scripts')
</body>
</html>
