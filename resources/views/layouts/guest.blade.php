<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#2563eb">
    <meta
        name="apple-mobile-web-app-capable"
        content="yes"
    >
    <meta
        name="apple-mobile-web-app-status-bar-style"
        content="default"
    >
    <link rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link
        rel="icon"
        type="image/png"
        href="{{ asset('favicon.png') }}"
    >
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    

    <title>{{ config('app.name', 'Laravel') }} - Login</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- INLINE CSS (KHUSUS AUTH) --}}
    <style>
        body {
            margin: 0;
            font-family: 'Figtree', sans-serif;
            min-height: 100vh;
             
            /* BACKGROUND IMAGE */
            background-image: url('{{ asset('assets/images/Bg-animated.svg') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        .auth-wrapper {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px;

        }
    </style>
</head>

<body>
    <div class="auth-wrapper">
        {{ $slot }}
    </div>
</body>
</html>
