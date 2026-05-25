<!DOCTYPE html>
<html>
<head>

    <title>@yield('title')</title>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css"
        rel="stylesheet"
    >

    <meta
        name="csrf-token"
        content="{{ csrf_token() }}"
    >

    <style>

        body{
            margin:0;
            font-family:'Segoe UI',sans-serif;
            background:#eef1f8;
            transition:.3s;
        }

        .layout-wrapper{
            width:100%;
            height:100vh;
            display:flex;
            flex-direction:column;
        }

        /* TOPBAR */

        .topbar{
            background:#fff;
            height:70px;
            border-bottom:1px solid #e7e7e7;
            display:flex;
            align-items:center;
            justify-content:space-between;
            padding:0 25px;
            box-shadow:0 2px 6px rgba(0,0,0,.04);
        }

        .topbar-brand{
            display:flex;
            align-items:center;
            gap:12px;
        }

        .topbar-brand img{
            width:42px;
            height:42px;
            object-fit:contain;
        }

        .topbar-brand h4{
            margin:0;
            font-weight:800;
            font-size:22px;
        }

        .topbar-right{
            display:flex;
            align-items:center;
            gap:12px;
        }

        /* MAIN */

        .main-area{
            flex:1;
            display:flex;
            height:calc(100vh - 70px);
        }

        /* SIDEBAR */

        .sidebar{
            width:260px;
            background:#fff;
            padding:28px 20px;
            border-right:1px solid #eef0f5;
            overflow-y:auto;
        }

        .sidebar-title{
            font-size:12px;
            font-weight:700;
            letter-spacing:.08em;
            text-transform:uppercase;
            color:#9aa3b2;
            margin-bottom:18px;
        }

        .menu-item{
            display:flex;
            align-items:center;
            justify-content:space-between;
            padding:12px 16px;
            height:46px;
            border-radius:10px;
            font-size:14.5px;
            font-weight:500;
            text-decoration:none;
            color:#4a4f63;
            margin-bottom:6px;
            transition:.2s;
            width:100%;
            white-space:nowrap;
        }

        .menu-left{
            display:flex;
            align-items:center;
            gap:14px;
        }

        .menu-item:hover{
            background:#f2f4ff;
            color:#3f51f5;
        }

        .menu-item.active{
            background:#e8edff;
            color:#3f51f5;
            font-weight:600;
        }

        .dropdown-arrow{
            font-size:13px;
            transition:.25s;
            opacity:.6;
        }

        .rotate{
            transform:rotate(90deg);
        }

        .submenu{
            display:none;
            margin-top:4px;
            padding-left:16px;
            border-left:2px solid #edf0ff;
        }

        .submenu.show{
            display:block;
        }

        .submenu .menu-item{
            height:42px;
            font-size:13.5px;
            padding-left:18px;
        }

        /* CONTENT */

        .content{
            flex:1;
            padding:30px;
            overflow-y:auto;
        }

        /* DARK MODE */

        .dark-mode{
            background:#1e1f26 !important;
            color:#e6e6e6 !important;
        }

        .dark-mode .topbar,
        .dark-mode .sidebar{
            background:#2c2e36 !important;
            border-color:#3a3b44 !important;
        }

        .dark-mode .menu-item{
            color:#d0d0d0 !important;
        }

        .dark-mode .menu-item:hover{
            background:#3c3f4a !important;
            color:#fff !important;
        }

        .dark-mode .menu-item.active{
            background:#4d53ff !important;
            color:#fff !important;
        }

        .dark-mode .card{
            background:#2e3038 !important;
            color:#e6e6e6 !important;
            border-color:#444 !important;
        }

    </style>

</head>

<body>

<div class="layout-wrapper">

    {{-- TOPBAR --}}
    <div class="topbar">

        <div class="topbar-brand">

            <img
                src="{{ asset('images/logo.png') }}"
                alt="Logo"
            >

            <h4>SDN MEKAR JAYA</h4>

        </div>

        <div class="topbar-right">

            <button
                id="darkToggle"
                class="btn"
            >
                🌙
            </button>

            <div class="dropdown">

                <a
                    class="btn btn-light shadow-sm dropdown-toggle"
                    href="#"
                    data-bs-toggle="dropdown"
                >

                    {{ Auth::user()->name }}

                </a>

                <ul class="dropdown-menu dropdown-menu-end shadow-sm">

                    <li>
                        <a
                            class="dropdown-item"
                            href="#"
                        >
                            Profil
                        </a>
                    </li>

                    <li>
                        <hr class="dropdown-divider">
                    </li>

                    <li>

                        <form
                            action="{{ route('logout') }}"
                            method="POST"
                        >

                            @csrf

                            <button class="dropdown-item">
                                Logout
                            </button>

                        </form>

                    </li>

                </ul>

            </div>

        </div>

    </div>

    {{-- MAIN --}}
    <div class="main-area">

        {{-- SIDEBAR --}}
        @include('layouts.sidebar.siswa')

        {{-- CONTENT --}}
        <div class="content">

            @yield('content')

        </div>

    </div>

</div>

<script>

    const darkToggle =
        document.getElementById('darkToggle');

    if(localStorage.getItem('darkMode') === 'enabled'){

        document.body.classList.add('dark-mode');

        darkToggle.innerText = '☀️';

    }

    darkToggle.addEventListener('click',function(){

        document.body.classList.toggle('dark-mode');

        if(document.body.classList.contains('dark-mode')){

            localStorage.setItem(
                'darkMode',
                'enabled'
            );

            darkToggle.innerText='☀️';

        }else{

            localStorage.setItem(
                'darkMode',
                'disabled'
            );

            darkToggle.innerText='🌙';

        }

    });

    function toggleMenu(menuId,arrowId){

        const menu =
            document.getElementById(menuId);

        const arrow =
            document.getElementById(arrowId);

        menu.classList.toggle('show');

        arrow.classList.toggle('rotate');

    }

</script>

@yield('scripts')

</body>
</html>