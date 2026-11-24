@extends('layouts.app')

@section('title', 'Detail Profil')

@section('content')
<div class="container-fluid py-4">
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header border-0">
            <h5 class="mb-1 text-primary fw-bold">Profil Akun</h5>
            <p class="mb-0 text-muted">Ubah email akun atau ajukan koreksi data kepegawaian.</p>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{ route('guru.profile.detail.update') }}" method="POST" class="mb-4">
                @csrf
                @method('PUT')
                <div class="row align-items-end g-3">
                    <div class="col-md-8">
                        <label for="email" class="form-label">Email akun</label>
                        <input id="email" type="email" name="email" class="form-control"
                            value="{{ old('email', $user->email) }}" maxlength="255">
                    </div>
                    <div class="col-md-4">
                        <button class="btn btn-primary" type="submit">Simpan Email</button>
                    </div>
                </div>
            </form>
            <div class="alert alert-info mb-0">
                Data kepegawaian resmi tidak dapat diubah langsung dari profil. Ajukan perubahan melalui
                <a href="{{ route('guru.pegawai-changes.create') }}">form Perubahan Data Pegawai</a>;
                status dan hasil verifikasi tersedia pada
                <a href="{{ route('guru.pegawai-changes.index') }}">riwayat pengajuan</a>.
            </div>
        </div>
    </div>

    @if ($pegawai)
        <div class="card shadow-sm border-0">
            <div class="card-header border-0">
                <h5 class="mb-0 text-primary fw-bold">Data Kepegawaian Saat Ini</h5>
            </div>
            <div class="card-body">
                <dl class="row mb-0">
                    @foreach ([
                        'nama' => 'Nama lengkap',
                        'nip' => 'NIP',
                        'nuptk' => 'NUPTK',
                        'tempat_lahir' => 'Tempat lahir',
                        'tanggal_lahir' => 'Tanggal lahir',
                        'agama' => 'Agama',
                        'jenis_kelamin' => 'Jenis kelamin',
                        'status_kawin' => 'Status perkawinan',
                        'ijazah_terakhir' => 'Pendidikan terakhir',
                        'pengalaman_tahun' => 'Pengalaman mengajar (tahun)',
                    ] as $field => $label)
                        <dt class="col-sm-4 col-lg-3">{{ $label }}</dt>
                        <dd class="col-sm-8 col-lg-9">
                            {{ $pegawai->{$field} instanceof \Carbon\CarbonInterface
                                ? $pegawai->{$field}->format('Y-m-d')
                                : ($pegawai->{$field} ?? '—') }}
                        </dd>
                    @endforeach
                </dl>
            </div>
        </div>
    @endif
</div>
@endsection
