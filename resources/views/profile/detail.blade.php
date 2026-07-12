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
                Kelola data pribadi dan data kepegawaian Anda.
            </p>

        </div>

       <a href="{{ route('guru.profile') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>
            Kembali
        </a>

    </div>

    <form action="{{ route('guru.profile.detail.update') }}" method="POST">

        @csrf
        @method('PUT')

        {{-- IDENTITAS --}}
        <div class="card shadow-sm border-0 mb-4">

            <div class="card-header">

                <div class="d-flex align-items-center">

                    <div class="section-icon">
                        <i class="bi bi-person-vcard"></i>
                    </div>

                    <div class="ms-3">

                        <h5 class="mb-1 fw-bold">
                            Identitas
                        </h5>

                        <small class="text-muted">
                            Informasi akun utama guru
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
                            value="{{ old('nama',$pegawai->nama) }}">

                    </div>

                    <div class="col-md-6">

                        <label class="form-label">
                            Email
                        </label>

                        <input
                            type="email"
                            name="email"
                            class="form-control"
                            value="{{ old('email',$user->email) }}">

                    </div>

                    <div class="col-md-4">

                        <label class="form-label">
                            NIP
                        </label>

                        <input
                            type="text"
                            name="nip"
                            class="form-control"
                            value="{{ old('nip',$pegawai->nip) }}">

                    </div>

                    <div class="col-md-4">

                        <label class="form-label">
                            NUPTK
                        </label>

                        <input
                            type="text"
                            name="nuptk"
                            class="form-control"
                            value="{{ old('nuptk',$pegawai->nuptk) }}">

                    </div>

                    <div class="col-md-4">

                        <label class="form-label">
                            Jabatan
                        </label>

                        <input
                            type="text"
                            name="jabatan"
                            class="form-control"
                            value="{{ old('jabatan',$pegawai->jabatan) }}">

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
                            Informasi identitas pribadi
                        </small>

                    </div>

                </div>

            </div>

            <div class="card-body">

                <div class="row g-3">

                    <div class="col-md-4">

                        <label>Tempat Lahir</label>

                        <input
                            type="text"
                            name="tempat_lahir"
                            class="form-control"
                            value="{{ old('tempat_lahir',$pegawai->tempat_lahir) }}">

                    </div>

                    <div class="col-md-4">

                        <label>Tanggal Lahir</label>

                        <input
                            type="date"
                            name="tanggal_lahir"
                            class="form-control"
                            value="{{ old('tanggal_lahir',$pegawai->tanggal_lahir) }}">

                    </div>

                    <div class="col-md-4">

                        <label>Agama</label>

                       <select name="agama" class="form-select">

                            @foreach([
                                'Islam',
                                'Kristen',
                                'Katolik',
                                'Hindu',
                                'Buddha',
                                'Konghucu'
                            ] as $agama)

                                <option value="{{ $agama }}"
                                    {{ old('agama',$pegawai->agama)==$agama ? 'selected' : '' }}>
                                    {{ $agama }}
                                </option>

                            @endforeach

                        </select>
                    </div>

                    <div class="col-md-4">

                        <label>Jenis Kelamin</label>

                        <select
                            name="jenis_kelamin"
                            class="form-select">

                            <option value="L"
                                {{ $pegawai->jenis_kelamin=='L'?'selected':'' }}>
                                Laki-laki
                            </option>

                            <option value="P"
                                {{ $pegawai->jenis_kelamin=='P'?'selected':'' }}>
                                Perempuan
                            </option>

                        </select>

                    </div>

                    <div class="col-md-4">

                        <label>Status Kawin</label>

                        <select name="status_kawin" class="form-select">
                            <option value="K"
                                {{ old('status_kawin',$pegawai->status_kawin)=='K' ? 'selected' : '' }}>
                                Kawin
                            </option>

                            <option value="TK"
                                {{ old('status_kawin',$pegawai->status_kawin)=='TK' ? 'selected' : '' }}>
                                Belum Kawin
                            </option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label>Pengalaman Mengajar (Tahun)</label>
                        <input type="number"
                            name="pengalaman_tahun"
                            class="form-control"
                            value="{{ old('pengalaman_tahun',$pegawai->pengalaman_tahun) }}">
                    </div>

                </div>

            </div>

        </div>

        {{-- DATA KEPEGAWAIAN --}}
        <div class="card shadow-sm border-0 mb-4">

            <div class="card-header">

                <div class="d-flex align-items-center">

                    <div class="section-icon">
                        <i class="bi bi-briefcase"></i>
                    </div>

                    <div class="ms-3">

                        <h5 class="mb-1 fw-bold">
                            Data Kepegawaian
                        </h5>

                        <small class="text-muted">
                            Informasi administrasi pegawai
                        </small>

                    </div>

                </div>

            </div>

            <div class="card-body">

                <div class="row g-3">

                    <div class="col-md-4">

                        <label>Status Kepegawaian</label>

                        <select name="status_kepegawaian" class="form-select">
                            <option value="">-- Pilih Status --</option>
                            <option value="PNS"
                                {{ old('status_kepegawaian',$pegawai->status_kepegawaian)=='PNS' ? 'selected' : '' }}>
                                PNS
                            </option>
                            <option value="NON_PNS"
                                {{ old('status_kepegawaian',$pegawai->status_kepegawaian)=='NON_PNS' ? 'selected' : '' }}>
                                NON PNS
                            </option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label>Mengajar Kelas</label>
                        <input type="text"
                            name="mengajar_kelas"
                            class="form-control"
                            value="{{ old('mengajar_kelas',$pegawai->mengajar_kelas) }}">
                    </div>

                    <div class="col-md-4">
                        <label>Gaji Pokok</label>
                        <input type="number"
                            step="0.01"
                            name="gaji_pokok"
                            class="form-control"
                            value="{{ old('gaji_pokok',$pegawai->gaji_pokok) }}">
                    </div>

                    <div class="col-md-4">
                        <label>Jam Mengajar</label>
                        <input type="number"
                            name="jam_mengajar"
                            class="form-control"
                            value="{{ old('jam_mengajar',$pegawai->jam_mengajar) }}">
                    </div>

                    <div class="col-md-4">
                        <label>Tugas Tambahan</label>
                        <input type="text"
                            name="tugas_tambahan"
                            class="form-control"
                            value="{{ old('tugas_tambahan',$pegawai->tugas_tambahan) }}">
                    </div>

                    <div class="col-md-4">

                        <label>Ijazah Terakhir</label>

                        <input
                            type="text"
                            name="ijazah_terakhir"
                            class="form-control"
                            value="{{ old('ijazah_terakhir',$pegawai->ijazah_terakhir) }}">

                    </div>

                    <div class="col-md-4">

                        <label>Mulai Kerja</label>

                        <input
                            type="date"
                            name="mulai_kerja"
                            class="form-control"
                            value="{{ old('mulai_kerja',$pegawai->mulai_kerja) }}">

                    </div>

                </div>

            </div>

        </div>

        <div class="row">

            {{-- SK TERAKHIR --}}
            <div class="col-lg-6 mb-4">

                <div class="card shadow-sm border-0 h-100">

                    <div class="card-header">

                        <div class="d-flex align-items-center">

                            <div class="section-icon">
                                <i class="bi bi-file-earmark-text"></i>
                            </div>

                            <div class="ms-3">

                                <h5 class="mb-1 fw-bold">
                                    SK Terakhir
                                </h5>

                                <small class="text-muted">
                                    Data SK pangkat terakhir
                                </small>

                            </div>

                        </div>

                    </div>

                    <div class="card-body">

                        <div class="row g-3">

                            <div class="col-12">

                                <label class="form-label">
                                    Pangkat
                                </label>

                                <input
                                    type="text"
                                    name="sk_terakhir_pangkat"
                                    class="form-control"
                                    value="{{ old('sk_terakhir_pangkat', $pegawai->sk_terakhir_pangkat) }}">

                            </div>

                            <div class="col-12">

                                <label class="form-label">
                                    Nomor SK
                                </label>

                                <input
                                    type="text"
                                    name="sk_terakhir_no"
                                    class="form-control"
                                    value="{{ old('sk_terakhir_no', $pegawai->sk_terakhir_no) }}">

                            </div>

                            <div class="col-12">

                                <label class="form-label">
                                    TMT
                                </label>

                                <input
                                    type="date"
                                    name="sk_terakhir_tmt"
                                    class="form-control"
                                    value="{{ old('sk_terakhir_tmt', $pegawai->sk_terakhir_tmt) }}">

                            </div>

                        </div>

                    </div>

                </div>

            </div>

            {{-- SK BERKALA --}}
            <div class="col-lg-6 mb-4">

                <div class="card shadow-sm border-0 h-100">

                    <div class="card-header">

                        <div class="d-flex align-items-center">

                            <div class="section-icon">
                                <i class="bi bi-arrow-repeat"></i>
                            </div>

                            <div class="ms-3">

                                <h5 class="mb-1 fw-bold">
                                    SK Berkala
                                </h5>

                                <small class="text-muted">
                                    Informasi kenaikan gaji berkala
                                </small>

                            </div>

                        </div>

                    </div>

                    <div class="card-body">

                        <div class="row g-3">

                            <div class="col-12">

                                <label class="form-label">
                                    Pangkat
                                </label>

                                <input
                                    type="text"
                                    name="sk_berkala_pangkat"
                                    class="form-control"
                                    value="{{ old('sk_berkala_pangkat', $pegawai->sk_berkala_pangkat) }}">

                            </div>

                            <div class="col-12">

                                <label class="form-label">
                                    Nomor SK
                                </label>

                                <input
                                    type="text"
                                    name="sk_berkala_no"
                                    class="form-control"
                                    value="{{ old('sk_berkala_no', $pegawai->sk_berkala_no) }}">

                            </div>

                            <div class="col-12">

                                <label class="form-label">
                                    TMT
                                </label>

                                <input
                                    type="date"
                                    name="sk_berkala_tmt"
                                    class="form-control"
                                    value="{{ old('sk_berkala_tmt', $pegawai->sk_berkala_tmt) }}">

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <div class="row">

            {{-- MASA KERJA --}}
            <div class="col-lg-6 mb-4">

                <div class="card shadow-sm border-0 h-100">

                    <div class="card-header">

                        <div class="d-flex align-items-center">

                            <div class="section-icon">
                                <i class="bi bi-calendar2-week"></i>
                            </div>

                            <div class="ms-3">

                                <h5 class="mb-1 fw-bold">
                                    Masa Kerja
                                </h5>

                                <small class="text-muted">
                                    Riwayat masa kerja guru
                                </small>

                            </div>

                        </div>

                    </div>

                    <div class="card-body">

                        <div class="row g-3">

                            <div class="col-6">

                                <label class="form-label">
                                    Golongan (Tahun)
                                </label>

                                <input
                                    type="number"
                                    name="masa_kerja_gol_th"
                                    class="form-control"
                                    value="{{ old('masa_kerja_gol_th',$pegawai->masa_kerja_gol_th) }}">

                            </div>

                            <div class="col-6">

                                <label class="form-label">
                                    Golongan (Bulan)
                                </label>

                                <input
                                    type="number"
                                    name="masa_kerja_gol_bln"
                                    class="form-control"
                                    value="{{ old('masa_kerja_gol_bln',$pegawai->masa_kerja_gol_bln) }}">

                            </div>

                            <div class="col-6">

                                <label class="form-label">
                                    Total (Tahun)
                                </label>

                                <input
                                    type="number"
                                    name="masa_kerja_total_th"
                                    class="form-control"
                                    value="{{ old('masa_kerja_total_th',$pegawai->masa_kerja_total_th) }}">

                            </div>

                            <div class="col-6">

                                <label class="form-label">
                                    Total (Bulan)
                                </label>

                                <input
                                    type="number"
                                    name="masa_kerja_total_bln"
                                    class="form-control"
                                    value="{{ old('masa_kerja_total_bln',$pegawai->masa_kerja_total_bln) }}">

                            </div>

                        </div>

                    </div>

                </div>

            </div>

            {{-- SERTIFIKASI --}}
            <div class="col-lg-6 mb-4">

                <div class="card shadow-sm border-0 h-100">

                    <div class="card-header">

                        <div class="d-flex align-items-center">

                            <div class="section-icon">
                                <i class="bi bi-award"></i>
                            </div>

                            <div class="ms-3">

                                <h5 class="mb-1 fw-bold">
                                    Sertifikasi
                                </h5>

                                <small class="text-muted">
                                    Data sertifikasi pendidik
                                </small>

                            </div>

                        </div>

                    </div>

                    <div class="card-body">

                        <div class="row g-3">

                            <div class="col-12">

                                <label class="form-label">
                                    Nomor Sertifikasi
                                </label>

                                <input
                                    type="text"
                                    name="nomor_sertifikasi"
                                    class="form-control"
                                    value="{{ old('nomor_sertifikasi',$pegawai->nomor_sertifikasi) }}">

                            </div>

                            <div class="col-12">

                                <label class="form-label">
                                    Tahun Sertifikasi
                                </label>

                                <input
                                    type="number"
                                    name="tahun_sertifikasi"
                                    class="form-control"
                                    value="{{ old('tahun_sertifikasi',$pegawai->tahun_sertifikasi) }}">

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        {{-- KETERANGAN --}}
        <div class="card shadow-sm border-0 mb-4">

            <div class="card-header">

                <div class="d-flex align-items-center">

                    <div class="section-icon">
                        <i class="bi bi-journal-text"></i>
                    </div>

                    <div class="ms-3">

                        <h5 class="mb-1 fw-bold">
                            Keterangan
                        </h5>

                        <small class="text-muted">
                            Catatan tambahan
                        </small>

                    </div>

                </div>

            </div>

            <div class="card-body">

                <textarea
                    name="keterangan"
                    rows="5"
                    class="form-control">{{ old('keterangan',$pegawai->keterangan) }}</textarea>

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