@extends('layouts.app')

@section('title', 'Detail Pegawai')

@section('content')

    <div class="container-fluid">

        <div class="card shadow-sm border-0 overflow-hidden">
            <div class="card-body p-4 p-lg-5">

                <div class="row g-4 align-items-start">

                    <div class="col-lg-4">
                        <div class="bg-primary bg-opacity-10 rounded-4 p-4 border h-100">
                            <div class="d-flex flex-column align-items-center text-center gap-3">
                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold"
                                     style="width:110px;height:110px;font-size:30px;">
                                    {{ strtoupper(substr($pegawai->nama, 0, 2)) }}
                                </div>

                                <div>
                                    <h4 class="mb-1 fw-bold">{{ $pegawai->nama }}</h4>
                                    <p class="text-muted mb-2">{{ $pegawai->jabatan ?? 'Pegawai' }}</p>

                                    <div class="d-flex flex-wrap gap-2 justify-content-center">
                                        <span class="badge bg-primary-subtle text-primary-emphasis border border-primary-subtle">
                                            <i class="bi bi-person-badge me-1"></i> NIP {{ $pegawai->nip ?? '-' }}
                                        </span>
                                        <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle">
                                            <i class="bi bi-credit-card-2-front me-1"></i> NUPTK {{ $pegawai->nuptk ?? '-' }}
                                        </span>
                                    </div>
                                </div>

                                <div class="w-100">
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <div class="border rounded-3 p-2 bg-white">
                                                <div class="small text-muted">Status Kepegawaian</div>
                                                <div class="fw-semibold">{{ $pegawai->status_kepegawaian ?? '-' }}</div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="border rounded-3 p-2 bg-white">
                                                <div class="small text-muted">Status Approval</div>
                                                <div class="fw-semibold">
                                                    @if($pegawai->approval_status == 'approved')
                                                        <span class="small"><i class="bi bi-patch-check-fill text-success me-1"></i>Approved</span>
                                                    @elseif($pegawai->approval_status == 'rejected')
                                                        <span class="small"><i class="bi bi-patch-exclamation-fill text-danger me-1"></i>Rejected</span>
                                                    @else
                                                        <span class="small"><i class="bi bi-patch-question-fill text-warning me-1"></i>Pending</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-8">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="fw-bold mb-0">Profil Pegawai</h5>
                                    <x-table.role-badge :role="$pegawai->jabatan ?? 'Pegawai'" /> 
                                </div>

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="small text-muted mb-1">Tempat, Tanggal Lahir</div>
                                        <div class="fw-semibold">
                                            {{ $pegawai->tempat_lahir ?? '-' }}, {{ $pegawai->tanggal_lahir ? $pegawai->tanggal_lahir->format('d-m-Y') : '-' }}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="small text-muted mb-1">Agama</div>
                                        <div class="fw-semibold">{{ $pegawai->agama ?? '-' }}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="small text-muted mb-1">Status Kawin</div>
                                        <div class="fw-semibold">
                                            @if ($pegawai->status_kawin == 'K')
                                                Kawin
                                            @elseif($pegawai->status_kawin == 'TK')
                                                Tidak Kawin
                                            @else
                                                -
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="small text-muted mb-1">Jenis Kelamin</div>
                                        <div class="fw-semibold">
                                            @if ($pegawai->jenis_kelamin == 'L')
                                                <span class="badge bg-indigo rounded-pill px-2 py-1">
                                                    <i class="bi bi-gender-male me-1"></i>
                                                    Laki-laki
                                                </span>
                                            @elseif($pegawai->jenis_kelamin == 'P')
                                                <span class="badge bg-rose rounded-pill px-2 py-1">
                                                    <i class="bi bi-gender-female me-1"></i>
                                                    Perempuan
                                                </span>
                                            @else
                                                -
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <hr class="my-4">

                <div class="row g-4">

                    <div class="col-lg-6">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="rounded-circle bg-primary bg-opacity-10 p-2 me-2">
                                        <i class="bi bi-file-earmark-text fs-5 text-primary"></i>
                                    </div>
                                    <h5 class="fw-bold mb-0">Informasi Utama</h5>
                                </div>

                                <div class="row g-3">
                                    <div class="col-12">
                                        <div class="small text-muted mb-1">Ijazah Terakhir</div>
                                        <div class="fw-semibold">{{ $pegawai->ijazah_terakhir ?? '-' }}</div>
                                    </div>
                                    <div class="col-12">
                                        <div class="small text-muted mb-1">Pangkat Terakhir</div>
                                        <div class="fw-semibold">{{ $pegawai->sk_terakhir_pangkat ?? '-' }}</div>
                                    </div>
                                    <div class="col-12">
                                        <div class="small text-muted mb-1">Pangkat Berkala</div>
                                        <div class="fw-semibold">{{ $pegawai->sk_berkala_pangkat ?? '-' }}</div>
                                    </div>
                                    @if(auth()->user() && in_array(auth()->user()->role, ['tu','superadmin']))
                                        <div class="col-12">
                                            <div class="small text-muted mb-1">Gaji Pokok</div>
                                            <div class="fw-semibold">{{ $pegawai->gaji_pokok ? 'Rp ' . number_format($pegawai->gaji_pokok, 0, ',', '.') : '-' }}</div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="rounded-circle bg-info bg-opacity-10 p-2 me-2">
                                        <i class="bi bi-briefcase fs-5 text-info"></i>
                                    </div>
                                    <h5 class="fw-bold mb-0">Riwayat Kepegawaian</h5>
                                </div>

                                <div class="row g-3">
                                    <div class="col-12">
                                        <div class="small text-muted mb-1">Jabatan</div>
                                        <div class="fw-semibold">{{ $pegawai->jabatan ?? '-' }}</div>
                                    </div>
                                    <div class="col-12">
                                        <div class="small text-muted mb-1">Mulai Kerja</div>
                                        <div class="fw-semibold">{{ $pegawai->mulai_kerja ? $pegawai->mulai_kerja->format('d-m-Y') : '-' }}</div>
                                    </div>
                                    <div class="col-12">
                                        <div class="small text-muted mb-1">Masa Kerja Golongan</div>
                                        <div class="fw-semibold">{{ $pegawai->masa_kerja_gol_th ?? 0 }} Tahun {{ $pegawai->masa_kerja_gol_bln ?? 0 }} Bulan</div>
                                    </div>
                                    <div class="col-12">
                                        <div class="small text-muted mb-1">Masa Kerja Keseluruhan</div>
                                        <div class="fw-semibold">{{ $pegawai->masa_kerja_total_th ?? 0 }} Tahun {{ $pegawai->masa_kerja_total_bln ?? 0 }} Bulan</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="rounded-circle bg-secondary bg-opacity-10 p-2 me-2">
                                        <i class="bi bi-award fs-5 text-secondary"></i>
                                    </div>
                                    <h5 class="fw-bold mb-0">SK & Sertifikasi</h5>
                                </div>

                                <div class="row g-3">
                                    <div class="col-12">
                                        <div class="small text-muted mb-1">SK Terakhir</div>
                                        <div class="fw-semibold">
                                            Pangkat: {{ $pegawai->sk_terakhir_pangkat ?? '-' }}<br>
                                            Nomor SK: {{ $pegawai->sk_terakhir_no ?? '-' }}<br>
                                            TMT: {{ $pegawai->sk_terakhir_tmt ? $pegawai->sk_terakhir_tmt->format('d-m-Y') : '-' }}
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="small text-muted mb-1">SK Berkala</div>
                                        <div class="fw-semibold">
                                            Pangkat: {{ $pegawai->sk_berkala_pangkat ?? '-' }}<br>
                                            Nomor SK: {{ $pegawai->sk_berkala_no ?? '-' }}<br>
                                            TMT: {{ $pegawai->sk_berkala_tmt ? $pegawai->sk_berkala_tmt->format('d-m-Y') : '-' }}
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="small text-muted mb-1">Sertifikasi</div>
                                        <div class="fw-semibold">
                                            {{ $pegawai->nomor_sertifikasi ?? '-' }}
                                            @if ($pegawai->tahun_sertifikasi)
                                                <span class="text-muted">(Th. {{ $pegawai->tahun_sertifikasi }})</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="rounded-circle bg-warning bg-opacity-10 p-2 me-2">
                                        <i class="bi bi-check2-square fs-5 text-warning"></i>
                                    </div>
                                    <h5 class="fw-bold mb-0">Approval</h5>
                                </div>

                                <div class="row g-3">
                                    <div class="col-12">
                                        <div class="small text-muted mb-1">Status Approval</div>
                                        <div class="fw-semibold">
                                            @if($pegawai->approval_status == 'approved')
                                                <span class="small"><i class="bi bi-patch-check-fill text-success me-1"></i>Approved</span>
                                            @elseif($pegawai->approval_status == 'rejected')
                                                <span class="small"><i class="bi bi-patch-exclamation-fill text-danger me-1"></i>Rejected</span>
                                            @else
                                                <span class="small"><i class="bi bi-patch-question-fill text-warning me-1"></i>Pending</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="small text-muted mb-1">Disetujui Oleh</div>
                                        <div class="fw-semibold">{{ $pegawai->approved_by ?? '-' }}</div>
                                    </div>
                                    <div class="col-12">
                                        <div class="small text-muted mb-1">Tanggal Approval</div>
                                        <div class="fw-semibold">{{ $pegawai->approved_at ? $pegawai->approved_at->format('d-m-Y H:i') : '-' }}</div>
                                    </div>
                                    <div class="col-12">
                                        <div class="small text-muted mb-1">Alasan Penolakan</div>
                                        <div class="fw-semibold">{{ $pegawai->rejected_reason ?? '-' }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="rounded-circle bg-success bg-opacity-10 p-2 me-2">
                                        <i class="bi bi-person-gear fs-5 text-success"></i>
                                    </div>
                                    <h5 class="fw-bold mb-0">Akun Sistem</h5>
                                </div>

                                @if($pegawai->user)
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <div class="small text-muted mb-1">Username</div>
                                            <div class="fw-semibold">{{ $pegawai->user->username ?? $pegawai->user->email ?? '-' }}</div>
                                        </div>
                                        <div class="col-12">
                                            <div class="small text-muted mb-1">Role</div>
                                            <div class="fw-semibold">{{ ucfirst($pegawai->user->role ?? '-') }}</div>
                                        </div>
                                        <div class="col-12">
                                            <div class="small text-muted mb-1">Status User</div>
                                            <div class="fw-semibold">
                                                @if($pegawai->approval_status == 'approved')
                                                    <span class="small"><i class="bi bi-patch-check-fill text-success me-1"></i>Approved</span>
                                                @elseif($pegawai->approval_status == 'rejected')
                                                    <span class="small"><i class="bi bi-patch-exclamation-fill text-danger me-1"></i>Rejected</span>
                                                @else
                                                    <span class="small"><i class="bi bi-patch-question-fill text-warning me-1"></i>Pending</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="small text-muted mb-1">Tanggal Akun Dibuat</div>
                                            <div class="fw-semibold">{{ $pegawai->user->created_at ? $pegawai->user->created_at->format('d-m-Y H:i') : '-' }}</div>
                                        </div>
                                    </div>
                                @else
                                    <div class="alert alert-light border mb-0">
                                        <i class="bi bi-exclamation-circle me-2 text-warning"></i>
                                        <span class="fw-semibold">Belum Memiliki Akun</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="rounded-circle bg-secondary bg-opacity-10 p-2 me-2">
                                        <i class="bi bi-clock-history fs-5 text-secondary"></i>
                                    </div>
                                    <h5 class="fw-bold mb-0">Riwayat Aktivitas</h5>
                                </div>

                                <div class="d-flex flex-column gap-3">
                                    <div class="d-flex align-items-start gap-2">
                                        <i class="bi bi-check-circle-fill text-success mt-1"></i>
                                        <div>
                                            <div class="fw-semibold">Data Pegawai Dibuat</div>
                                            <div class="small text-muted">{{ $pegawai->created_at ? $pegawai->created_at->format('d-m-Y H:i') : '-' }}</div>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-start gap-2">
                                        <i class="bi bi-check-circle-fill text-success mt-1"></i>
                                        <div>
                                            <div class="fw-semibold">Menunggu Approval</div>
                                            <div class="small text-muted">Status saat ini: {{ $pegawai->approval_status ?? 'Pending' }}</div>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-start gap-2">
                                        <i class="bi bi-check-circle-fill text-success mt-1"></i>
                                        <div>
                                            <div class="fw-semibold">Akun Dibuat</div>
                                            <div class="small text-muted">{{ $pegawai->user ? 'Sudah tersedia' : 'Belum tersedia' }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
        <div class="sticky-bottom bg-white border border-light-subtle py-3 mt-4">
            <div class="d-flex justify-content-between align-items-center">                
                <a href="{{ route('tu.datakepegawaian.index') }}" class="btn btn-outline-secondary rounded-3 ml-4">
                    <i class="bi bi-chevron-left me-1"></i> Kembali
                </a>              
            </div>
        </div>

    </div>

@endsection
