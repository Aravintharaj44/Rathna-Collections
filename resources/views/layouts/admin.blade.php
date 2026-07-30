<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/rc-mark.svg') }}">
    <title>@yield('title', 'Dashboard') · Admin · Rathna Collections</title>
    @vite(['resources/scss/app.scss', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-body-tertiary">
    <div class="d-flex">
        @include('partials.admin.sidebar')

        <div class="flex-grow-1 min-vw-0">
            @include('partials.admin.topbar')

            <main class="p-4">
                @include('partials.flash')

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="h3 mb-0">@yield('page_title', 'Dashboard')</h1>
                    @yield('page_actions')
                </div>

                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
