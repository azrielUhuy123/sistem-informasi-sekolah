<!DOCTYPE html>
<html>
<head>
    <title>@yield('title')</title>

    <!-- Bootstrap + Icons -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

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
            padding: 28px 20px;
            border-right: 1px solid #eef0f5;
            overflow-y: auto;
        }

        .sidebar-title {
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #9aa3b2;
            margin-bottom: 18px;
        }

        .menu-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 16px;
            height: 46px;
            border-radius: 10px;
            font-size: 14.5px;
            font-weight: 500;
            text-decoration: none;
            color: #4a4f63;
            margin-bottom: 6px;
            transition: all 0.2s ease;
            width: 100%;
            white-space: nowrap;
            position: relative;
        }

        .menu-left {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .menu-item i {
            font-size: 16px;
            opacity: 0.85;
        }

        .menu-item:hover {
            background: #f2f4ff;
            color: #3f51f5;
        }

        .menu-item.active {
            background: #e8edff;
            color: #3f51f5;
            font-weight: 600;
        }
        .menu-item.active i {
            opacity: 1;
        }

        /* Arrow icon */
        .dropdown-arrow {
            font-size: 13px;
            transition: transform 0.25s ease;
            opacity: 0.6;
        }

        .rotate {
            transform: rotate(90deg);
        }

        .content {
            flex: 1;
            padding: 30px;
            overflow-y: auto;
        }
        /* Indent submenu (item di dalam dropdown) */
        .submenu .menu-item {
            height: 42px;
            font-size: 13.5px;
            font-weight: 500;
            padding-left: 18px;
            color: #5c627a;
            background: transparent;
            margin-bottom: 4px;
        }

        /* Submenu */
        .submenu {
            display: none;
            margin-top: 4px;
            padding-left: 16px;
            border-left: 2px solid #edf0ff;
        }

        .submenu.show {
            display: block;
        }

        .submenu .menu-item:hover {
            background: #f4f6ff;
            color: #3f51f5;
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

    <div class="sidebar-title">MAIN TATA USAHA</div>

    <a class="menu-item {{ request()->is('tu/dashboard') ? 'active' : '' }}"
       href="{{ route('tu.dashboard') }}">
        <div class="menu-left">
            <i class="bi bi-speedometer2"></i>
            <span>Dashboard</span>
        </div>
    </a>

    <!-- Manajemen Sekolah -->
    <button class="menu-item border-0 bg-transparent text-start"
        onclick="toggleMenu('menuSekolah','arrowSekolah')">

        <div class="menu-left">
            <i class="bi bi-building"></i>
            <span>Manajemen Sekolah</span>
        </div>

        <i class="bi bi-chevron-right dropdown-arrow" id="arrowSekolah"></i>
    </button>

    <div class="submenu" id="menuSekolah">

        <a class="menu-item {{ request()->is('tu/kelas*') ? 'active' : '' }}"
            href="{{ route('tu.kelas.index') }}">
            <div class="menu-left">
                <i class="bi bi-people"></i>
                <span>Kelas</span>
            </div>
        </a>

        <a class="menu-item {{ request()->is('tu/tahun*') ? 'active' : '' }}"
            href="{{ route('tu.tahun.index') }}">
            <div class="menu-left">
                <i class="bi bi-calendar3"></i>
                <span>Tahun Ajaran</span>
            </div>
        </a>

        <a class="menu-item {{ request()->is('tu/siswa*') ? 'active' : '' }}"
            href="{{ route('tu.siswa.index') }}">
            <div class="menu-left">
                <i class="bi bi-mortarboard"></i>
                <span>Siswa</span>
            </div>
        </a>

        <a class="menu-item {{ request()->is('tu/guru*') ? 'active' : '' }}"
            href="{{ route('tu.guru.index') }}">
            <div class="menu-left">
                <i class="bi bi-person-badge"></i>
                <span>Guru</span>
            </div>
        </a>

        <a class="menu-item {{ request()->is('tu/jadwal*') ? 'active' : '' }}"
            href="{{ route('tu.jadwal.index') }}">
            <div class="menu-left">
                <i class="bi bi-calendar-week"></i>
                <span>Jadwal</span>
            </div>
        </a>

        <a class="menu-item {{ request()->is('tu/mapel*') ? 'active' : '' }}"
            href="{{ route('tu.mapel.index') }}">
            <div class="menu-left">
                <i class="bi bi-journal-text"></i>
                <span>Mata Pelajaran</span>
            </div>
        </a>

        <a class="menu-item {{ request()->is('tu/ruangan*') ? 'active' : '' }}"
            href="{{ route('tu.ruangan.index') }}">
            <div class="menu-left">
                <i class="bi bi-door-closed"></i>
                <span>Ruangan</span>
            </div>
        </a>

        <a class="menu-item {{ request()->is('tu/walikelas*') ? 'active' : '' }}"
            href="{{ route('tu.walikelas.index') }}">
            <div class="menu-left">
                <i class="bi bi-person-workspace"></i>
                <span>Wali Kelas</span>
            </div>
        </a>

    </div>

    <!-- Proses Akademik -->
    <button class="menu-item border-0 bg-transparent text-start"
        onclick="toggleMenu('menuProses','arrowProses')">

        <div class="menu-left">
            <i class="bi bi-arrow-repeat"></i>
            <span>Proses Akademik</span>
        </div>

        <i class="bi bi-chevron-right dropdown-arrow" id="arrowProses"></i>
    </button>

    <div class="submenu" id="menuProses">

        <a class="menu-item {{ request()->is('tu/penempatan-siswa/create') ? 'active' : '' }}"
            href="{{ route('tu.penempatan-siswa.create') }}">
            <div class="menu-left">
                <i class="bi bi-person-plus"></i>
                <span>Penempatan Siswa</span>
            </div>
        </a>

        <a class="menu-item {{ request()->is('tu/penempatan-siswa/preview-naik-kelas') ? 'active' : '' }}"
            href="{{ route('tu.penempatan-siswa.naik-kelas.index') }}">
            <div class="menu-left">
                <i class="bi bi-arrow-up-circle"></i>
                <span>Naik Kelas</span>
            </div>
        </a>

    </div>
    <!-- Arsip -->
    <button class="menu-item border-0 bg-transparent text-start"
        onclick="toggleMenu('menuArsip','arrowArsip')">

        <div class="menu-left">
            <i class="bi bi-folder2-open"></i>
            <span>Arsip & Dokumen</span>
        </div>

        <i class="bi bi-chevron-right dropdown-arrow" id="arrowArsip"></i>
    </button>

    <div class="submenu" id="menuArsip">

        <a class="menu-item {{ request()->is('tu/arsip-dokumen*') ? 'active' : '' }}"
           href="{{ route('tu.arsip-dokumen.index') }}">
            <div class="menu-left">
                <i class="bi bi-file-earmark-text"></i>
                <span>Surat</span>
            </div>
        </a>

        <a class="menu-item {{ request()->is('tu/datakepegawaian*') ? 'active' : '' }}"
           href="{{ route('tu.datakepegawaian.index') }}">
            <div class="menu-left">
                <i class="bi bi-people-fill"></i>
                <span>Data Kepegawaian</span>
            </div>
        </a>

    </div>

</div>


        <!-- CONTENT -->
        <div class="content">
            @yield('content')
        </div>

    </div>
</div>
<div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999">
    <div id="appToast" class="toast align-items-center border-0" role="alert">
        <div class="d-flex">
            <div class="toast-body fw-semibold"></div>
            <button type="button" class="btn-close me-2 m-auto"
                data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {

    /* DARK MODE */
    const darkToggle = document.getElementById("darkToggle");

    if (darkToggle) {
        if (localStorage.getItem("darkMode") === "enabled") {
            document.body.classList.add("dark-mode");
            darkToggle.innerText = "☀️";
        }

        darkToggle.addEventListener("click", function () {
            document.body.classList.toggle("dark-mode");

            if (document.body.classList.contains("dark-mode")) {
                localStorage.setItem("darkMode", "enabled");
                darkToggle.innerText = "☀️";
            } else {
                localStorage.setItem("darkMode", "disabled");
                darkToggle.innerText = "🌙";
            }
        });
    }

    /* GLOBAL SEARCH */
    const searchInput = document.getElementById("globalSearch");
    const searchResults = document.getElementById("searchResults");

    if (searchInput) {
        searchInput.addEventListener("keyup", function () {
            let query = this.value.trim();

            if (query.length < 2) {
                searchResults.style.display = "none";
                return;
            }

            fetch(`/tu/search-ajax?q=${query}`)
                .then(res => res.json())
                .then(data => {
                    let html = "";

                    if (data.siswa.length === 0 && data.kelas.length === 0) {
                        html = `<div class="p-2 text-muted">Tidak ada hasil...</div>`;
                    }

                    if (data.siswa.length > 0) {
                        html += `<div class="fw-bold px-2 pt-2">Siswa</div>`;
                        data.siswa.forEach(s => {
                            html += `
                                <div class="p-2 search-item" onclick="window.location='/tu/siswa/${s.id}'">
                                    <i class="bi bi-person me-2"></i> ${s.nama}
                                    <div class="small text-muted">${s.nis}</div>
                                </div>
                            `;
                        });
                    }

                    if (data.kelas.length > 0) {
                        html += `<div class="fw-bold px-2 pt-2">Kelas</div>`;
                        data.kelas.forEach(k => {
                            html += `
                                <div class="p-2 search-item" onclick="window.location='/tu/kelas/${k.id}'">
                                    <i class="bi bi-people me-2"></i> ${k.nama_kelas}
                                </div>
                            `;
                        });
                    }

                    searchResults.innerHTML = html;
                    searchResults.style.display = "block";
                });
        });

        document.addEventListener("click", function (e) {

            if (!searchInput || !searchResults) return;

            // 🔥 JANGAN ganggu klik di sidebar
            if (e.target.closest('.sidebar')) return;

            if (
                !searchInput.contains(e.target) &&
                !searchResults.contains(e.target)
            ) {
                searchResults.style.display = "none";
            }
        });

        // 🔥 Restore sidebar state
document.addEventListener("DOMContentLoaded", function () {

    const menus = [
        { id: 'menuSekolah', arrow: 'arrowSekolah' },
        { id: 'menuProses', arrow: 'arrowProses' },
        { id: 'menuArsip', arrow: 'arrowArsip' }
    ];

    menus.forEach(m => {
        if (localStorage.getItem(m.id) === "open") {
            const menu = document.getElementById(m.id);
            const arrow = document.getElementById(m.arrow);

            if (menu) menu.classList.add("show");
            if (arrow) arrow.classList.add("rotate");
        }
    });

});

// 🔥 Auto open kalau ada menu active
document.addEventListener("DOMContentLoaded", function () {

    const submenus = document.querySelectorAll(".submenu");

    submenus.forEach(menu => {
        if (menu.querySelector(".menu-item.active")) {
            menu.classList.add("show");

            const arrow = menu.previousElementSibling.querySelector(".dropdown-arrow");
            if (arrow) arrow.classList.add("rotate");
        }
    });

});



    }

});
</script>

<script>
function showToast(message, type = 'success') {

    const toastEl = document.getElementById('appToast');
    const toastBody = toastEl.querySelector('.toast-body');

    // Reset class
    toastEl.classList.remove('bg-success', 'bg-danger', 'text-white');

    if (type === 'success') {
        toastEl.classList.add('bg-success', 'text-white');
    }

    if (type === 'error') {
        toastEl.classList.add('bg-danger', 'text-white');
    }

    toastBody.innerText = message;

    const toast = new bootstrap.Toast(toastEl, {
        delay: 3000
    });

    toast.show();
}
</script>

<script>
function toggleSekolah() {
    const menu = document.getElementById("menuSekolah");
    if (menu.style.display === "none") {
        menu.style.display = "block";
    } else {
        menu.style.display = "none";
    }
}
</script>

<script>
function toggleMenu(menuId, arrowId) {

    const menu = document.getElementById(menuId);
    const arrow = document.getElementById(arrowId);

    if (menu.classList.contains("show")) {
        menu.classList.remove("show");
        arrow.classList.remove("rotate");

        localStorage.removeItem(menuId);
    } else {
        menu.classList.add("show");
        arrow.classList.add("rotate");

        localStorage.setItem(menuId, "open");
    }
}
</script>


@yield('scripts')

</body>
</html>
