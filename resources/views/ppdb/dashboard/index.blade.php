@extends('layouts.app')

@section('title', 'Dashboard PPDB')

@section('content')
<div class="container-fluid px-0">
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
        <div class="card-body p-4 p-lg-5 text-white" style="background: linear-gradient(135deg, #0f4c81 0%, #2563eb 100%);">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                <div>
                    <h3 class="fw-bold mb-2">Dashboard PPDB</h3>
                    <p class="mb-0 opacity-75">Ringkasan proses pendaftaran, status verifikasi, dan kuota penerimaan siswa baru.</p>
                </div>
                <div class="badge bg-white text-primary px-3 py-2 fs-6">
                    <i class="bi bi-shield-check me-2"></i> {{ ucfirst($statusPpdb ?? 'Tidak Aktif') }}
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
                            <i class="bi bi-people text-primary fs-5"></i>
                        </div>
                        <span class="badge bg-light text-muted">Total</span>
                    </div>
                    <h2 class="fw-bold mb-0">{{ $total }}</h2>
                    <p class="text-muted small mb-0 mt-2">Jumlah pendaftar</p>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100 dashboard-card">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="rounded-circle bg-warning bg-opacity-10 p-2">
                            <i class="bi bi-hourglass-split text-warning fs-5"></i>
                        </div>
                        <span class="badge bg-light text-muted">Pending</span>
                    </div>
                    <h2 class="fw-bold mb-0">{{ $pending }}</h2>
                    <p class="text-muted small mb-0 mt-2">Menunggu verifikasi</p>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100 dashboard-card">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="rounded-circle bg-success bg-opacity-10 p-2">
                            <i class="bi bi-check-circle text-success fs-5"></i>
                        </div>
                        <span class="badge bg-light text-muted">Diterima</span>
                    </div>
                    <h2 class="fw-bold mb-0">{{ $diterima }}</h2>
                    <p class="text-muted small mb-0 mt-2">Calon siswa diterima</p>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100 dashboard-card">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="rounded-circle bg-danger bg-opacity-10 p-2">
                            <i class="bi bi-x-circle text-danger fs-5"></i>
                        </div>
                        <span class="badge bg-light text-muted">Ditolak</span>
                    </div>
                    <h2 class="fw-bold mb-0">{{ $ditolak }}</h2>
                    <p class="text-muted small mb-0 mt-2">Calon siswa ditolak</p>
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
                            <p class="text-muted small mb-0">Menu utama yang sering dipakai dalam pengelolaan PPDB.</p>
                        </div>
                        <span class="badge bg-light text-dark">PPDB</span>
                    </div>

                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <a href="{{ route('tu.ppdb.pendaftar.index') }}" class="text-decoration-none">
                                <div class="border rounded-4 p-3 h-100 quick-link">
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="rounded-circle bg-primary bg-opacity-10 p-2 me-2">
                                            <i class="bi bi-people text-primary"></i>
                                        </div>
                                        <h6 class="mb-0 fw-semibold text-dark">Data Pendaftar</h6>
                                    </div>
                                    <p class="small text-muted mb-0">Lihat daftar seluruh pendaftar PPDB dan detailnya.</p>
                                </div>
                            </a>
                        </div>

                        <div class="col-12 col-md-6">
                            <a href="{{ route('tu.ppdb.verifikasi.index') }}" class="text-decoration-none">
                                <div class="border rounded-4 p-3 h-100 quick-link">
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="rounded-circle bg-warning bg-opacity-10 p-2 me-2">
                                            <i class="bi bi-search text-warning"></i>
                                        </div>
                                        <h6 class="mb-0 fw-semibold text-dark">Verifikasi</h6>
                                    </div>
                                    <p class="small text-muted mb-0">Tinjau dan lakukan verifikasi status pendaftar.</p>
                                </div>
                            </a>
                        </div>

                        <div class="col-12 col-md-6">
                            <a href="{{ route('tu.ppdb.diterima.index') }}" class="text-decoration-none">
                                <div class="border rounded-4 p-3 h-100 quick-link">
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="rounded-circle bg-success bg-opacity-10 p-2 me-2">
                                            <i class="bi bi-check2-circle text-success"></i>
                                        </div>
                                        <h6 class="mb-0 fw-semibold text-dark">Diterima</h6>
                                    </div>
                                    <p class="small text-muted mb-0">Pantau daftar calon siswa yang telah diterima.</p>
                                </div>
                            </a>
                        </div>

                        <div class="col-12 col-md-6">
                            <a href="{{ route('tu.ppdb.ditolak.index') }}" class="text-decoration-none">
                                <div class="border rounded-4 p-3 h-100 quick-link">
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="rounded-circle bg-danger bg-opacity-10 p-2 me-2">
                                            <i class="bi bi-x-circle text-danger"></i>
                                        </div>
                                        <h6 class="mb-0 fw-semibold text-dark">Ditolak</h6>
                                    </div>
                                    <p class="small text-muted mb-0">Cek daftar pendaftar yang dinyatakan tidak lolos.</p>
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
                                <span class="text-muted">Status PPDB</span>
                                <span class="badge bg-success-subtle text-success">{{ ucfirst($statusPpdb ?? 'Tidak Aktif') }}</span>
                            </div>
                        </li>
                        <li class="list-group-item px-0 py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted">Kuota</span>
                                <span class="fw-semibold">{{ $kuota }}</span>
                            </div>
                        </li>
                        <li class="list-group-item px-0 py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted">Sisa Kuota</span>
                                <span class="fw-semibold">{{ $sisaKuota }}</span>
                            </div>
                        </li>
                        <li class="list-group-item px-0 py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted">Persentase Terisi</span>
                                <span class="fw-semibold">{{ $persentase }}%</span>
                            </div>
                        </li>
                    </ul>

                    <div class="card border-0 bg-light rounded-4 mt-3">
                        <div class="card-body p-3">
                            <h6 class="fw-semibold mb-2">Informasi</h6>
                            <p class="small text-muted mb-0">Dashboard ini membantu operator memantau alur penerimaan siswa baru secara cepat, mulai dari pendaftar hingga keputusan akhir.</p>
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
                    <h5 class="mb-0">Distribusi Status Pendaftar</h5>
                    <p class="small text-muted mb-0">Visualisasi jumlah pendaftar berdasarkan status saat ini.</p>
                </div>
            </div>
        </div>
        <div class="card-body p-4">
            <div style="max-width: 720px; margin: 0 auto;">
                <canvas id="chartPpdbStatus"></canvas>
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
    const ctx = document.getElementById('chartPpdbStatus').getContext('2d');
    const chartPpdbStatus = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Pending', 'Diterima', 'Ditolak'],
            datasets: [{
                label: 'Jumlah',
                data: [{{ $pending }}, {{ $diterima }}, {{ $ditolak }}],
                backgroundColor: ['#f59e0b', '#10b981', '#ef4444'],
                borderRadius: 8,
                maxBarThickness: 48,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
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
                    grid: {
                        display: false
                    },
                    ticks: {
                        color: '#64748b'
                    }
                },
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(148, 163, 184, 0.2)'
                    },
                    ticks: {
                        color: '#64748b'
                    }
                }
            }
        }
    });
</script>
@endsection