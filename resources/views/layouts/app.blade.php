<!DOCTYPE html>
<html
    lang="{{ str_replace('_', '-', app()->getLocale()) }}"
>

<head>

    <meta charset="utf-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <meta
        name="csrf-token"
        content="{{ csrf_token() }}"
    >

    <title>

        @yield('title', config('app.name'))

    </title>

    {{-- Bootstrap Icons --}}
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css"
        rel="stylesheet"
    >

    {{-- Fonts --}}
    <link
        rel="preconnect"
        href="https://fonts.bunny.net"
    >

    <link
        href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap"
        rel="stylesheet"
    >

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    {{-- Assets --}}
    @vite([
        'resources/css/app.css',
        'resources/css/dashboard.css',
        'resources/js/app.js',
        'resources/js/dashboard.js'
    ])

    {{-- Extra Head --}}
    @stack('styles')

</head>

<body>

    <div class="layout-wrapper">

        {{-- TOPBAR --}}
        @include('layouts.partials.topbar')

        <div class="main-area">

            {{-- SIDEBAR --}}
            @auth

                @if(auth()->user()->role === 'guru')

                    @include('layouts.sidebar.guru')

                @elseif(auth()->user()->role === 'superadmin')

                    @include('layouts.sidebar.superadmin')

                @elseif(auth()->user()->role === 'tu')

                    @include('layouts.sidebar.tu')

                @elseif(auth()->user()->role === 'siswa')

                    @include('layouts.sidebar.siswa')

                @endif

            @endauth

            {{-- CONTENT --}}
            <main class="content">

                @yield('content')

            </main>

        </div>

    </div>

    {{-- TOAST --}}
    @include('layouts.partials.toast')

    {{-- EXTRA SCRIPTS --}}
    @stack('scripts')

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>