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

                                    <p class="text-muted mb-2">Guru</p>                                                                   

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
                                                <div class="small text-muted">Status Persetujuan</div>
                                                <div class="fw-semibold text-dark">
                                                    @if($pegawai->pegawai?->approval_status == 'approved')
                                                        <span class="small"><i class="bi bi-patch-check-fill text-success me-1"></i>Approved</span>
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
                        <div class="card border-0 h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="fw-bold mb-0">Profil Guru</h5>
                                    <x-table.role-badge :role="$pegawai->pegawai?->jabatan ?? 'Pegawai'" /> 
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
                                                <span class="badge bg-indigo rounded-pill px-2 py-1">
                                                    <i class="bi bi-gender-male me-1"></i>
                                                    Laki-laki
                                                </span>
                                            @elseif($pegawai->pegawai?->jenis_kelamin == 'P')
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
                        <div class="card border-0">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="fw-bold mb-0">Akademik</h5>                                    
                                </div>

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="small text-muted mb-1">Mata Pelajaran</div>
                                        <div class="fw-semibold">
                                            @if($pegawai->waliKelas)
                                                @php
                                                    $mapelInti = [
                                                        'Bahasa Indonesia,',
                                                        'Matematika,',
                                                        'Pendidikan Pancasila,',
                                                        'Ilmu Pengetahuan Alam dan Sosial,',
                                                    ];
                                                @endphp
                                                @foreach($mapelInti as $mapel)
                                                    <span class="small">
                                                        {{ $mapel }}
                                                    </span>
                                                @endforeach
                                            @else
                                                @php
                                                    $mapels = $pegawai->pengampuMapel
                                                        ->filter(fn ($pengampu) => $pengampu->mapel)
                                                        ->unique('mapel_id');
                                                @endphp
                                                @forelse($mapels as $pengampu)
                                                    <span
                                                        class="small"
                                                        title="{{ $pengampu->mapel?->nama_mapel ?? '-' }}"
                                                    >
                                                        {{ Str::limit($pengampu->mapel?->nama_mapel ?? '-') }}
                                                    </span>
                                                @empty
                                                    <span class="text-muted">
                                                        Belum ada mapel
                                                    </span>
                                                @endforelse
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="small text-muted mb-1">Total kelas</div>
                                        <div class="fw-semibold">
                                            @php
                                                $kelasWali = $pegawai->waliKelas?->kelas;

                                                $kelasMengajar = $pegawai->pengampuMapel
                                                    ->pluck('kelas')
                                                    ->filter()
                                                    ->unique('id');

                                                $semuaKelas = collect();

                                                if ($kelasWali) {
                                                    $semuaKelas->push($kelasWali);
                                                }

                                                foreach ($kelasMengajar as $kelas) {
                                                    if (!$kelasWali || $kelas->id != $kelasWali->id) {
                                                        $semuaKelas->push($kelas);
                                                    }
                                                }

                                                $jumlahKelas = $semuaKelas->count();
                                            @endphp

                                            @if($jumlahKelas == 0)

                                                <span class="badge-status selesai">
                                                    0 kelas
                                                </span>

                                            @elseif($jumlahKelas >= 15)

                                                <span class="badge-status nonaktif">
                                                    {{ $jumlahKelas }} Kelas
                                                </span>

                                            @elseif($jumlahKelas >= 8)

                                                <span class="badge-status pending">
                                                    {{ $jumlahKelas }} Kelas
                                                </span>

                                            @else

                                                <span class="badge-status aktif">
                                                    {{ $jumlahKelas }} Kelas
                                                </span>

                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="small text-muted mb-1">Mengajar Di Kelas</div>
                                        <div class="fw-semibold">
                                            @php
                                                $kelasWali = $pegawai->waliKelas?->kelas;
                                                $kelasMengajar = $pegawai->pengampuMapel
                                                    ->pluck('kelas')
                                                    ->filter()
                                                    ->unique('id');
                                            @endphp
                                            {{-- Kelas Wali --}}
                                            @if($kelasWali)
                                                <span class="badge bg-purple rounded-3">
                                                    {{ $kelasWali->nama_kelas_label ?? '-' }}
                                                </span>
                                            @endif
                                            {{-- Kelas Mengajar --}}    
                                            @foreach($kelasMengajar as $kelas)
                                                @if(!$kelasWali || $kelas->id != $kelasWali->id)
                                                    <span class="badge bg-purple rounded-3">
                                                        {{ $kelas->nama_kelas_label ?? '-' }}
                                                    </span>
                                                @endif
                                            @endforeach
                                            @if($kelasMengajar->isEmpty() && !$kelasWali)
                                                <span class="text-muted">-</span>
                                            @endif                                                                                                                                                                                            
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="small text-muted mb-1">Status Akademik</div>
                                        <div class="fw-semibold">
                                            @if($pegawai->waliKelas)
                                                <span class="text-gold">
                                                    Wali Kelas
                                                </span>
                                            @else
                                                <span class="text-teal">
                                                    Guru Bidang
                                                </span>
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
                <a href="{{ route('tu.guru.index') }}" class="btn btn-outline-secondary rounded-3 ml-4">
                    <i class="bi bi-chevron-left me-1"></i> Kembali
                </a>                
            </div>
        </div>

    </div>

@endsection
