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
    <meta name="theme-color" content="#2563eb">

    <meta
        name="apple-mobile-web-app-capable"
        content="yes"
    >

    <meta
        name="apple-mobile-web-app-status-bar-style"
        content="default"
    >

    <meta
        name="apple-mobile-web-app-title"
        content="SINERGI MJ"
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

    {{-- Assets --}}
    @vite([
        'resources/css/app.css',
        'resources/css/dashboard.css',
        'resources/js/app.js',
        'resources/js/dashboard.js'
    ])

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
        rel="stylesheet"
    >

    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet"
    >

    <style>

        body{
            font-family:'Poppins',sans-serif;
            background:#f5f7fb;
        }

        .navbar-brand{
            font-weight:700;
        }

        .hero-gradient{
            background:linear-gradient(
                135deg,
                #0d6efd,
                #3b82f6
            );
        }

        .card{
            border:none;
            border-radius:18px;
        }

        .shadow-soft{
            box-shadow:
                0 10px 30px rgba(0,0,0,.08);
        }

        footer{
            background:#fff;
            border-top:1px solid #e9ecef;
        }

        @media (max-width: 992px) {

            .navbar-brand{
                display:none;
            }

        }

    </style>

    @stack('styles')

</head>

<body>

    {{-- NAVBAR --}}
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">

        <div class="container">

            <a
                class="navbar-brand"
                href="{{ route('ppdb.informasi') }}"
            >
                <i class="bi bi-mortarboard-fill me-2"></i>
                PPDB SDN Mekar Jaya
            </a>

            <button
                class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarPublic"
            >
                <span class="navbar-toggler-icon"></span>
            </button>

            <div
                class="collapse navbar-collapse"
                id="navbarPublic"
            >

                <ul class="navbar-nav ms-auto">

                    <li class="nav-item">
                        <a
                            class="nav-link"
                            href="{{ route('ppdb.informasi') }}"
                        >
                            Beranda
                        </a>
                    </li>

                    <li class="nav-item">
                        <a
                            class="nav-link"
                            href="{{ route('ppdb.alur') }}"
                        >
                            Alur PPDB
                        </a>
                    </li>

                    <li class="nav-item">
                        <a
                            class="nav-link"
                            href="{{ route('ppdb.status') }}"
                        >
                            Cek Status
                        </a>
                    </li>

                    <li class="nav-item">
                        <a
                            class="nav-link"
                            href="{{ route('ppdb.daftar-ulang') }}"
                        >
                            Daftar Ulang
                        </a>
                    </li>

                </ul>

            </div>

        </div>

    </nav>

    {{-- CONTENT --}}
    <main>

        @yield('content')

    </main>

    @include('components.modal.confirm')

    {{-- FOOTER --}}
    <footer class="mt-5 py-4">

        <div class="container text-center">

            <h6 class="fw-bold mb-1">
                SDN Mekar Jaya
            </h6>

            <p class="text-muted mb-1">
                Sistem Penerimaan Peserta Didik Baru
            </p>

            <small class="text-muted">
                © {{ date('Y') }} SDN Mekar Jaya
            </small>

        </div>

    </footer>

    @stack('scripts')

</body>

</html>