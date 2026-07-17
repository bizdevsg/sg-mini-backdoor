<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SG Admin')</title>
    <meta name="theme-color" content="#15110d">

    <link rel="icon" type="image/png" href="{{ asset('favicon/favicon-96x96.png') }}" sizes="96x96">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon/favicon.svg') }}">
    <link rel="shortcut icon" href="{{ asset('favicon/favicon.ico') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicon/apple-touch-icon.png') }}">
    <meta name="apple-mobile-web-app-title" content="SG Admin">
    <link rel="manifest" href="{{ asset('favicon/site.webmanifest') }}">

    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>

<body class="min-h-screen bg-obsidian text-champagne">
    <div class="min-h-screen bg-[linear-gradient(180deg,_#15110d_0%,_#221a14_100%)]">
        @yield('content')
    </div>

    @stack('scripts')
</body>

</html>
