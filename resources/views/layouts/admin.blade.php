<!DOCTYPE html>
<html>
<head>
    <title>@yield('title') - Admin</title>

    <!-- Bootstrap + Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background: #eef1f8;
            transition: 0.3s;
        }

        .layout-wrapper {
            width: 100%;
            height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* TOPBAR */
        .topbar {
            background: #ffffff;
            height: 70px;
            border-bottom: 1px solid #e7e7e7;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 25px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.04);
        }

        .topbar h4 {
            font-weight: 800;
            
            margin: 0;
        }

        /* Mengatur area kanan topbar */
        .topbar-right {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        /* Search box lebih kecil */
        .search-box {
            position: relative;
            width: 260px;
        }

        .search-box input {
            padding-left: 12px;
        }

        /* Sidebar */
        .main-area {
            flex: 1;
            display: flex;
            height: calc(100vh - 70px);
        }

        .sidebar {
            width: 260px;
            background: #ffffff;
            padding: 30px 18px;
            border-right: 1px solid #e8e8e8;
            overflow-y: auto;
        }

        .sidebar-title {
            font-size: 14px;
            color: #8088a2;
            font-weight: 700;
            margin-bottom: 14px;
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
        }

        .menu-item:hover {
            background: #f1f3fb;
            color: #4d5bd8;
        }

        .menu-item.active {
            background: #e9edff;
            color: #4d5bd8 !important;
            font-weight: 600;
        }

        .content {
            flex: 1;
            padding: 30px;
            overflow-y: auto;
        }
        /* Indent submenu (item di dalam dropdown) */
.submenu .menu-item {
    padding-left: 34px !important; 
    font-size: 14px;
    opacity: 0.95;
}

        /* DARK MODE FULL */
.dark-mode {
    background: #1e1f26 !important;
    color: #e6e6e6 !important;
}

.dark-mode .topbar {
    background: #2c2e36 !important;
    border-color: #3a3b44 !important;
}

.dark-mode .sidebar {
    background: #2c2e36 !important;
    border-color: #3a3b44 !important;
}

.dark-mode .menu-item {
    color: #d0d0d0 !important;
}

.dark-mode .menu-item:hover {
    background: #3c3f4a !important;
    color: #ffffff !important;
}

.dark-mode .menu-item.active {
    background: #4d53ff !important;
    color: #fff !important;
}

.dark-mode .content {
    color: #e6e6e6;
}

.dark-mode input, 
.dark-mode .dropdown-menu, 
.dark-mode .card, 
.dark-mode #searchResults {
    background: #2e3038 !important;
    color: #e6e6e6 !important;
    border-color: #444 !important;
}
.search-item {
    cursor: pointer;
    border-bottom: 1px solid #f0f0f0;
}

.search-item:hover {
    background: #f5f6ff;
}

.dark-mode .search-item:hover {
    background: #3a3c45 !important;


}

/* =============== FIX PAGINATION FINAL =============== */

/* Reset seluruh wrapper pagination */
.content .pagination,
.content .pagination * {
    all: initial !important;
    font-family: inherit !important;
    box-sizing: border-box !important;
}

/* Pagination container */
.content .pagination {
    display: flex !important;
    gap: 6px !important;
    align-items: center !important;
}

/* Pagination item */
.content .pagination li {
    list-style: none !important;
    display: block !important;
}

/* Links */
.content .pagination a,
.content .pagination span {
    display: inline-block !important;
    padding: 6px 12px !important;
    border: 1px solid #d0d0d0 !important;
    border-radius: 6px !important;
    background: #ffffff !important;
    color: #4d5bd8 !important;
    font-size: 14px !important;
    text-decoration: none !important;
    cursor: pointer !important;
}

/* SVG arrows */
.content .pagination svg {
    width: 14px !important;
    height: 14px !important;
    stroke: #4d5bd8 !important;
    stroke-width: 2 !important;
    display: inline-block !important;
}

/* Hover effect */
.content .pagination a:hover {
    background: #eef0ff !important;
}

/* Active page */
.content .pagination .active span {
    background: #4d5bd8 !important;
    color: white !important;
}


    </style>
</head>

<body>

<div class="layout-wrapper">

    <!-- TOPBAR -->
    <div class="topbar">

        <!-- KIRI -->
        <h4>SDN MEKAR JAYA</h4>

        <!-- KANAN -->
        <div class="topbar-right">

            <!-- SEARCH -->
            <div class="search-box">
                <input type="text" id="globalSearch" class="form-control shadow-sm" placeholder="Cari siswa / kelas...">
                <div id="searchResults" class="bg-white border rounded shadow-sm mt-1"
                     style="display:none; position:absolute; width:100%; z-index:1000;"></div>
            </div>

            <!-- DARK MODE -->
            <button id="darkToggle" class="btn">🌙</button>

            <!-- PROFIL -->
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

            <div class="sidebar-title">ADMINISTRATOR</div>

            <a class="menu-item {{ request()->is('admin/dashboard') ? 'active' : '' }}"
               href="{{ route('admin.dashboard') }}">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>

            <!-- USER MANAGEMENT -->
            <a class="menu-item dropdown-toggle" data-bs-toggle="collapse" href="#menuUser">
                <i class="bi bi-people"></i> Manajemen User
            </a>

            <div class="collapse show submenu" id="menuUser">
                <a class="menu-item {{ request()->is('admin/users*') ? 'active' : '' }}"
                href="{{ route('admin.users.index') }}">
                    <i class="bi bi-person-gear"></i> Data User
                </a>

                <a class="menu-item" href="#">
                    <i class="bi bi-shield-lock"></i> Role & Akses
                </a>
            </div>

            <!-- MASTER DATA -->
            <a class="menu-item dropdown-toggle" data-bs-toggle="collapse" href="#menuMaster">
                <i class="bi bi-database"></i> Master Sistem
            </a>

            <div class="collapse submenu" id="menuMaster">
                <a class="menu-item" href="#">
                    <i class="bi bi-gear"></i> Pengaturan Sistem
                </a>
            </div>
        </div>


        <!-- CONTENT -->
        <div class="content">
            @yield('content')
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.getElementById("darkToggle").onclick = function () {
    document.body.classList.toggle("dark-mode");
};
</script>
<script>
/* ============================
   🔥 DARK MODE + LOCALSTORAGE
   ============================ */

const darkToggle = document.getElementById("darkToggle");

// Load dark mode dari localStorage
if (localStorage.getItem("darkMode") === "enabled") {
    document.body.classList.add("dark-mode");
    darkToggle.innerText = "☀️";
}

darkToggle.onclick = function () {
    document.body.classList.toggle("dark-mode");

    if (document.body.classList.contains("dark-mode")) {
        localStorage.setItem("darkMode", "enabled");
        darkToggle.innerText = "☀️";
    } else {
        localStorage.setItem("darkMode", "disabled");
        darkToggle.innerText = "🌙";
    }
};


// Klik luar → tutup dropdown
document.addEventListener("click", function (e) {
    if (!searchInput.contains(e.target)) {
        searchResults.style.display = "none";
    }
});
</script>

@yield('scripts')

</body>
</html>
