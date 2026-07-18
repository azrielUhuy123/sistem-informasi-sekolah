@extends('layouts.app')

@section('title', 'Detail Pegawai')

@section('content')

    <div class="container-fluid py-3">

    {{-- ================= HERO ================= --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">

        <div
            class="card-body p-4 p-lg-5 text-white"
            style="background:linear-gradient(135deg,#0f4c81 0%,#2563eb 100%);"
        >

            <div class="d-flex justify-content-between align-items-center flex-wrap">

                <div>

                    <h2 class="fw-bold mb-2">

                        Detail Data Pegawai

                    </h2>

                    <p class="mb-0 opacity-75">

                        Informasi lengkap biodata, riwayat kepegawaian,
                        masa kerja, serta data administrasi pegawai.

                    </p>

                </div>

                <div class="mt-4 mt-lg-0">

                    <a
                        href="{{ route('superadmin.datakepegawaian.approval') }}"
                        class="btn btn-light rounded-pill px-4 shadow-sm"
                    >

                        <i class="bi bi-arrow-left me-2"></i>

                        Kembali

                    </a>

                </div>

            </div>

        </div>

    </div>

    {{-- ================= PROFILE ================= --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">

        <div class="card-body p-4">

            <div class="row align-items-center">

                <div class="col-lg-4">

                    <div class="d-flex align-items-center">

                        <div class="avatar-profile me-4">

                            {{ strtoupper(substr($pegawai->nama,0,2)) }}

                        </div>

                        <div>

                            <h3 class="fw-bold mb-1">

                                {{ $pegawai->nama }}

                            </h3>

                            <div class="text-muted mb-2">

                                Data Pegawai

                            </div>

                            <span class="badge bg-primary rounded-pill px-3 py-2">

                                {{ $pegawai->status_kepegawaian ?? '-' }}

                            </span>

                        </div>

                    </div>

                </div>

                <div class="col-lg-8 mt-4 mt-lg-0">

                    <div class="row g-3">

                        <div class="col-md-6">

                            <div class="info-card">

                                <small class="text-muted d-block">

                                    NIP

                                </small>

                                <div class="fw-bold">

                                    {{ $pegawai->nip ?? '-' }}

                                </div>

                            </div>

                        </div>

                        <div class="col-md-6">

                            <div class="info-card">

                                <small class="text-muted d-block">

                                    NUPTK

                                </small>

                                <div class="fw-bold">

                                    {{ $pegawai->nuptk ?? '-' }}

                                </div>

                            </div>

                        </div>

                        <div class="col-md-6">

                            <div class="info-card">

                                <small class="text-muted d-block">

                                    Tempat, Tanggal Lahir

                                </small>

                                <div class="fw-semibold">

                                    {{ $pegawai->tempat_lahir ?? '-' }},
                                    {{ $pegawai->tanggal_lahir ? $pegawai->tanggal_lahir->format('d-m-Y') : '-' }}

                                </div>

                            </div>

                        </div>

                        <div class="col-md-6">

                            <div class="info-card">

                                <small class="text-muted d-block">

                                    Agama

                                </small>

                                <div class="fw-semibold">

                                    {{ $pegawai->agama ?? '-' }}

                                </div>

                            </div>

                        </div>

                        <div class="col-md-6">

                            <div class="info-card">

                                <small class="text-muted d-block">

                                    Status Perkawinan

                                </small>

                                <div class="fw-semibold">

                                    @if($pegawai->status_kawin=='K')

                                        Kawin

                                    @elseif($pegawai->status_kawin=='TK')

                                        Tidak Kawin

                                    @else

                                        -

                                    @endif

                                </div>

                            </div>

                        </div>

                        <div class="col-md-6">

                            <div class="info-card">

                                <small class="text-muted d-block">

                                    Jenis Kelamin

                                </small>

                                <div>

                                    @if($pegawai->jenis_kelamin=='L')

                                        <span class="badge bg-primary rounded-pill px-3 py-2">

                                            Laki-laki

                                        </span>

                                    @elseif($pegawai->jenis_kelamin=='P')

                                        <span class="badge bg-danger rounded-pill px-3 py-2">

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

    </div>

    {{-- ================= DETAIL ================= --}}
    <div class="card border-0 shadow-sm rounded-4">

        <div class="card-header bg-white border-0 py-3">

            <h5 class="fw-bold mb-0">

                Informasi Kepegawaian

            </h5>

        </div>

        <div class="card-body">

    <div class="row g-4">

    {{-- ================= KOLOM KIRI ================= --}}
    <div class="col-lg-6">

        <div class="detail-card h-100">

            <h5 class="section-title">

                <i class="bi bi-person-vcard-fill me-2"></i>

                Informasi Utama

            </h5>

            <table class="table table-borderless table-detail">

                <tr>
                    <th>Ijazah Terakhir</th>
                    <td>{{ $pegawai->ijazah_terakhir ?? '-' }}</td>
                </tr>

                <tr>
                    <th>SK Terakhir</th>
                    <td>

                        Pangkat/Gol :
                        {{ $pegawai->sk_terakhir_pangkat ?? '-' }}

                        <br>

                        No :
                        {{ $pegawai->sk_terakhir_no ?? '-' }}

                        <br>

                        TMT :
                        {{ $pegawai->sk_terakhir_tmt ? $pegawai->sk_terakhir_tmt->format('d-m-Y') : '-' }}

                    </td>
                </tr>

                <tr>
                    <th>SK Berkala</th>
                    <td>

                        Pangkat/Gol :
                        {{ $pegawai->sk_berkala_pangkat ?? '-' }}

                        <br>

                        No :
                        {{ $pegawai->sk_berkala_no ?? '-' }}

                        <br>

                        TMT :
                        {{ $pegawai->sk_berkala_tmt ? $pegawai->sk_berkala_tmt->format('d-m-Y') : '-' }}

                    </td>
                </tr>

                <tr>

                    <th>Gaji Pokok</th>

                    <td>

                        {{ $pegawai->gaji_pokok
                            ? 'Rp '.number_format($pegawai->gaji_pokok,0,',','.')
                            : '-' }}

                    </td>

                </tr>

            </table>

        </div>

    </div>

    {{-- ================= KOLOM KANAN ================= --}}
        <div class="col-lg-6">

            <div class="detail-card h-100">

                <h5 class="section-title">

                    <i class="bi bi-briefcase-fill me-2"></i>

                    Informasi Kepegawaian

                </h5>

                <table class="table table-borderless table-detail">

                    <tr>

                        <th>Mulai Bekerja Di Sekolah Ini</th>

                        <td>

                            {{ $pegawai->mulai_kerja
                                ? $pegawai->mulai_kerja->format('d-m-Y')
                                : '-' }}

                        </td>

                    </tr>

                    <tr>

                        <th>Masa Kerja Gol</th>

                        <td>

                            {{ $pegawai->masa_kerja_gol_th ?? 0 }}
                            Tahun

                            {{ $pegawai->masa_kerja_gol_bln ?? 0 }}
                            Bulan

                        </td>

                    </tr>

                    <tr>

                        <th>Masa Kerja Keseluruhan</th>

                        <td>

                            {{ $pegawai->masa_kerja_total_th ?? 0 }}
                            Tahun

                            {{ $pegawai->masa_kerja_total_bln ?? 0 }}
                            Bulan

                        </td>

                    </tr>

                    <tr>

                        <th>Mengajar Di Kelas</th>

                        <td>

                            {{ $pegawai->mengajar_kelas ?? '-' }}

                        </td>

                    </tr>

                    <tr>

                        <th>Jam Mengajar</th>

                        <td>

                            {{ $pegawai->jam_mengajar ?? '-' }}
                            Jam

                        </td>

                    </tr>

                    <tr>

                        <th>Tugas Tambahan</th>

                        <td>

                            {{ $pegawai->tugas_tambahan ?? '-' }}

                        </td>

                    </tr>

                    <tr>

                        <th>Nomor Sertifikasi</th>

                        <td>

                            {{ $pegawai->nomor_sertifikasi ?? '-' }}

                            @if($pegawai->tahun_sertifikasi)

                                (Th. {{ $pegawai->tahun_sertifikasi }})

                            @endif

                        </td>

                    </tr>

                    <tr>

                        <th>Keterangan</th>

                        <td>

                            {{ $pegawai->keterangan ?? '-' }}

                        </td>

                    </tr>

                </table>

            </div>

        </div>

    </div>

</div>

</div>

</div>

    <style>

        .table-detail th{

            width:40%;

            color:#64748b;

            font-weight:600;

        }

        .table-detail td{

            color:#0f172a;

        }

    </style>

<style>

.card{

    transition:.25s;

}

.card:hover{

    box-shadow:0 18px 45px rgba(37,99,235,.08)!important;

}

.avatar-profile{

    width:110px;

    height:110px;

    border-radius:50%;

    background:linear-gradient(135deg,#2563eb,#0f4c81);

    color:#fff;

    display:flex;

    align-items:center;

    justify-content:center;

    font-size:2rem;

    font-weight:700;

    flex-shrink:0;

}

.info-card{

    background:#f8fbff;

    border:1px solid #dbeafe;

    border-radius:16px;

    padding:18px;

    height:100%;

}

.detail-card{

    background:#fff;

    border:1px solid #eef2f7;

    border-radius:18px;

    padding:24px;

    height:100%;

}

.section-title{

    font-weight:700;

    color:#2563eb;

    margin-bottom:20px;

}

.table-detail th{

    width:42%;

    color:#475569;

    font-weight:600;

    padding:.9rem 1rem;

    border-bottom:1px solid #eef2f7;

}

.table-detail td{

    padding:.9rem 1rem;

    border-bottom:1px solid #eef2f7;

    color:#0f172a;

}

.table-detail tr:last-child th,
.table-detail tr:last-child td{

    border-bottom:none;

}

.table-borderless td{

    color:#0f172a;

}

.badge{

    font-weight:600;

}

.btn{

    transition:.2s;

}

.btn:hover{

    transform:translateY(-2px);

}

@media(max-width:768px){

    .avatar-profile{

        width:90px;

        height:90px;

        font-size:1.6rem;

    }

}

</style>

@endsection
