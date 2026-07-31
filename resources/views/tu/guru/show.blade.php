@extends('layouts.app')

@section('title', 'Detail Guru')

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
                                    {{ strtoupper(substr($pegawai->pegawai?->nama, 0, 2)) }}
                                </div>

                                <div>
                                    <h4 class="mb-1 fw-bold">{{ $pegawai->pegawai?->nama }}</h4>
                                    <p class="text-muted mb-2">{{ $pegawai->pegawai?->jabatan ?? 'Pegawai' }}</p>

                                    <div class="d-flex flex-wrap gap-2 justify-content-center">
                                        <span class="badge bg-primary-subtle text-primary-emphasis border border-primary-subtle">
                                            <i class="bi bi-person-badge me-1"></i> NIP {{ $pegawai->pegawai?->nip ?? '-' }}
                                        </span>
                                        <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle">
                                            <i class="bi bi-credit-card-2-front me-1"></i> NUPTK {{ $pegawai->pegawai?->nuptk ?? '-' }}
                                        </span>
                                    </div>
                                </div>

                                <div class="w-100">
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <div class="border rounded-3 p-2 bg-white">
                                                <div class="small text-muted">Status Kepegawaian</div>
                                                <div class="fw-semibold">{{ $pegawai->pegawai?->status_kepegawaian ?? '-' }}</div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="border rounded-3 p-2 bg-white">
                                                <div class="small text-muted">Status Approval</div>
                                                <div class="fw-semibold">
                                                    @if($pegawai->pegawai?->approval_status == 'approved')
                                                        <span class="badge bg-success">Approved</span>
                                                    @elseif( $pegawai->pegawai?->approval_status == 'rejected')
                                                        <span class="badge bg-danger">Rejected</span>
                                                    @else
                                                        <span class="badge bg-warning text-dark">Pending</span>
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
                                    <h5 class="fw-bold mb-0">Profil Guru</h5>
                                    <span class="badge bg-secondary-subtle text-secondary-emphasis border border-secondary-subtle">
                                        <i class="bi bi-person-lines-fill me-1"></i> Master Data
                                    </span>
                                </div>

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="small text-muted mb-1">Tempat, Tanggal Lahir</div>
                                        <div class="fw-semibold">
                                            {{ $pegawai->pegawai?->tempat_lahir ?? '-' }}, {{ $pegawai->pegawai?->tanggal_lahir ? $pegawai->pegawai?->tanggal_lahir->format('d-m-Y') : '-' }}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="small text-muted mb-1">Agama</div>
                                        <div class="fw-semibold">{{ $pegawai->pegawai?->agama ?? '-' }}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="small text-muted mb-1">Status Kawin</div>
                                        <div class="fw-semibold">
                                            @if ($pegawai->pegawai?->status_kawin == 'K')
                                                Kawin
                                            @elseif($pegawai->pegawai?->status_kawin == 'TK')
                                                Tidak Kawin
                                            @else
                                                -
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="small text-muted mb-1">Jenis Kelamin</div>
                                        <div class="fw-semibold">
                                            @if ($pegawai->pegawai?->jenis_kelamin == 'L')
                                                <span class="badge bg-primary">Laki-laki</span>
                                            @elseif($pegawai->pegawai?->jenis_kelamin == 'P')
                                                <span class="badge bg-danger">Perempuan</span>
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
                                        <div class="fw-semibold">{{ $pegawai->pegawai?->ijazah_terakhir ?? '-' }}</div>
                                    </div>
                                    <div class="col-12">
                                        <div class="small text-muted mb-1">Pangkat Terakhir</div>
                                        <div class="fw-semibold">{{ $pegawai->pegawai?->sk_terakhir_pangkat ?? '-' }}</div>
                                    </div>
                                    <div class="col-12">
                                        <div class="small text-muted mb-1">Pangkat Berkala</div>
                                        <div class="fw-semibold">{{ $pegawai->pegawai?->sk_berkala_pangkat ?? '-' }}</div>
                                    </div>
                                    @if(auth()->user() && in_array(auth()->user()->role, ['superadmin']))
                                        <div class="col-12">
                                            <div class="small text-muted mb-1">Gaji Pokok</div>
                                            <div class="fw-semibold">{{ $pegawai->pegawai?->gaji_pokok ? 'Rp ' . number_format($pegawai->pegawai?->gaji_pokok, 0, ',', '.') : '-' }}</div>
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
                                        <div class="fw-semibold">{{ $pegawai->pegawai?->jabatan ?? '-' }}</div>
                                    </div>
                                    <div class="col-12">
                                        <div class="small text-muted mb-1">Mulai Kerja</div>
                                        <div class="fw-semibold">{{ $pegawai->pegawai?->mulai_kerja ? $pegawai->pegawai?->mulai_kerja->format('d-m-Y') : '-' }}</div>
                                    </div>
                                    <div class="col-12">
                                        <div class="small text-muted mb-1">Masa Kerja Golongan</div>
                                        <div class="fw-semibold">{{ $pegawai->pegawai?->masa_kerja_gol_th ?? 0 }} Tahun {{ $pegawai->pegawai?->masa_kerja_gol_bln ?? 0 }} Bulan</div>
                                    </div>
                                    <div class="col-12">
                                        <div class="small text-muted mb-1">Masa Kerja Keseluruhan</div>
                                        <div class="fw-semibold">{{ $pegawai->pegawai?->masa_kerja_total_th ?? 0 }} Tahun {{ $pegawai->pegawai?->masa_kerja_total_bln ?? 0 }} Bulan</div>
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
                                            Pangkat: {{ $pegawai->pegawai?->sk_terakhir_pangkat ?? '-' }}<br>
                                            Nomor SK: {{ $pegawai->pegawai?->sk_terakhir_no ?? '-' }}<br>
                                            TMT: {{ $pegawai->pegawai?->sk_terakhir_tmt ? $pegawai->pegawai?->sk_terakhir_tmt->format('d-m-Y') : '-' }}
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="small text-muted mb-1">SK Berkala</div>
                                        <div class="fw-semibold">
                                            Pangkat: {{ $pegawai->pegawai?->sk_berkala_pangkat ?? '-' }}<br>
                                            Nomor SK: {{ $pegawai->pegawai?->sk_berkala_no ?? '-' }}<br>
                                            TMT: {{ $pegawai->pegawai?->sk_berkala_tmt ? $pegawai->pegawai?->sk_berkala_tmt->format('d-m-Y') : '-' }}
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="small text-muted mb-1">Sertifikasi</div>
                                        <div class="fw-semibold">
                                            {{ $pegawai->pegawai?->nomor_sertifikasi ?? '-' }}
                                            @if ($pegawai->pegawai?->tahun_sertifikasi)
                                                <span class="text-muted">(Th. {{ $pegawai->pegawai?->tahun_sertifikasi }})</span>
                                            @endif
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
                 @if(request()->is('superadmin/*'))
                    <a href="{{ route('superadmin.datakepegawaian.approval') }}" class="btn btn-outline-secondary ml-4">
                        <i class="bi bi-arrow-left me-1"></i> Kembali
                    </a>
                @else
                    <a href="{{ route('tu.guru.index') }}" class="btn btn-outline-secondary ml-4">
                        <i class="bi bi-arrow-left me-1"></i> Kembali
                    </a>
                @endif
            </div>
        </div>

    </div>

@endsection
