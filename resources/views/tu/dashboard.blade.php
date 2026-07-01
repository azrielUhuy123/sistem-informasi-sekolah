@extends('layouts.app')

@section('title', 'Dashboard TU')

@section('content')
<div class="container-fluid px-0">
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
        <div class="card-body p-4 p-lg-5 text-white" style="background: linear-gradient(135deg, #0f4c81 0%, #2563eb 100%);">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                <div>
                    <h3 class="fw-bold mb-2">Dashboard Operator Sekolah</h3>
                    <p class="mb-0 opacity-75">Ringkasan data administrasi sekolah, status sistem, dan akses cepat ke menu utama.</p>
                </div>
                <div class="badge bg-white text-primary px-3 py-2 fs-6">
                    <i class="bi bi-shield-check me-2"></i> Sistem Terhubung
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100 dashboard-card">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="rounded-circle bg-primary bg-opacity-10 p-2">
                            <i class="bi bi-person-workspace text-primary fs-5"></i>
                        </div>
                        <span class="badge bg-light text-muted">Guru</span>
                    </div>
                    <h2 class="fw-bold mb-0">{{ $guru }}</h2>
                    <p class="text-muted small mb-0 mt-2">Jumlah tenaga pendidik</p>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100 dashboard-card">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="rounded-circle bg-success bg-opacity-10 p-2">
                            <i class="bi bi-people text-success fs-5"></i>
                        </div>
                        <span class="badge bg-light text-muted">Siswa</span>
                    </div>
                    <h2 class="fw-bold mb-0">{{ $siswa }}</h2>
                    <p class="text-muted small mb-0 mt-2">Jumlah peserta didik</p>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100 dashboard-card">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="rounded-circle bg-warning bg-opacity-10 p-2">
                            <i class="bi bi-file-earmark-text text-warning fs-5"></i>
                        </div>
                        <span class="badge bg-light text-muted">Dokumen</span>
                    </div>
                    <h2 class="fw-bold mb-0">{{ $dokumen }}</h2>
                    <p class="text-muted small mb-0 mt-2">Dokumen terdata</p>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100 dashboard-card">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="rounded-circle bg-info bg-opacity-10 p-2">
                            <i class="bi bi-person-badge text-info fs-5"></i>
                        </div>
                        <span class="badge bg-light text-muted">Akun</span>
                    </div>
                    <h2 class="fw-bold mb-0">{{ $akun }}</h2>
                    <p class="text-muted small mb-0 mt-2">Akun pengguna aktif</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12 col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h4 class="fw-semibold mb-1">Akses Cepat</h4>
                            <p class="text-muted small mb-0">Menu utama yang sering dipakai untuk pengelolaan administrasi.</p>
                        </div>
                        <span class="badge bg-light text-dark">Menu utama</span>
                    </div>

                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <a href="{{ route('tu.kelas.index') }}" class="text-decoration-none">
                                <div class="border rounded-4 p-3 h-100 quick-link">
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="rounded-circle bg-primary bg-opacity-10 p-2 me-2">
                                            <i class="bi bi-building text-primary"></i>
                                        </div>
                                        <h6 class="mb-0 fw-semibold text-dark">Manajemen Kelas</h6>
                                    </div>
                                    <p class="small text-muted mb-0">Kelola data kelas, wali kelas, dan daftar rombel secara terstruktur.</p>
                                </div>
                            </a>
                        </div>

                        <div class="col-12 col-md-6">
                            <a href="{{ route('tu.siswa.index') }}" class="text-decoration-none">
                                <div class="border rounded-4 p-3 h-100 quick-link">
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="rounded-circle bg-success bg-opacity-10 p-2 me-2">
                                            <i class="bi bi-people text-success"></i>
                                        </div>
                                        <h6 class="mb-0 fw-semibold text-dark">Manajemen Siswa</h6>
                                    </div>
                                    <p class="small text-muted mb-0">Pantau dan kelola data siswa dengan cepat dan akurat.</p>
                                </div>
                            </a>
                        </div>

                        <div class="col-12 col-md-6">
                            <a href="{{ route('tu.mapel.index') }}" class="text-decoration-none">
                                <div class="border rounded-4 p-3 h-100 quick-link">
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="rounded-circle bg-warning bg-opacity-10 p-2 me-2">
                                            <i class="bi bi-book text-warning"></i>
                                        </div>
                                        <h6 class="mb-0 fw-semibold text-dark">Manajemen Mata Pelajaran</h6>
                                    </div>
                                    <p class="small text-muted mb-0">Kelola data mata pelajaran yang diajarkan di sekolah.</p>
                                </div>
                            </a>
                        </div>

                        <div class="col-12 col-md-6">
                            <a href="{{ route('tu.dokumen.sekolah') }}" class="text-decoration-none">
                                <div class="border rounded-4 p-3 h-100 quick-link">
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="rounded-circle bg-info bg-opacity-10 p-2 me-2">
                                            <i class="bi bi-file-earmark-arrow-up text-info"></i>
                                        </div>
                                        <h6 class="mb-0 fw-semibold text-dark">Manajemen Dokumen</h6>
                                    </div>
                                    <p class="small text-muted mb-0">Atur dokumen sekolah dan file administrasi penting.</p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <h5 class="fw-semibold mb-3">Ringkasan</h5>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item px-0 py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted">Status sistem</span>
                                <span class="badge bg-success-subtle text-success">Aktif</span>
                            </div>
                        </li>
                        <li class="list-group-item px-0 py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted">Akses cepat</span>
                                <span class="fw-semibold">4 menu</span>
                            </div>
                        </li>
                        <li class="list-group-item px-0 py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted">Peran akun</span>
                                <span class="fw-semibold">Operator</span>
                            </div>
                        </li>
                    </ul>

                    <div class="card border-0 bg-light rounded-4 mt-3">
                        <div class="card-body p-3">
                            <h6 class="fw-semibold mb-2">Informasi</h6>
                            <p class="small text-muted mb-0">Dashboard ini dirancang untuk memudahkan operator sekolah memantau data inti dan mengakses fitur utama secara cepat.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 mt-4">
        <div class="card-header bg-white border-0 pt-4 px-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0">Distribusi Pengguna Sistem</h5>
                    <p class="small text-muted mb-0">Visualisasi jumlah data utama dalam sistem.</p>
                </div>
            </div>
        </div>
        <div class="card-body p-4">
            <div style="max-width: 720px; margin: 0 auto;">
                <canvas id="chartRole"></canvas>
            </div>
        </div>
    </div>
