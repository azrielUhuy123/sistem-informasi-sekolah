<!DOCTYPE html>
<html>
<head>
    <title>@yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            display: flex;
            min-height: 100vh;
        }
        .sidebar {
            width: 250px;
            background: #2c3e50;
            color: white;
            min-height: 100vh;
            padding: 20px;
        }
        .sidebar a {
            color: white;
            text-decoration: none;
            display: block;
            padding: 10px 0;
            margin-bottom: 5px;
        }
        .sidebar a:hover {
            background: #34495e;
            padding-left: 5px;
            transition: 0.2s;
        }
        .content-wrapper {
            flex: 1;
            display: flex;
            flex-direction: column;
            background: #f8f9fa;
        }
        .topbar {
            background: white;
            padding: 15px 25px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .content {
            padding: 25px;
        }
    </style>
</head>
<body>

    <!-- SIDEBAR -->
    <div class="sidebar">
        <h4>Menu TU</h4>
        <hr>

        <ul class="nav flex-column">

    <li class="nav-item">
        <a class="nav-link" href="{{ route('tu.dashboard') }}">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>
    </li>

    <!-- DROPDOWN MANAJEMEN SEKOLAH -->
    <li class="nav-item">
        <a class="nav-link dropdown-toggle" data-bs-toggle="collapse" href="#menuSekolah" role="button">
            <i class="bi bi-building"></i> Manajemen Sekolah
        </a>

        <div class="collapse ms-3" id="menuSekolah">

            <a class="nav-link" href="{{ route('tu.kelas.index') }}">
                Kelas
            </a>

            <a class="nav-link" href="{{ route('tu.tahun.index') }}">
                Tahun Ajaran
            </a>

            <a class="nav-link" href="{{ route('tu.siswa.index') }}">
                Siswa
            </a>

            <a class="nav-link" href="{{ route('tu.guru.index') }}">
                Guru
            </a>

            <a class="nav-link" href="{{ route('tu.jadwal.index') }}">
                Jadwal
            </a>

            <a class="nav-link" href="{{ route('tu.mapel.index') }}">
                Mata Pelajaran
            </a>

            <a class="nav-link" href="{{ route('tu.ruangan.index') }}">
                Ruangan
            </a>

            <a class="nav-link" href="{{ route('tu.rwakajai angan.index') }}">
                Ruangan
            </a>
        </div>
    </li>

</ul>

    </div>

    <!-- KANAN -->
    <div class="content-wrapper">

        <!-- TOPBAR -->
        <div class="topbar">
            <h4 class="m-0"><b>SDN MEKAR JAYA</b></h4>

            <div style="position: relative; flex:1; max-width: 400px;" class="ms-4">
                <input type="text" id="globalSearch" class="form-control" placeholder="Cari siswa / kelas...">

                <!-- Dropdown hasil -->
                <div id="searchResults"
                    class="bg-white border rounded shadow-sm mt-1"
                    style="display:none; position:absolute; width:100%; z-index:1000;">
                </div>
            </div>



            <!-- Dropdown Profil -->
            <div class="dropdown">
                <a class="btn btn-light dropdown-toggle" href="#" data-bs-toggle="dropdown">
                    {{ Auth::user()->name }}
                </a>

                <ul class="dropdown-menu dropdown-menu-end">
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

        <!-- CONTENT -->
        <div class="content">
            @yield('content')
        </div>

    </div>
<script>
document.getElementById('globalSearch').addEventListener('keyup', function() {
    let q = this.value;

    if (q.length < 2) {
        document.getElementById('searchResults').style.display = 'none';
        return;
    }

    fetch("{{ route('tu.search.ajax') }}?q=" + q)
        .then(response => response.json())
        .then(data => {
            let html = "";

            if (data.siswa.length === 0 && data.kelas.length === 0) {
                html = "<div class='p-2 text-muted'>Tidak ada hasil</div>";
            }

            if (data.siswa.length > 0) {
                html += "<div class='p-2 fw-bold border-bottom bg-light'>Siswa</div>";
                data.siswa.forEach(s => {
                    html += `
                        <a href="/tu/siswa/${s.id}/edit" class="p-2 d-block text-dark" style="text-decoration:none;">
                            ${s.nama} — NIS: ${s.nis}
                        </a>
                    `;
                });
            }

            if (data.kelas.length > 0) {
                html += "<div class='p-2 fw-bold border-bottom bg-light mt-2'>Kelas</div>";
                data.kelas.forEach(k => {
                    html += `
                        <a href="/tu/kelas/${k.id}/edit" class="p-2 d-block text-dark" style="text-decoration:none;">
                            ${k.nama_kelas} — Tingkat ${k.tingkat}
                        </a>
                    `;
                });
            }

            let resultsBox = document.getElementById('searchResults');
            resultsBox.innerHTML = html;
            resultsBox.style.display = 'block';
        });
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
