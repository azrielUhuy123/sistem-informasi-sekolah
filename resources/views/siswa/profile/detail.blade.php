@extends('layouts.app')

@section('title', 'Detail Profil')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3 class="fw-bold mb-1">
                Detail Profil
            </h3>

            <p class="text-muted mb-0">
                Kelola data identitas siswa.
            </p>

        </div>

        <a href="{{ route('siswa.profile') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>
            Kembali
        </a>

    </div>

    <form action="{{ route('siswa.profile.detail.update') }}" method="POST">

        @csrf
        @method('PUT')

        {{-- IDENTITAS SISWA --}}
        <div class="card shadow-sm border-0 mb-4">

            <div class="card-header">

                <div class="d-flex align-items-center">

                    <div class="section-icon">
                        <i class="bi bi-person-vcard"></i>
                    </div>

                    <div class="ms-3">

                        <h5 class="mb-1 fw-bold">
                            Identitas Siswa
                        </h5>

                        <small class="text-muted">
                            Informasi utama peserta didik
                        </small>

                    </div>

                </div>

            </div>

            <div class="card-body">

                <div class="row g-3">

                    <div class="col-md-6">

                        <label class="form-label">
                            Nama Lengkap
                        </label>

                        <input
                            type="text"
                            name="nama"
                            class="form-control"
                            value="{{ old('nama', $ppdb->nama ?? '') }}">

                    </div>

                    <div class="col-md-6">

                        <label class="form-label">
                            Email
                        </label>

                        <input
                            type="email"
                            name="email"
                            class="form-control"
                            value="{{ old('email', $user->email) }}">

                    </div>

                    <div class="col-md-4">

                        <label class="form-label">
                            NIS
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            value="{{ $siswa->nis }}"
                            readonly>

                    </div>

                    <div class="col-md-4">

                        <label class="form-label">
                            NISN
                        </label>

                        <input
                            type="text"
                            name="nisn"
                            class="form-control"
                            value="{{ old('nisn', $siswa->nisn) }}">

                    </div>

                    <div class="col-md-4">

                        <label class="form-label">
                            Nomor Pendaftaran
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            value="{{ $ppdb->no_pendaftaran ?? '-' }}"
                            readonly>

                    </div>

                    <div class="col-md-6">

                        <label class="form-label">
                            NIK
                        </label>

                        <input
                            type="text"
                            name="nik"
                            class="form-control"
                            value="{{ old('nik', $ppdb->nik ?? '') }}">

                    </div>

                    <div class="col-md-6">

                        <label class="form-label">
                            Nomor KK
                        </label>

                        <input
                            type="text"
                            name="nomor_kk"
                            class="form-control"
                            value="{{ old('nomor_kk', $ppdb->nomor_kk ?? '') }}">

                    </div>

                </div>

            </div>

        </div>

        {{-- DATA PRIBADI --}}
        <div class="card shadow-sm border-0 mb-4">

            <div class="card-header">

                <div class="d-flex align-items-center">

                    <div class="section-icon">
                        <i class="bi bi-person"></i>
                    </div>

                    <div class="ms-3">

                        <h5 class="mb-1 fw-bold">
                            Data Pribadi
                        </h5>

                        <small class="text-muted">
                            Informasi identitas pribadi siswa
                        </small>

                    </div>

                </div>

            </div>

            <div class="card-body">

                <div class="row g-3">

                    <div class="col-md-4">

                        <label class="form-label">
                            Tempat Lahir
                        </label>

                        <input
                            type="text"
                            name="tempat_lahir"
                            class="form-control"
                            value="{{ old('tempat_lahir',$ppdb->tempat_lahir ?? '') }}">

                    </div>

                    <div class="col-md-4">

                        <label class="form-label">
                            Tanggal Lahir
                        </label>

                        <input
                            type="date"
                            name="tanggal_lahir"
                            class="form-control"
                            value="{{ old('tanggal_lahir',$ppdb->tanggal_lahir ?? '') }}">

                    </div>

                    <div class="col-md-4">

                        <label class="form-label">
                            Agama
                        </label>

                        <select
                            name="agama"
                            class="form-select">

                            @foreach([
                                'Islam',
                                'Kristen',
                                'Katolik',
                                'Hindu',
                                'Buddha',
                                'Konghucu'
                            ] as $agama)

                                <option
                                    value="{{ $agama }}"
                                    {{ old('agama',$ppdb->agama ?? '') == $agama ? 'selected' : '' }}>

                                    {{ $agama }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                    <div class="col-md-4">

                        <label class="form-label">
                            Jenis Kelamin
                        </label>

                        <select
                            name="jenis_kelamin"
                            class="form-select">

                            <option
                                value="L"
                                {{ old('jenis_kelamin',$ppdb->jenis_kelamin ?? '')=='L'?'selected':'' }}>
                                Laki-laki
                            </option>

                            <option
                                value="P"
                                {{ old('jenis_kelamin',$ppdb->jenis_kelamin ?? '')=='P'?'selected':'' }}>
                                Perempuan
                            </option>

                        </select>

                    </div>

                    <div class="col-md-4">

                        <label class="form-label">
                            Kewarganegaraan
                        </label>

                        <input
                            type="text"
                            name="kewarganegaraan"
                            class="form-control"
                            value="{{ old('kewarganegaraan',$ppdb->kewarganegaraan ?? '') }}">

                    </div>

                    <div class="col-md-4">

                        <label class="form-label">
                            Nomor Akta Kelahiran
                        </label>

                        <input
                            type="text"
                            name="nomor_akta"
                            class="form-control"
                            value="{{ old('nomor_akta',$ppdb->nomor_akta ?? '') }}">

                    </div>

                    <div class="col-md-4">

                        <label class="form-label">
                            Anak Ke
                        </label>

                        <input
                            type="number"
                            name="anak_ke"
                            class="form-control"
                            value="{{ old('anak_ke',$ppdb->anak_ke ?? '') }}">

                    </div>

                    <div class="col-md-4">

                        <label class="form-label">
                            Jumlah Saudara
                        </label>

                        <input
                            type="number"
                            name="jumlah_saudara"
                            class="form-control"
                            value="{{ old('jumlah_saudara',$ppdb->jumlah_saudara ?? '') }}">

                    </div>

                    <div class="col-md-4">

                        <label class="form-label">
                            Tinggi Badan (cm)
                        </label>

                        <input
                            type="number"
                            step="0.01"
                            name="tinggi_badan"
                            class="form-control"
                            value="{{ old('tinggi_badan',$ppdb->tinggi_badan ?? '') }}">

                    </div>

                    <div class="col-md-4">

                        <label class="form-label">
                            Berat Badan (kg)
                        </label>

                        <input
                            type="number"
                            step="0.01"
                            name="berat_badan"
                            class="form-control"
                            value="{{ old('berat_badan',$ppdb->berat_badan ?? '') }}">

                    </div>

                </div>

            </div>

        </div>

        {{-- DATA AKADEMIK --}}
        <div class="card shadow-sm border-0 mb-4">

            <div class="card-header">

                <div class="d-flex align-items-center">

                    <div class="section-icon">
                        <i class="bi bi-mortarboard"></i>
                    </div>

                    <div class="ms-3">

                        <h5 class="mb-1 fw-bold">
                            Data Akademik
                        </h5>

                        <small class="text-muted">
                            Informasi akademik siswa
                        </small>

                    </div>

                </div>

            </div>

            <div class="card-body">

                <div class="row g-3">

                    <div class="col-md-4">

                        <label class="form-label">
                            NIS
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            value="{{ $siswa->nis }}"
                            readonly>

                    </div>

                    <div class="col-md-4">

                        <label class="form-label">
                            NISN
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            value="{{ $siswa->nisn }}"
                            readonly>

                    </div>

                    <div class="col-md-4">

                        <label class="form-label">
                            Kelas
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            value="{{ $siswa->penempatanAktif?->kelas?->nama_kelas_label ?? '-' }}"
                            readonly>

                    </div>

                    <div class="col-md-4">

                        <label class="form-label">
                            Tahun Ajaran
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            value="{{ current_tahun()?->tahun }}"
                            readonly>

                    </div>

                    <div class="col-md-4">

                        <label class="form-label">
                            Nomor Pendaftaran
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            value="{{ $ppdb->no_pendaftaran }}"
                            readonly>

                    </div>

                    <div class="col-md-4">

                        <label class="form-label">
                            Status Daftar Ulang
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            value="{{ ucfirst($ppdb->status_daftar_ulang) }}"
                            readonly>

                    </div>

                    <div class="col-md-6">

                        <label class="form-label">
                            Status Pendaftaran
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            value="{{ ucfirst($ppdb->status) }}"
                            readonly>

                    </div>

                    <div class="col-md-6">

                        <label class="form-label">
                            Tanggal Pendaftaran
                        </label>

                        <input
                            type="date"
                            class="form-control"
                            value="{{ $ppdb->tanggal_pendaftaran }}"
                            readonly>

                    </div>

                </div>

            </div>

        </div>

        <div class="row">

            {{-- ALAMAT --}}
            <div class="card shadow-sm border-0 mb-4">

                <div class="card-header">

                    <div class="d-flex align-items-center">

                        <div class="section-icon">
                            <i class="bi bi-geo-alt"></i>
                        </div>

                        <div class="ms-3">

                            <h5 class="mb-1 fw-bold">
                                Alamat
                            </h5>

                            <small class="text-muted">
                                Informasi alamat tempat tinggal siswa
                            </small>

                        </div>

                    </div>

                </div>

                <div class="card-body">

                    <div class="row g-3">

                        <div class="col-md-12">

                            <label class="form-label">
                                Alamat
                            </label>

                            <textarea
                                name="alamat"
                                rows="3"
                                class="form-control">{{ old('alamat', $ppdb->alamat ?? '') }}</textarea>

                        </div>

                        <div class="col-md-2">

                            <label class="form-label">
                                RT
                            </label>

                            <input
                                type="text"
                                name="rt"
                                class="form-control"
                                value="{{ old('rt', $ppdb->rt ?? '') }}">

                        </div>

                        <div class="col-md-2">

                            <label class="form-label">
                                RW
                            </label>

                            <input
                                type="text"
                                name="rw"
                                class="form-control"
                                value="{{ old('rw', $ppdb->rw ?? '') }}">

                        </div>

                        <div class="col-md-4">

                            <label class="form-label">
                                Desa / Kelurahan
                            </label>

                            <input
                                type="text"
                                name="desa"
                                class="form-control"
                                value="{{ old('desa', $ppdb->desa ?? '') }}">

                        </div>

                        <div class="col-md-4">

                            <label class="form-label">
                                Kecamatan
                            </label>

                            <input
                                type="text"
                                name="kecamatan"
                                class="form-control"
                                value="{{ old('kecamatan', $ppdb->kecamatan ?? '') }}">

                        </div>

                        <div class="col-md-6">

                            <label class="form-label">
                                Kabupaten / Kota
                            </label>

                            <input
                                type="text"
                                name="kabupaten"
                                class="form-control"
                                value="{{ old('kabupaten', $ppdb->kabupaten ?? '') }}">

                        </div>

                        <div class="col-md-6">

                            <label class="form-label">
                                Provinsi
                            </label>

                            <input
                                type="text"
                                name="provinsi"
                                class="form-control"
                                value="{{ old('provinsi', $ppdb->provinsi ?? '') }}">

                        </div>

                        <div class="col-md-4">

                            <label class="form-label">
                                Kode Pos
                            </label>

                            <input
                                type="text"
                                name="kode_pos"
                                class="form-control"
                                value="{{ old('kode_pos', $ppdb->kode_pos ?? '') }}">

                        </div>

                        <div class="col-md-8">

                            <label class="form-label">
                                Telepon Rumah
                            </label>

                            <input
                                type="text"
                                name="telepon_rumah"
                                class="form-control"
                                value="{{ old('telepon_rumah', $ppdb->telepon_rumah ?? '') }}">

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <div class="row">

            {{-- DATA ORANG TUA --}}
            <div class="card shadow-sm border-0 mb-4">

                <div class="card-header">

                    <div class="d-flex align-items-center">

                        <div class="section-icon">
                            <i class="bi bi-people"></i>
                        </div>

                        <div class="ms-3">

                            <h5 class="mb-1 fw-bold">
                                Data Orang Tua
                            </h5>

                            <small class="text-muted">
                                Informasi ayah dan ibu siswa
                            </small>

                        </div>

                    </div>

                </div>

                <div class="card-body">

                    {{-- AYAH --}}
                    <h6 class="fw-bold text-primary mb-3">
                        <i class="bi bi-person-fill me-2"></i>
                        Data Ayah
                    </h6>

                    <div class="row g-3 mb-4">

                        <div class="col-md-6">

                            <label class="form-label">
                                Nama Ayah
                            </label>

                            <input
                                type="text"
                                name="nama_ayah"
                                class="form-control"
                                value="{{ old('nama_ayah',$ppdb->nama_ayah ?? '') }}">

                        </div>

                        <div class="col-md-6">

                            <label class="form-label">
                                NIK Ayah
                            </label>

                            <input
                                type="text"
                                name="nik_ayah"
                                class="form-control"
                                value="{{ old('nik_ayah',$ppdb->nik_ayah ?? '') }}">

                        </div>

                        <div class="col-md-4">

                            <label class="form-label">
                                Pendidikan
                            </label>

                            <input
                                type="text"
                                name="pendidikan_ayah"
                                class="form-control"
                                value="{{ old('pendidikan_ayah',$ppdb->pendidikan_ayah ?? '') }}">

                        </div>

                        <div class="col-md-4">

                            <label class="form-label">
                                Pekerjaan
                            </label>

                            <input
                                type="text"
                                name="pekerjaan_ayah"
                                class="form-control"
                                value="{{ old('pekerjaan_ayah',$ppdb->pekerjaan_ayah ?? '') }}">

                        </div>

                        <div class="col-md-4">

                            <label class="form-label">
                                Penghasilan
                            </label>

                            <input
                                type="text"
                                name="penghasilan_ayah"
                                class="form-control"
                                value="{{ old('penghasilan_ayah',$ppdb->penghasilan_ayah ?? '') }}">

                        </div>

                        <div class="col-md-6">

                            <label class="form-label">
                                No. HP Ayah
                            </label>

                            <input
                                type="text"
                                name="hp_ayah"
                                class="form-control"
                                value="{{ old('hp_ayah',$ppdb->hp_ayah ?? '') }}">

                        </div>

                    </div>

                    <hr class="my-4">

                    {{-- IBU --}}
                    <h6 class="fw-bold text-danger mb-3">
                        <i class="bi bi-person-heart me-2"></i>
                        Data Ibu
                    </h6>

                    <div class="row g-3">

                        <div class="col-md-6">

                            <label class="form-label">
                                Nama Ibu
                            </label>

                            <input
                                type="text"
                                name="nama_ibu"
                                class="form-control"
                                value="{{ old('nama_ibu',$ppdb->nama_ibu ?? '') }}">

                        </div>

                        <div class="col-md-6">

                            <label class="form-label">
                                NIK Ibu
                            </label>

                            <input
                                type="text"
                                name="nik_ibu"
                                class="form-control"
                                value="{{ old('nik_ibu',$ppdb->nik_ibu ?? '') }}">

                        </div>

                        <div class="col-md-4">

                            <label class="form-label">
                                Pendidikan
                            </label>

                            <input
                                type="text"
                                name="pendidikan_ibu"
                                class="form-control"
                                value="{{ old('pendidikan_ibu',$ppdb->pendidikan_ibu ?? '') }}">

                        </div>

                        <div class="col-md-4">

                            <label class="form-label">
                                Pekerjaan
                            </label>

                            <input
                                type="text"
                                name="pekerjaan_ibu"
                                class="form-control"
                                value="{{ old('pekerjaan_ibu',$ppdb->pekerjaan_ibu ?? '') }}">

                        </div>

                        <div class="col-md-4">

                            <label class="form-label">
                                Penghasilan
                            </label>

                            <input
                                type="text"
                                name="penghasilan_ibu"
                                class="form-control"
                                value="{{ old('penghasilan_ibu',$ppdb->penghasilan_ibu ?? '') }}">

                        </div>

                        <div class="col-md-6">

                            <label class="form-label">
                                No. HP Ibu
                            </label>

                            <input
                                type="text"
                                name="hp_ibu"
                                class="form-control"
                                value="{{ old('hp_ibu',$ppdb->hp_ibu ?? '') }}">

                        </div>

                    </div>

                </div>

            </div>
        </div>

        <div class="text-end">

            <button
                class="btn btn-primary px-4">

                <i class="bi bi-check-circle me-1"></i>
                Simpan Perubahan

            </button>

        </div>

    </form>

</div>

@push('styles')
<style>
/* ==========================
   PAGE
========================== */

body{
    background:#f5f7fb;
}

/* ==========================
   CARD
========================== */

.card{
    border:none;
    border-radius:18px;
    overflow:hidden;
    box-shadow:0 10px 30px rgba(15,23,42,.06);
    transition:.3s;
}

.card:hover{
    box-shadow:0 18px 40px rgba(15,23,42,.08);
}

.card-header{
    background:#fff;
    border-bottom:1px solid #edf2f7;
    padding:18px 24px;
}

.card-body{
    padding:28px;
}

/* ==========================
   SECTION ICON
========================== */

.section-icon{

    width:52px;
    height:52px;

    border-radius:14px;

    background:linear-gradient(135deg,#2563eb,#3b82f6);

    color:#fff;

    display:flex;
    align-items:center;
    justify-content:center;

    font-size:22px;

    flex-shrink:0;

    box-shadow:0 8px 20px rgba(37,99,235,.25);

}

/* ==========================
   TITLE
========================== */

.card-header h5{

    font-size:1.05rem;

    color:#1e293b;

}

.card-header small{

    font-size:.82rem;

    color:#94a3b8;

}

/* ==========================
   LABEL
========================== */

.form-label,
label{

    font-size:.83rem;

    font-weight:700;

    color:#64748b;

    margin-bottom:7px;

}

/* ==========================
   INPUT
========================== */

.form-control,
.form-select{

    height:50px;

    border-radius:14px;

    border:1px solid #dbe4ee;

    background:#fff;

    font-size:.94rem;

    transition:.25s;

    box-shadow:none;

}

textarea.form-control{

    height:auto;

    min-height:150px;

    padding-top:14px;

}

/* ==========================
   HOVER
========================== */

.form-control:hover,
.form-select:hover{

    border-color:#93c5fd;

}

/* ==========================
   FOCUS
========================== */

.form-control:focus,
.form-select:focus{

    border-color:#2563eb;

    box-shadow:0 0 0 .2rem rgba(37,99,235,.10);

}

/* ==========================
   BUTTON
========================== */

.btn-primary{

    background:#2563eb;

    border:none;

    border-radius:14px;

    padding:11px 28px;

    font-weight:600;

}

.btn-primary:hover{

    background:#1d4ed8;

}

.btn-outline-secondary{

    border-radius:14px;

}

/* ==========================
   CARD SPACING
========================== */

.card+.card{

    margin-top:26px;

}

/* ==========================
   ROW GAP
========================== */

.row.g-3{

    --bs-gutter-y:1.25rem;

}

/* ==========================
   PLACEHOLDER
========================== */

.form-control::placeholder{

    color:#cbd5e1;

}

/* ==========================
   DISABLED
========================== */

.form-control:disabled{

    background:#f8fafc;

}

/* ==========================
   SCROLLBAR
========================== */

::-webkit-scrollbar{

    width:8px;

}

::-webkit-scrollbar-thumb{

    background:#cbd5e1;

    border-radius:10px;

}

/* ==========================
   RESPONSIVE
========================== */

@media(max-width:992px){

    .card-body{

        padding:20px;

    }

    .section-icon{

        width:45px;
        height:45px;
        font-size:18px;

    }

}

/* ==========================
   CUSTOM SELECT
========================== */

.form-select{

    appearance:none;
    -webkit-appearance:none;
    -moz-appearance:none;

    padding-right:52px;

    background-color:#fff;

    background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='18' height='18' fill='%2364748b' viewBox='0 0 16 16'%3E%3Cpath fill-rule='evenodd' d='M1.646 5.646a.5.5 0 0 1 .708 0L8 11.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3E%3C/svg%3E");

    background-repeat:no-repeat;

    background-position:right 18px center;

    background-size:18px;

    cursor:pointer;

}

.form-select:hover{

    border-color:#60a5fa;

    background-position:right 15px center;

}

.form-select:focus{

    background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='18' height='18' fill='%232563eb' viewBox='0 0 16 16'%3E%3Cpath fill-rule='evenodd' d='M1.646 5.646a.5.5 0 0 1 .708 0L8 11.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3E%3C/svg%3E");

}
</style>
@endpush

@push('scripts')

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if(session('success'))

<script>

Swal.fire({

    icon: 'success',

    title: 'Berhasil',

    text: '{{ session('success') }}',

    confirmButtonColor: '#2563eb',

    confirmButtonText: 'OK',

    timer: 2200,

    timerProgressBar: true,

    showClass: {
        popup: 'animate__animated animate__fadeInDown'
    },

    hideClass: {
        popup: 'animate__animated animate__fadeOutUp'
    }

});

</script>

@endif

@endpush

@endsection