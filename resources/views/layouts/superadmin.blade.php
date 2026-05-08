<!DOCTYPE html>
<html>
<head>
    <title>@yield('title') - Kepala Sekolah</title>

    <!-- Bootstrap + Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background: #f2f3f8;
        }

        .layout-wrapper {
            width: 100%;
            height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* ===== TOPBAR ===== */
        .topbar {
            background: #ffffff;
            height: 70px;
            border-bottom: 1px solid #e5e7f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 25px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.04);
        }

        .topbar h4 {
            font-weight: 800;
            color: #5b2d8b; /* ungu elegan */
            margin: 0;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        /* ===== MAIN AREA ===== */
        .main-area {
            flex: 1;
            display: flex;
            height: calc(100vh - 70px);
        }

        /* ===== SIDEBAR ===== */
        .sidebar {
            width: 260px;
            background: #ffffff;
            padding: 30px 18px;
            border-right: 1px solid #e5e7f0;
            overflow-y: auto;
        }

        .sidebar-title {
            font-size: 14px;
            color: #7b6f9e;
            font-weight: 700;
            margin-bottom: 14px;
            text-transform: uppercase;
        }

        .menu-item {
            display: flex;
            align-items: center;
            padding: 12px 14px;
            height: 48px;
            border-radius: 12px;
            font-size: 15px;
            text-decoration: none;
            color: #3b3f52;
            margin-bottom: 6px;
            transition: 0.25s;
        }

        .menu-item i {
            margin-right: 12px;
            font-size: 17px;
            color: #5b2d8b;
        }

        .menu-item:hover {
            background: #f2ecfa;
            color: #5b2d8b;
        }

        .menu-item.active {
            background: #e8ddf5;
            color: #5b2d8b !important;
            font-weight: 600;
        }

        .submenu .menu-item {
            padding-left: 34px !important;
            font-size: 14px;
        }

        /* ===== CONTENT ===== */
        .content {
            flex: 1;
            padding: 30px;
            overflow-y: auto;
        }
    </style>
</head>

<body>

<div class="layout-wrapper">

    <!-- TOPBAR -->
    <div class="topbar">
        <h4>SDN MEKAR JAYA</h4>

        <div class="topbar-right">
            <div class="dropdown">
                <a class="btn btn-light shadow-sm dropdown-toggle" href="#" data-bs-toggle="dropdown">
                    {{ Auth::user()->name }}
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                    <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Profil</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button class="dropdown-item">Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- MAIN -->
    <div class="main-area">

        <!-- SIDEBAR -->
        <div class="sidebar">

            <div class="sidebar-title">
                KEPALA SEKOLAH
            </div>

            {{-- DASHBOARD --}}
            <a class="menu-item {{ request()->is('superadmin/dashboard') ? 'active' : '' }}"
            href="{{ route('superadmin.dashboard') }}">

                <i class="bi bi-speedometer2"></i>

                Dashboard

            </a>

            {{-- APPROVAL USER --}}
            <button
                class="menu-item border-0 bg-transparent w-100 text-start"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#approvalMenu"
            >

                <i class="bi bi-person-check"></i>

                Approval User

            </button>

            <div
                class="collapse {{ request()->is('superadmin/users*') ? 'show' : '' }}"
                id="approvalMenu"
            >

                <div class="submenu">

                    <a class="menu-item {{ request()->is('superadmin/users/pending') ? 'active' : '' }}"
                    href="{{ route('superadmin.users.pending') }}">

                        <i class="bi bi-hourglass-split"></i>

                        Pending

                    </a>

                    <a class="menu-item {{ request()->is('superadmin/users/approved') ? 'active' : '' }}"
                    href="{{ route('superadmin.users.approved') }}">

                        <i class="bi bi-check-circle"></i>

                        Disetujui

                    </a>

                    <a class="menu-item {{ request()->is('superadmin/users/rejected') ? 'active' : '' }}"
                    href="{{ route('superadmin.users.rejected') }}">

                        <i class="bi bi-x-circle"></i>

                        Ditolak

                    </a>

                </div>

            </div>

            {{-- MONITORING AKADEMIK --}}
            <button
                class="menu-item border-0 bg-transparent w-100 text-start"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#monitoringMenu"
            >

                <i class="bi bi-bar-chart-line"></i>

                Monitoring Akademik

            </button>

            <div
                class="collapse {{ request()->is('superadmin/monitoring*') ? 'show' : '' }}"
                id="monitoringMenu"
            >

                <div class="submenu">

                    <a class="menu-item {{ request()->is('superadmin/monitoring-jadwal') ? 'active' : '' }}"
                    href="{{ route('superadmin.monitoring-jadwal.index') }}">

                        <i class="bi bi-calendar-week"></i>

                        Monitoring Jadwal

                    </a>

                    <a class="menu-item"
                    href="#">

                        <i class="bi bi-exclamation-triangle"></i>

                        Bentrok Jadwal

                    </a>

                    <a class="menu-item"
                    href="#">

                        <i class="bi bi-grid"></i>

                        Jadwal Final

                    </a>

                </div>

            </div>

            {{-- AKADEMIK --}}
            <button
                class="menu-item border-0 bg-transparent w-100 text-start"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#akademikMenu"
            >

                <i class="bi bi-journal-text"></i>

                Akademik

            </button>

            <div class="collapse" id="akademikMenu">

                <div class="submenu">

                    <a class="menu-item" href="#">
                        <i class="bi bi-book"></i>
                        Mata Pelajaran
                    </a>

                    <a class="menu-item" href="#">
                        <i class="bi bi-people"></i>
                        Guru & Wali Kelas
                    </a>

                    <a class="menu-item" href="#">
                        <i class="bi bi-diagram-3"></i>
                        Struktur Kelas
                    </a>

                </div>

            </div>

        </div>

        <!-- CONTENT -->
        <div class="content">
            @yield('content')
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

@yield('scripts')

</body>
</html>
