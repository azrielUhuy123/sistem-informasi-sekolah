@extends('layouts.app')

@section('title', 'Dashboard Wali Kelas')

@section('content')

<div class="container-fluid py-3">

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
        <div class="card-body p-4 p-lg-5 text-white" style="background: linear-gradient(135deg, #0f4c81 0%, #2563eb 100%);">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                <div>
                    <h3 class="fw-bold mb-2">Dashboard Wali Kelas</h3>
                    <p class="mb-0 opacity-75">
                        {{ $kelas->nama_kelas_label }} • Tahun Ajaran {{ current_tahun()?->tahun }}
                    </p>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('guru.kelas-saya') }}" class="btn btn-light text-primary shadow-sm">
                        <i class="bi bi-file-earmark-text me-2"></i> Raport
                    </a>
                    <a href="{{ route('guru.absensi.rekap') }}" class="btn btn-outline-light shadow-sm">
                        <i class="bi bi-clipboard-data me-2"></i> Rekap
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 dashboard-card">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <small class="text-muted">Total Siswa</small>
                            <h2 class="fw-bold mb-1 mt-2">{{ $totalSiswa }}</h2>
                            <small class="text-success">Siswa aktif</small>
                        </div>
                        <div class="rounded-circle bg-primary bg-opacity-10 p-2">
                            <i class="bi bi-people-fill text-primary fs-5"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 dashboard-card">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <small class="text-muted">Laki-Laki</small>
                            <h2 class="fw-bold mb-1 mt-2">{{ $totalLaki }}</h2>
                            <small class="text-muted">Peserta didik</small>
                        </div>
                        <div class="rounded-circle bg-info bg-opacity-10 p-2">
                            <i class="bi bi-gender-male text-info fs-5"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 dashboard-card">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <small class="text-muted">Perempuan</small>
                            <h2 class="fw-bold mb-1 mt-2">{{ $totalPerempuan }}</h2>
                            <small class="text-muted">Peserta didik</small>
                        </div>
                        <div class="rounded-circle bg-danger bg-opacity-10 p-2">
                            <i class="bi bi-gender-female text-danger fs-5"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 dashboard-card">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <small class="text-muted">Kehadiran</small>
                            <h2 class="fw-bold mb-1 mt-2">{{ $persentaseHadir }}%</h2>
                            <small class="{{ $persentaseHadir >= 90 ? 'text-success' : 'text-danger' }}">Monitoring semester</small>
                        </div>
                        <div class="rounded-circle bg-success bg-opacity-10 p-2">
                            <i class="bi bi-clipboard-check text-success fs-5"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h5 class="fw-semibold mb-1">Akses Cepat</h5>
                            <p class="text-muted small mb-0">Menu utama yang sering dipakai untuk pengelolaan kelas.</p>
                        </div>
                        <span class="badge bg-light text-dark">Menu utama</span>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <a href="{{ route('guru.kelas-saya') }}" class="text-decoration-none">
                                <div class="border rounded-4 p-3 h-100 quick-link">
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="rounded-circle bg-primary bg-opacity-10 p-2 me-2">
                                            <i class="bi bi-file-earmark-text text-primary"></i>
                                        </div>
                                        <h6 class="mb-0 fw-semibold text-dark">Raport</h6>
                                    </div>
                                    <p class="small text-muted mb-0">Lihat dan kelola hasil belajar siswa dengan cepat.</p>
                                </div>
                            </a>
                        </div>

                        <div class="col-md-6">
                            <a href="{{ route('guru.absensi.rekap') }}" class="text-decoration-none">
                                <div class="border rounded-4 p-3 h-100 quick-link">
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="rounded-circle bg-success bg-opacity-10 p-2 me-2">
                                            <i class="bi bi-clipboard-data text-success"></i>
                                        </div>
                                        <h6 class="mb-0 fw-semibold text-dark">Rekap</h6>
                                    </div>
                                    <p class="small text-muted mb-0">Pantau absensi, ketercapaian, dan perkembangan siswa.</p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <h5 class="fw-semibold mb-3">Ringkasan Kelas</h5>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item px-0 py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted">Kelas</span>
                                <span class="fw-semibold">{{ $kelas->nama_kelas_label }}</span>
                            </div>
                        </li>
                        <li class="list-group-item px-0 py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted">Kehadiran</span>
                                <span class="fw-semibold">{{ $persentaseHadir }}%</span>
                            </div>
                        </li>
                        <li class="list-group-item px-0 py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted">Rata-rata nilai</span>
                                <span class="fw-semibold">{{ $rataNilai }}</span>
                            </div>
                        </li>
                    </ul>

                    <div class="card border-0 bg-light rounded-4 mt-3">
                        <div class="card-body p-3">
                            <h6 class="fw-semibold mb-2">Informasi</h6>
                            <p class="small text-muted mb-0">Dashboard ini membantu wali kelas memantau kondisi kelas, siswa yang membutuhkan perhatian, dan akses cepat ke fitur utama.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h5 class="fw-semibold mb-1">Rata-Rata Nilai Kelas</h5>
                            <p class="text-muted small mb-0">Perkiraan pencapaian akademik kelas saat ini.</p>
                        </div>
                        <span class="badge bg-warning-subtle text-warning-emphasis">Akademik</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="fw-bold mb-1">{{ $rataNilai }}</h2>
                            <small class="text-success">Akademik kelas stabil</small>
                        </div>
                        <div class="fs-1 text-warning">
                            <i class="bi bi-bar-chart-line"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h5 class="fw-semibold mb-1">Belum Ada Perkembangan</h5>
                            <p class="text-muted small mb-0">Siswa yang belum memiliki perkembangan tercatat.</p>
                        </div>
                        <span class="badge bg-danger-subtle text-danger-emphasis">Perlu tindak lanjut</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="fw-bold mb-1 text-danger">{{ $belumPerkembangan }}</h2>
                            <small class="text-danger">Perlu tindak lanjut</small>
                        </div>
                        <div class="fs-1 text-danger">
                            <i class="bi bi-exclamation-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="alert alert-warning border-0 shadow-sm mb-4 rounded-4">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <div>Monitoring kelas membantu wali kelas memantau siswa yang memerlukan perhatian khusus.</div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-header bg-white border-0 pt-4 px-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0">Distribusi Siswa</h5>
                    <p class="small text-muted mb-0">Visualisasi jumlah siswa laki-laki dan perempuan dalam kelas.</p>
                </div>
            </div>
        </div>
        <div class="card-body p-4">
            <div style="max-width: 680px; margin: 0 auto;">
                <canvas id="kelasChart"></canvas>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="fw-bold mb-0">Perlu Perhatian Akademik</h5>
                        <span class="badge bg-danger">Nilai Rendah</span>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Nama</th>
                                    <th>Nilai</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($nilaiTerendah as $n)
                                    <tr>
                                        <td>{{ $n->siswa->nama }}</td>
                                        <td><span class="badge bg-danger">{{ $n->nilai_akhir }}</span></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="text-center text-muted py-4">Belum ada data</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="fw-bold mb-0">Kehadiran Bermasalah</h5>
                        <span class="badge bg-warning text-dark">Alpha</span>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Nama</th>
                                    <th>Total Alpha</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($alphaTerbanyak as $a)
                                    <tr>
                                        <td>{{ $a['nama'] }}</td>
                                        <td><span class="badge bg-warning text-dark">{{ $a['alpha'] }}</span></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="text-center text-muted py-4">Belum ada data</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
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

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const kelasChartCtx = document.getElementById('kelasChart');

    if (kelasChartCtx) {
        const kelasChart = new Chart(kelasChartCtx.getContext('2d'), {
            type: 'bar',
            data: {
                labels: ['Laki-Laki', 'Perempuan'],
                datasets: [{
                    label: 'Jumlah Siswa',
                    data: [{{ $totalLaki }}, {{ $totalPerempuan }}],
                    backgroundColor: ['#3b82f6', '#ec4899'],
                    borderRadius: 8,
                    maxBarThickness: 36
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
                        bodyColor: '#e2e8f0'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            color: '#64748b'
                        },
                        grid: {
                            color: 'rgba(148, 163, 184, 0.2)'
                        }
                    },
                    x: {
                        ticks: {
                            color: '#334155'
                        },
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    }
</script>
@endpush

@endsection