</div>

<style>
    .dashboard-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .dashboard-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.75rem 1.5rem rgba(15, 76, 129, 0.12) !important;
    }

    .quick-link {
        transition: all 0.2s ease;
    }

    .quick-link:hover {
        border-color: #2563eb !important;
        background-color: #f8fbff;
        transform: translateY(-2px);
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('chartRole').getContext('2d');
    const chartRole = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Guru','Siswa','TU','Super Admin'],

            datasets: [
            {
                label: 'Aktif',
                data: [
                    {{ $guruAktif }},
                    {{ $siswaAktif }},
                    {{ $tuAktif }},
                    {{ $superadminAktif }}
                ],
                backgroundColor: '#4CAF50'
            },
            {
                label: 'Nonaktif',
                data: [
                    {{ $guruNonaktif }},
                    {{ $siswaNonaktif }},
                    {{ $tuNonaktif }},
                    {{ $superadminNonaktif }}
                ],
                backgroundColor: '#F44336'
            }
            ]
        },
        options: {
            responsive: true,
            indexAxis: 'y',
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        padding: 16
                    }
                },
                tooltip: {
                    backgroundColor: '#0f172a',
                    titleColor: '#ffffff',
                    bodyColor: '#e2e8f0',
                    padding: 10
                }
            },
            scales: {
                x: {
                    stacked: true,
                    beginAtZero: true,
                    grid: {
                        drawBorder: false,
                        color: 'rgba(148, 163, 184, 0.2)'
                    },
                    ticks: {
                        color: '#64748b'
                    }
                },
                y: {
                    stacked: true,
                    grid: {
                        drawBorder: false,
                        display: false
                    },
                    ticks: {
                        color: '#334155'
                    }
                }
            }
        }
    });
</script>
@endsection
