@extends('layouts.app')

@section('title', 'Kenaikan Kelas')

@section('content')

@php
    $approvalStatus = $approvalStatus ?? $penempatan->first()?->keputusan?->approval_status ?? 'draft';

    $readonly = in_array($approvalStatus, ['pending', 'approved']);
    
    $totalSiswa = $penempatan->count();

    $naik = $penempatan->filter(fn ($p) => optional($p->keputusan)->status == 'naik')->count();

    $tinggal = $penempatan->filter(fn ($p) => optional($p->keputusan)->status == 'tinggal')->count();

@endphp

<div class="container-fluid py-4">

    {{-- HERO --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">

        <div
            class="card-body p-4 p-lg-5 text-white"
            style="background:linear-gradient(135deg,#0f4c81 0%,#2563eb 100%);">

            <div class="row align-items-center">

                <div class="col-lg-8">

                    <h2 class="fw-bold mb-2">

                        Kenaikan Kelas

                    </h2>

                    <p class="mb-0 opacity-75">

                        Tentukan keputusan kenaikan kelas setiap siswa berdasarkan hasil pembelajaran selama satu tahun ajaran.

                    </p>

                </div>

                <div class="col-lg-4 mt-4 mt-lg-0">

                    <div class="row g-3">

                        <div class="col-4">

                            <div class="bg-white rounded-4 text-center p-3">

                                <h4 class="fw-bold text-primary mb-1">

                                    {{ $totalSiswa }}

                                </h4>

                                <small class="text-muted">

                                    Siswa

                                </small>

                            </div>

                        </div>

                        <div class="col-4">

                            <div class="bg-white rounded-4 text-center p-3">

                                <h4 class="fw-bold text-success mb-1">

                                    {{ $naik }}

                                </h4>

                                <small class="text-muted">

                                    Naik

                                </small>

                            </div>

                        </div>

                        <div class="col-4">

                            <div class="bg-white rounded-4 text-center p-3">

                                <h4 class="fw-bold text-danger mb-1">

                                    {{ $tinggal }}

                                </h4>

                                <small class="text-muted">

                                    Tinggal

                                </small>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    {{-- ALERT --}}
    @if(session('success'))

        <div class="alert alert-success border-0 shadow-sm rounded-4">

            <i class="bi bi-check-circle-fill me-2"></i>

            {{ session('success') }}

        </div>

    @endif

    @if(session('error'))

        <div class="alert alert-danger border-0 shadow-sm rounded-4">

            <i class="bi bi-exclamation-triangle-fill me-2"></i>

            {{ session('error') }}

        </div>

    @endif

    {{-- SUMMARY --}}
    <div class="row g-3 mb-4">

        <div class="col-lg-4">

            <div class="summary-card">

                <div class="summary-icon bg-primary-subtle text-primary">

                    <i class="bi bi-people-fill"></i>

                </div>

                <h3>{{ $totalSiswa }}</h3>

                <small class="text-muted">

                    Total Siswa

                </small>

            </div>

        </div>

        <div class="col-lg-4">

            <div class="summary-card">

                <div class="summary-icon bg-success-subtle text-success">

                    <i class="bi bi-arrow-up-circle-fill"></i>

                </div>

                <h3>{{ $naik }}</h3>

                <small class="text-muted">

                    Naik Kelas

                </small>

            </div>

        </div>

        <div class="col-lg-4">

            <div class="summary-card">

                <div class="summary-icon bg-danger-subtle text-danger">

                    <i class="bi bi-arrow-down-circle-fill"></i>

                </div>

                <h3>{{ $tinggal }}</h3>

                <small class="text-muted">

                    Tinggal Kelas

                </small>

            </div>

        </div>

    </div>

    @php
        $statusConfig = [
            'draft' => [
                'class' => 'secondary',
                'icon' => 'bi-pencil-square',
                'text' => 'Draft',
            ],
            'pending' => [
                'class' => 'warning',
                'icon' => 'bi-hourglass-split',
                'text' => 'Menunggu Persetujuan Kepala Sekolah',
            ],
            'rejected' => [
                'class' => 'danger',
                'icon' => 'bi-x-circle',
                'text' => 'Ditolak Kepala Sekolah',
            ],
            'approved' => [
                'class' => 'success',
                'icon' => 'bi-check-circle',
                'text' => 'Final Approved',
            ],
        ];
    @endphp

    <div class="alert alert-{{ $statusConfig[$approvalStatus]['class'] }} border-0 rounded-4 shadow-sm mb-4">
        <i class="bi {{ $statusConfig[$approvalStatus]['icon'] }} me-2"></i>
        <strong>Status :</strong>
        {{ $statusConfig[$approvalStatus]['text'] }}
    </div>

    {{-- TABLE --}}
    <div class="card border-0 shadow-sm rounded-4">

        <div class="card-header bg-white border-0 py-4">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <h5 class="fw-bold mb-1">

                        Keputusan Kenaikan Kelas

                    </h5>

                    <small class="text-muted">

                        Pilih keputusan kenaikan kelas dan tambahkan catatan apabila diperlukan.

                    </small>

                </div>

                <span class="badge bg-primary rounded-pill px-3 py-2">

                    {{ $totalSiswa }} Siswa

                </span>

            </div>

        </div>

        <div class="card-body p-0">

            <form action="{{ route('guru.kenaikan.store') }}" method="POST" class="w-100">
                @csrf

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th width="70">No</th>
                                <th>Nama Siswa</th>
                                <th width="220">Keputusan</th>
                                <th>Catatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($penempatan as $i => $p)
                                <tr>
                                    <td>
                                        <span class="badge bg-light text-dark rounded-pill px-3 py-2">
                                            {{ $i + 1 }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="student-avatar me-3">
                                                {{ strtoupper(substr($p->siswa->nama, 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="fw-bold">
                                                    {{ $p->siswa->nama }}
                                                </div>
                                                <small class="text-muted">
                                                    {{ $p->siswa->nis }}
                                                </small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <select
                                            name="keputusan[{{ $p->siswa->id }}][status]"
                                            class="form-select rounded-3"
                                            {{ $readonly ? 'disabled' : '' }}>
                                            <option value="naik" {{ optional($p->keputusan)->status == 'naik' ? 'selected' : '' }}>
                                                Naik Kelas
                                            </option>
                                            <option value="tinggal" {{ optional($p->keputusan)->status == 'tinggal' ? 'selected' : '' }}>
                                                Tinggal Kelas
                                            </option>
                                        </select>
                                    </td>
                                    <td>
                                        <input
                                            type="text"
                                            name="keputusan[{{ $p->siswa->id }}][catatan]"
                                            class="form-control form-control-modern"
                                            placeholder="Tambahkan catatan..."
                                            value="{{ optional($p->keputusan)->catatan }}"
                                            {{ $readonly ? 'readonly' : '' }}>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4">
                                        <div class="text-center py-5">
                                            <i class="bi bi-mortarboard display-3 text-secondary"></i>
                                            <h5 class="fw-bold mt-3">Belum Ada Data</h5>
                                            <p class="text-muted mb-0">
                                                Belum ada data siswa untuk diproses kenaikan kelas.
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="card-footer bg-white border-0 py-4">
                    <div class="d-flex justify-content-end gap-2">
                        @if($approvalStatus == 'draft' || $approvalStatus == 'rejected')
                            <button type="submit" class="btn btn-success rounded-pill px-4">
                                <i class="bi bi-check-circle me-2"></i>
                                Simpan Keputusan
                            </button>
                        @elseif($approvalStatus == 'pending')
                            <button
                                type="button"
                                class="btn btn-warning rounded-pill px-4"
                                data-bs-toggle="modal"
                                data-bs-target="#modalCancelSubmission">
                                <i class="bi bi-arrow-counterclockwise me-2"></i>
                                Batalkan Pengajuan
                            </button>
                        @elseif($approvalStatus == 'approved')
                            <span class="badge bg-success fs-6 px-4 py-3">
                                <i class="bi bi-check-circle-fill me-2"></i>
                                Final Approved
                            </span>
                        @endif
                    </div>
                </div>
            </form>

            @if($approvalStatus == 'draft' || $approvalStatus == 'rejected')
                <div class="px-4 pb-4 d-flex justify-content-end">
                    <button
                        type="button"
                        class="btn btn-primary rounded-pill px-4"
                        data-bs-toggle="modal"
                        data-bs-target="#modalSubmitKenaikan">
                        <i class="bi bi-send me-2"></i>
                        Kirim ke Kepala Sekolah
                    </button>
                </div>
            @endif

            <div
                class="modal fade"
                id="modalSubmitKenaikan"
                tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 rounded-4">
                        <div class="modal-header">
                            <h5 class="modal-title fw-bold">Kirim ke Kepala Sekolah</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-warning rounded-3">
                                <strong>Perhatian!</strong>
                                <hr>
                                Tahun ajaran yang sedang berjalan masih aktif.
                                <br><br>
                                Setelah pengajuan dikirim ke Kepala Sekolah:
                                <ul class="mt-2 mb-0">
                                    <li>Data tidak dapat diubah.</li>
                                    <li>Anda hanya dapat melakukan <strong>Batalkan Pengajuan</strong> sebelum disetujui Kepala Sekolah.</li>
                                    <li>Jika sudah disetujui Kepala Sekolah, data tidak dapat diubah kembali.</li>
                                </ul>
                            </div>

                            <label class="form-label fw-semibold">
                                Ketik
                                <span class="text-danger">NAIK KELAS</span>
                                untuk melanjutkan.
                            </label>
                            <input type="text" id="konfirmasiSubmit" class="form-control">
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                            <form action="{{ route('guru.kenaikan.submit') }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button id="btnSubmitKenaikan" class="btn btn-primary" disabled>
                                    Kirim ke Kepala Sekolah
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div
                class="modal fade"
                id="modalCancelSubmission"
                tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 rounded-4">
                        <div class="modal-header">
                            <h5 class="modal-title fw-bold">Batalkan Pengajuan</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-warning">
                                Pengajuan akan dikembalikan menjadi <strong>Draft</strong>.
                                <hr>
                                Setelah dibatalkan:
                                <ul class="mb-0">
                                    <li>Data dapat diedit kembali.</li>
                                    <li>Guru dapat mengirim ulang ke Kepala Sekolah.</li>
                                </ul>
                            </div>

                            <label class="form-label fw-semibold">
                                Ketik
                                <span class="text-danger">KONFIRMASI</span>
                                untuk melanjutkan.
                            </label>
                            <input id="konfirmasiCancel" type="text" class="form-control">
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
                            <form action="{{ route('guru.kenaikan.cancel') }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button id="btnCancelSubmission" class="btn btn-warning" disabled>
                                    Ya, Batalkan
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

</div>

</div>

<style>

.card{

    transition:.25s;

}

.card:hover{

    box-shadow:0 18px 45px rgba(37,99,235,.08)!important;

}

.summary-card{

    background:#fff;

    border-radius:20px;

    padding:24px;

    text-align:center;

    box-shadow:0 .125rem .5rem rgba(0,0,0,.05);

    transition:.25s;

    height:100%;

}

.summary-card:hover{

    transform:translateY(-4px);

}

.summary-icon{

    width:60px;

    height:60px;

    border-radius:18px;

    display:flex;

    align-items:center;

    justify-content:center;

    margin:0 auto 16px;

    font-size:1.35rem;

}

.student-avatar{

    width:46px;

    height:46px;

    border-radius:50%;

    background:linear-gradient(135deg,#2563eb,#0f4c81);

    color:#fff;

    display:flex;

    align-items:center;

    justify-content:center;

    font-weight:700;

    flex-shrink:0;

}

.table thead th{

    background:#f8fafc;

    color:#64748b;

    text-transform:uppercase;

    font-size:.82rem;

    font-weight:700;

    letter-spacing:.5px;

    border:0;

    padding:18px;

}

.table tbody td{

    padding:18px;

    border-color:#eef2f7;

    vertical-align:middle;

}

.table-hover tbody tr:hover{

    background:#f8fbff;

}

.form-control-modern{

    border-radius:12px;

}

.form-control-modern:focus,

.form-select:focus{

    border-color:#2563eb;

    box-shadow:0 0 0 .2rem rgba(37,99,235,.15);

}

.btn{

    font-weight:600;

    transition:.2s;

}

.btn:hover{

    transform:translateY(-2px);

}

@media(max-width:768px){

    .student-avatar{

        width:40px;

        height:40px;

        font-size:.9rem;

    }

}

</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('konfirmasiSubmit');
    const button = document.getElementById('btnSubmitKenaikan');

    if (!input || !button) {
        return;
    }

    input.addEventListener('input', function () {
        button.disabled = this.value.trim() !== 'NAIK KELAS';
    });

    const inputCancel = document.getElementById('konfirmasiCancel');
    const btnCancel = document.getElementById('btnCancelSubmission');

    if (inputCancel && btnCancel) {
        inputCancel.addEventListener('input', function () {
            btnCancel.disabled = this.value.trim() !== 'KONFIRMASI';
        });
    }
});
</script>

@endsection