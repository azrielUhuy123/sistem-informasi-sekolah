<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >
    <meta
        name="csrf-token"
        content="{{ csrf_token() }}"
    >
    <link
        rel="icon"
        type="image/png"
        href="{{ asset('favicon.png') }}"
    >
    <link rel="manifest" href="{{ asset('manifest.json') }}">

    <title>
        @yield('title', 'PPDB SDN Mekar Jaya')
    </title>

    {{-- Fonts --}}
    <link
        rel="preconnect"
        href="https://fonts.bunny.net"
    >

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
        rel="stylesheet"
    >
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet"
    >
    @vite([
        'resources/css/app.css',
        'resources/css/dashboard.css',
        'resources/js/app.js',
        'resources/js/dashboard.js'
    ])

    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet"
    >

    <style>

        body {
            margin: 0;

            font-family: 'Poppins', sans-serif;

            background-image: url('{{ asset('assets/images/Bg-animated.svg') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        .ppdb-form-page {
            min-height: 100vh;

            padding-top: 40px;
            padding-bottom: 60px;
        }      
    </style>

    @stack('styles')

</head>

<body>

    <main class="ppdb-form-page">

        @yield('content')

    </main>

    {{-- FOOTER --}}
    <footer class="mt-5 py-4">

        <div class="container text-center">

            <h6 class="fw-bold text-white mb-1">
                SDN Mekar Jaya
            </h6>

            <p class="text-white mb-1">
                Sistem Penerimaan Peserta Didik Baru
            </p>

            <small class="text-white">
                © {{ date('Y') }} SDN Mekar Jaya
            </small>

        </div>

    </footer>

    @include('components.modal.confirm')

    @stack('scripts')

</body>

</html>