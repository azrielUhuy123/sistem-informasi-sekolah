@extends('layouts.tu')

@section('title', 'Detail Pegawai')

@section('content')

<div class="d-flex justify-content-between mb-4">
    <h1>Detail Data Pegawai</h1>
    <a href="{{ route('tu.datakepegawaian.index') }}" class="btn btn-secondary">Kembali</a>
</div>

<div class="card">
    <div class="card-body">

        <table class="table table-bordered">
            <tr>
                <th width="250px">Nama</th>
                <td>{{ $pegawai->nama }}</td>
            </tr>

            <tr>
                <th>NIP</th>
                <td>{{ $pegawai->nip ?? '-' }}</td>
            </tr>

            <tr>
                <th>Tempat, Tanggal Lahir</th>
                <td>
                    {{ $pegawai->tempat_lahir ?? '-' }},
                    {{ $pegawai->tanggal_lahir ?? '-' }}
                </td>
            </tr>

            <tr>
                <th>Jabatan</th>
                <td>{{ $pegawai->jabatan ?? '-' }}</td>
            </tr>

            <tr>
                <th>Status Kerja</th>
                <td>{{ $pegawai->status_kerja ?? '-' }}</td>
            </tr>

            <tr>
                <th>Pendidikan Terakhir</th>
                <td>{{ $pegawai->pendidikan_terakhir ?? '-' }}</td>
            </tr>

            <tr>
                <th>Mengajar di Kelas</th>
                <td>{{ $pegawai->mengajar_di_kelas ?? '-' }}</td>
            </tr>

            <tr>
                <th>Pengalaman (tahun)</th>
                <td>{{ $pegawai->pengalaman_tahun ?? '-' }}</td>
            </tr>

            <tr>
                <th>Gaji</th>
                <td>
                    {{ is_numeric($pegawai->gaji) ? 'Rp ' . number_format($pegawai->gaji, 0, ',', '.') : '-' }}
                </td>
            </tr>

            <tr>
                <th>Tunjangan</th>
                <td>
                    {{ is_numeric($pegawai->tunjangan) ? 'Rp ' . number_format($pegawai->tunjangan, 0, ',', '.') : '-' }}
                </td>
            </tr>

            <tr>
                <th>Absensi Tahun Ini</th>
                <td>{{ is_numeric($pegawai->absensi_tahun_ini) ? $pegawai->absensi_tahun_ini : '-' }}</td>
            </tr>

            <tr>
                <th>Nilai Kinerja</th>
                <td>{{ $pegawai->nilai_kinerja ?? '-' }}</td>
            </tr>

            <tr>
                <th>Pelatihan / Sertifikasi</th>
                <td>{{ $pegawai->pelatihan ?? '-' }}</td>
            </tr>
        </table>

    </div>
</div>

@endsection
