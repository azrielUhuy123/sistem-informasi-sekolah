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

    {{-- Assets --}}
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
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

        body{
            font-family:'Poppins',sans-serif;
            background:#f5f7fb;
            
        }

        /* =========================================
        ADAPTIVE / CAMOUFLAGE NAVBAR
        ========================================= */

        .public-navbar {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;

            height: 76px;

            background: transparent;

            border-bottom: 1px solid transparent;

            box-shadow: none;

            z-index: 1030;

            transition:
                background-color .35s ease,
                backdrop-filter .35s ease,
                -webkit-backdrop-filter .35s ease,
                box-shadow .35s ease,
                border-color .35s ease,
                color .35s ease;
        }


        /* =========================================
        BRAND
        ========================================= */

        .public-navbar .navbar-brand {
            display: flex;
            align-items: center;

            font-size: 18px;
            font-weight: 700;

            color: #fff;

            letter-spacing: -0.3px;

            transition: color .3s ease;
        }

        .public-navbar .navbar-brand img {
            display: flex;
            align-items: center;
            justify-content: center;

            width: 38px;
            height: 38px;

            margin-right: 10px;

            background: rgba(255,255,255,.14);

            color: #fff;

            border: 1px solid rgba(255,255,255,.20);

            border-radius: 11px;

            font-size: 18px;

            transition:
                background-color .3s ease,
                color .3s ease,
                border-color .3s ease;
        }


        /* =========================================
        NAVIGATION
        ========================================= */

        .public-navbar .navbar-nav {
            align-items: center;
            gap: 4px;
        }

        .public-navbar .nav-link {
            position: relative;

            padding: 9px 14px !important;

            color: rgba(255,255,255,.82);

            font-size: 14px;
            font-weight: 500;

            border-radius: 10px;

            transition:
                background-color .25s ease,
                color .25s ease;
        }

        .public-navbar .nav-link:hover {
            color: #fff;

            background: rgba(255,255,255,.10);
        }


        /* =========================================
        ACTIVE MENU
        ========================================= */

        .public-navbar .nav-link.active {
            color: #fff;

            background: rgba(255,255,255,.14);

            font-weight: 600;
        }


        /* =========================================
        SCROLLED STATE
        ========================================= */

        .public-navbar.scrolled {
            height: 68px;

            background: rgba(255,255,255,.88);

            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);

            border-bottom: 1px solid rgba(15,23,42,.07);

            box-shadow:
                0 8px 30px rgba(15,23,42,.08);
        }


        /* Brand ketika scroll */

        .public-navbar.scrolled .navbar-brand {
            color: #111827;
        }

        .public-navbar.scrolled .navbar-brand i {
            background: #12365d;

            color: #fff;

            border-color: transparent;
        }


        /* Menu ketika scroll */

        .public-navbar.scrolled .nav-link {
            color: #596273;
        }

        .public-navbar.scrolled .nav-link:hover {
            color: #12365d;

            background: #f1f5f9;
        }

        .public-navbar.scrolled .nav-link.active {
            color: #12365d;

            background: #eaf1f8;
        }

        .modal-header{
            border-bottom: none;            
        }        
        
        .tittle-left {
            width: 45%;
            padding: 55px 50px;
        }
        .tittle-header {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px;
        }
        
        .tittle-logo {
            width: 50px;            
        }

        .tittle-school {
            color: #0881c7;
            font-size: 10px;
            line-height: 1.2;
        }

        .tittle-school span {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 6px;
        }              

        @media (max-width: 768px) {                        
            .tittle-left {
                width: 100%;
                padding: 36px 24px;
            }            
            .tittle-logo {
                margin-bottom: 18px;
            }
            .tittle-school {
                font-size: 13px;
            }
            .tittle-school h2 {
                font-size: 24px;
            }
                
        }

        /* =========================================
        MOBILE
        ========================================= */

        @media (max-width: 991.98px) {

            .public-navbar {
                height: 68px;
            }

            .public-navbar .navbar-brand {
                font-size: 16px;
            }

            .public-navbar .navbar-brand i {
                width: 36px;
                height: 36px;
            }

            .public-navbar .navbar-collapse {
                margin-top: 8px;

                padding: 10px;

                background: rgba(18,54,93,.96);

                backdrop-filter: blur(16px);
                -webkit-backdrop-filter: blur(16px);

                border-radius: 16px;

                box-shadow:
                    0 15px 40px rgba(0,0,0,.18);
            }

            .public-navbar .navbar-nav {
                align-items: stretch;
            }

            .public-navbar .nav-link {
                padding: 11px 13px !important;
            }

            .public-navbar.scrolled .navbar-collapse {
                background: rgba(255,255,255,.96);
            }

        }
        /* =========================================
        ERROR MODAL
        ========================================= */

        .error-modal-dialog {
            max-width: 420px;
            margin: 1.75rem auto;
        }

        .error-modal-content {
            border: none;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(15, 23, 42, .18);
        }

        .error-modal-body {
            padding: 36px 32px 30px;
            text-align: center;
        }

        .error-modal-icon {
            width: 64px;
            height: 64px;
            margin: 0 auto 20px;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 50%;

            background: #fff1f2;
            color: #dc3545;

            font-size: 28px;
        }

        .error-modal-title {
            margin-bottom: 8px;

            color: #1f2937;
            font-size: 20px;
            font-weight: 600;
        }

        .error-modal-message {
            margin-bottom: 24px;

            color: #6b7280;
            font-size: 14px;
            line-height: 1.6;
        }

        .error-modal-button {
            width: 100%;
            border: none;
            border-radius: 10px;

            padding: 11px 18px;

            background: #dc3545;
            color: #fff;

            font-size: 14px;
            font-weight: 500;

            transition: .2s ease;
        }

        .error-modal-button:hover {
            background: #bb2d3b;
            color: #fff;
        }

        @media (max-width: 576px) {
            .error-modal-dialog {
                max-width: calc(100% - 32px);
            }

            .error-modal-body {
                padding: 30px 24px 24px;
            }
        }

        .hero-gradient{
            /* BACKGROUND IMAGE */
            min-height: 360px;
            padding-top: 110px;
            padding-bottom: 50px;
            
            background-image: url('{{ asset('assets/images/Bg-animated.svg') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
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

    </style>

    @stack('styles')

</head>

<body>

    {{-- NAVBAR --}}
    <nav class="navbar navbar-expand-lg fixed-top public-navbar">
        <div class="container">

            {{-- BRAND --}}
            <a
                class="navbar-brand"
                href="{{ route('ppdb.informasi') }}"
            >
                <img src="{{ asset('assets/images/logo-kolaan.png') }}" class="tittle-logo">

                <span>PPDB SDN Mekar Jaya</span>
            </a>


            {{-- MOBILE TOGGLE --}}
            <button
                class="navbar-toggler border-0 shadow-none"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarPublic"
                aria-controls="navbarPublic"
                aria-expanded="false"
                aria-label="Buka menu navigasi"
            >
                <span class="navbar-toggler-icon"></span>
            </button>


            {{-- MENU --}}
            <div
                class="collapse navbar-collapse"
                id="navbarPublic"
            >

                <ul class="navbar-nav ms-auto">

                    {{-- BERANDA --}}
                    <li class="nav-item">

                        <a
                            class="nav-link {{ request()->routeIs('ppdb.informasi') ? 'active' : '' }}"
                            href="{{ route('ppdb.informasi') }}"
                        >
                            Beranda
                        </a>

                    </li>

                    


                    {{-- STATUS --}}
                    <li class="nav-item">

                        <a
                            data-bs-toggle="modal"
                            data-bs-target="#ppdbStatusModal"
                            class="nav-link {{ request()->routeIs('ppdb.status') ? 'active' : '' }}"
                            href="{{ route('ppdb.status') }}"
                        >
                            Cek Status
                        </a>

                    </li>


                    {{-- DAFTAR ULANG --}}
                    <li class="nav-item">

                        <a
                            class="nav-link {{ request()->routeIs('ppdb.daftar-ulang') ? 'active' : '' }}"
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

        @if(session('error'))
            <div
                class="modal fade"
                id="errorModal"
                tabindex="-1"
                aria-labelledby="errorModalTitle"
                aria-hidden="true"
            >
                <div class="modal-dialog modal-dialog-centered error-modal-dialog">
                    <div class="modal-content error-modal-content">

                        <div class="modal-body error-modal-body">

                            <div class="error-modal-icon">
                                <i class="bi bi-exclamation-lg"></i>
                            </div>

                            <h5
                                class="error-modal-title"
                                id="errorModalTitle"
                            >
                                Terjadi Kesalahan
                            </h5>

                            <p class="error-modal-message">
                                @if(session('error'))
                                    {{ session('error') }}
                                @elseif($errors->has('keyword'))
                                    {{ $errors->first('keyword') }}
                                @endif
                            </p>

                            <button
                                type="button"
                                class="error-modal-button"
                                data-bs-dismiss="modal"
                            >
                                Tutup
                            </button>

                        </div>

                    </div>
                </div>
            </div>

            <button
                type="button"
                class="d-none"
                data-bs-toggle="modal"
                data-bs-target="#errorModal"
                id="errorModalTrigger"
                tabindex="-1"
                aria-hidden="true"
            ></button>
        @endif

        @if(session('warning'))
            <div class="container pt-4">
                <div class="alert alert-warning alert-dismissible fade show mb-0" role="alert">
                    <i class="bi bi-exclamation-triangle me-2" aria-hidden="true"></i>
                    {{ session('warning') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
                </div>
            </div>
        @endif

        @yield('content')

    </main>

    <div class="modal fade" id="ppdbStatusModal" tabindex="-1" aria-labelledby="ppdbStatusModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="tittle-header" id="ppdbStatusModalTitle">                                                                               
                        <img src="{{ asset('assets/images/logo-kolaan.png') }}" class="tittle-logo">
                        <div class="tittle-school">
                            <span>Cek Status Pendaftaran</span><br>
                            Sekolah Dasar Negeri Mekar Jaya
                        </div>
                    </div>
                    <button type="button" class="btn-close me-3" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>                                                            
                
                <div class="modal-body">
                    <form method="POST" action="{{ route('ppdb.status.cek') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="statusKeyword" class="form-label">Nomor Pendaftaran</label>
                            <input
                                type="text"
                                id="statusKeyword"
                                name="keyword"
                                class="form-control"
                                value="{{ old('keyword') }}"
                                required
                                @error('keyword') aria-describedby="statusKeywordError" @enderror
                            >
                            @error('keyword')
                                <div id="statusKeywordError" class="invalid-feedback d-block" role="alert">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="statusBirthDate" class="form-label">Tanggal Lahir</label>
                            <input
                                type="date"
                                id="statusBirthDate"
                                name="tanggal_lahir"
                                class="form-control"
                                value="{{ old('tanggal_lahir') }}"
                                required
                                @error('tanggal_lahir') aria-describedby="statusBirthDateError" @enderror
                            >
                            @error('tanggal_lahir')
                                <div id="statusBirthDateError" class="invalid-feedback d-block" role="alert">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">Cek Status Pendaftaran</button>
                        </div>                        
                    </form>
                </div>
            </div>
        </div>
    </div>

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

    <script>

    document.addEventListener('DOMContentLoaded', function () {

        const navbar = document.querySelector('.public-navbar');

        if (!navbar) return;

        function updateNavbar() {

            if (window.scrollY > 20) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }

        }

        updateNavbar();

        window.addEventListener(
            'scroll',
            updateNavbar,
            { passive: true }
        );

    });

    </script>

    @if(session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {

                document.getElementById('errorModalTrigger')?.click();

            });
        </script>
    @endif

    @if($errors->has('keyword'))
    @elseif($errors->has('tanggal_lahir'))
    @endif
    @if($errors->has('keyword') || $errors->has('tanggal_lahir'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                document.querySelector('[data-bs-target="#ppdbStatusModal"]')?.click();
            });
        </script>
    @endif

    @stack('scripts')

</body>

</html>