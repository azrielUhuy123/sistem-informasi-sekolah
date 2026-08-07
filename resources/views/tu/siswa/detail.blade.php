@extends('layouts.app')

@section('title', 'Detail Siswa')

@section('content')

    <div class="container-fluid">

        <div class="card shadow-sm border-0 overflow-hidden">
            <div class="card-body p-4 p-lg-5">

                <div class="row g-4 align-items-start">

                    <div class="col-lg-4">
                        <div class="bg-primary bg-opacity-10 rounded-4 p-4 border h-100">
                            <div class="d-flex flex-column align-items-center text-center gap-3">
                                <img
                                    src="{{ $siswa->foto
                                        ? asset('storage/foto_siswa/'.$siswa->foto)
                                        : 'https://ui-avatars.com/api/?name='.urlencode($siswa->nama).'&background=E9ECEF&color=495057&size=120' }}"
                                    class="rounded-circle"
                                    width="110"
                                    height="110"
                                    style="object-fit:cover;"
                                >

                                <div>
                                    <h4 class="mb-1 fw-bold text-uppercase">{{ $siswa->nama }}</h4>
                                    <p class="text-muted mb-2">Siswa</p>

                                    <div class="d-flex flex-wrap gap-2 justify-content-center">
                                        <span class="badge bg-primary-subtle text-primary-emphasis border border-primary-subtle">
                                            <i class="bi bi-person-badge me-1"></i> NIS {{ $siswa->nis }}
                                        </span>
                                        <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle">
                                            <i class="bi bi-credit-card-2-front me-1"></i> NISN {{ $siswa->nisn ?? '-' }}
                                        </span>
                                    </div>
                                </div>

                                <div class="w-100">
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <div class="border rounded-3 p-2 bg-white">
                                                <div class="small text-muted">Status Siswa</div>
                                                <div class="fw-semibold">
                                                    @if($siswa->penempatanAktif)
                                                        Aktif
                                                    @else
                                                        Tidak Aktif
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="border rounded-3 p-2 bg-white">
                                                <div class="small text-muted">Rombel</div>
                                                <div class="fw-semibold">
                                                    <span class="badge bg-success">{{ $siswa->penempatanAktif?->kelas?->nama_kelas_label ?? '-' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-8">
                        <div class="card border-0 h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="fw-bold mb-0">Profil Siswa</h5>
                                    <span class="badge bg-secondary-subtle text-secondary-emphasis border border-secondary-subtle">
                                        <i class="bi bi-person-lines-fill me-1"></i> Master Data
                                    </span>
                                </div>

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="small text-muted mb-1">Tempat, Tanggal Lahir</div>
                                        <div class="fw-semibold">
                                            {{ $siswa->ppdb?->tempat_lahir ?? '-' }},
                                            {{ $siswa->ppdb?->tanggal_lahir
                                            ? \Carbon\Carbon::parse($siswa->ppdb?->tanggal_lahir)->format('d-m-Y')
                                            : '-' }}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="small text-muted mb-1">Agama</div>
                                        <div class="fw-semibold">{{ $siswa->ppdb?->agama }}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="small text-muted mb-1">Alamat</div>
                                        <div class="fw-semibold">{{ $siswa->alamat }}</div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="small text-muted mb-1">Jenis Kelamin</div>
                                        <div class="fw-semibold">
                                            @if($siswa->jenis_kelamin == 'L')
                                                <span class="badge bg-primary">Laki-laki</span>
                                            @else
                                                <span class="badge bg-danger">Perempuan</span>
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
                        <div class="card border h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="rounded-circle bg-primary bg-opacity-10 p-2 me-2">
                                        <i class="bi bi-file-earmark-text fs-5 text-primary"></i>
                                    </div>
                                    <h5 class="fw-bold mb-0">Informasi Akademik</h5>
                                </div>

                                <div class="row g-3">
                                    <div class="col-12">
                                        <div class="small text-muted mb-1">Nomor Pendaftaran</div>
                                        <div class="fw-semibold">{{ $siswa->ppdb?->no_pendaftaran }}</div>
                                    </div>
                                    <div class="col-12">
                                        <div class="small text-muted mb-1">Tahun Ajaran</div>
                                        <div class="fw-semibold">{{ $siswa->ppdb?->tahunAjaran?->tahun }}</div>
                                    </div>
                                    <div class="col-12">
                                        <div class="small text-muted mb-1">Tanggal Pendaftaran</div>
                                        <div class="fw-semibold">{{ $siswa->ppdb?->tanggal_pendaftaran }}</div>
                                    </div>
                                    <div class="col-12">
                                        <div class="small text-muted mb-1">Status Daftar Ulang</div>
                                        <div class="fw-semibold">{{ ucfirst($siswa->ppdb?->status_daftar_ulang ?? '-') }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="card border h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="rounded-circle bg-info bg-opacity-10 p-2 me-2">
                                        <i class="bi bi-briefcase fs-5 text-info"></i>
                                    </div>
                                    <h5 class="fw-bold mb-0">Data Orang Tua</h5>
                                </div>

                                <div class="row g-3">
                                    <div class="col-12">
                                        <div class="small text-muted mb-1">Nama Wali</div>
                                        <div class="fw-semibold">{{ $siswa->nama_wali }}</div>
                                    </div>
                                    <div class="col-12">
                                        <div class="small text-muted mb-1">Telepon Wali</div>
                                        <div class="fw-semibold">{{ $siswa->telepon_wali }}</div>
                                    </div>
                                    <div class="col-12">
                                        <div class="small text-muted mb-1">Telepon Ayah</div>
                                        <div class="fw-semibold">{{ $siswa->ppdb?->hp_ayah }}</div>
                                    </div>
                                    <div class="col-12">
                                        <div class="small text-muted mb-1">Telepon Ibu</div>
                                        <div class="fw-semibold">{{ $siswa->ppdb?->hp_ibu }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="card border h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="rounded-circle bg-secondary bg-opacity-10 p-2 me-2">
                                        <i class="bi bi-award fs-5 text-secondary"></i>
                                    </div>
                                    <h5 class="fw-bold mb-0">Data Kependudukan</h5>
                                </div>

                                <div class="row g-3">
                                    <div class="col-12">
                                        <div class="small text-muted mb-1">NIK</div>
                                        <div class="fw-semibold">{{ $siswa->ppdb?->nik }}</div>
                                    </div>
                                    <div class="col-12">
                                        <div class="small text-muted mb-1">Nomor KK</div>
                                        <div class="fw-semibold">{{ $siswa->ppdb?->nomor_kk }}</div>
                                    </div>
                                    <div class="col-12">
                                        <div class="small text-muted mb-1">Nomor Akta</div>
                                        <div class="fw-semibold">{{ $siswa->ppdb?->nomor_akta }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="card border h-100">
                            <div class="card-body">

                                <div class="d-flex align-items-center mb-3">
                                    <div class="rounded-circle bg-success bg-opacity-10 p-2 me-2">
                                        <i class="bi bi-clock-history fs-5 text-success"></i>
                                    </div>

                                    <h5 class="fw-bold mb-0">
                                        Riwayat Kelas
                                    </h5>
                                </div>

                                <div class="table-responsive">

                                    <table class="table table-hover align-middle mb-0">

                                        <thead class="table-light">
                                            <tr>
                                                <th>Tahun Ajaran</th>
                                                <th>Kelas</th>
                                                <th>Tanggal Masuk</th>
                                                <th>Tanggal Keluar</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>

                                        <tbody>

                                            @forelse($siswa->penempatan as $item)

                                                <tr>

                                                    <td>
                                                        {{ $item->tahunAjaran?->tahun ?? '-' }}
                                                        {{ $item->tahunAjaran?->semester ? '- '.$item->tahunAjaran->semester : '' }}
                                                    </td>

                                                    <td>
                                                        <span class="badge bg-secondary">
                                                            {{ $item->kelas?->nama_kelas_label ?? '-' }}
                                                        </span>
                                                    </td>

                                                    <td>
                                                        {{ \Carbon\Carbon::parse($item->tanggal_masuk)->format('d M Y') }}
                                                    </td>

                                                    <td>
                                                        {{ $item->tanggal_keluar
                                                            ? \Carbon\Carbon::parse($item->tanggal_keluar)->format('d M Y')
                                                            : '-' }}
                                                    </td>

                                                    <td>

                                                        @if($item->tanggal_keluar === null)

                                                            <span class="badge bg-success">
                                                                Aktif
                                                            </span>

                                                        @else

                                                            <span class="badge bg-secondary">
                                                                Selesai
                                                            </span>

                                                        @endif

                                                    </td>

                                                </tr>

                                            @empty

                                                <tr>
                                                    <td colspan="5" class="text-center text-muted py-4">
                                                        Belum ada riwayat kelas.
                                                    </td>
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

        </div>
        <div class="sticky-bottom bg-white border border-light-subtle py-3 mt-4">
            <div class="d-flex justify-content-between align-items-center">
                <a href="{{ route('tu.siswa.index') }}" class="btn btn-outline-secondary ml-4">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>

    </div>
@endsection
