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

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">

        <div
            class="card-body p-4 p-lg-5 text-white"
            style="background:linear-gradient(135deg,#0f4c81 0%,#2563eb 100%);"
        >

            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">

                <div>

                    <h3 class="fw-bold mb-2">
                        Kenaikan Kelas
                    </h3>

                    <p class="mb-0 opacity-75">
                        Tentukan keputusan kenaikan kelas pada setiap siswa bedasarkan hasil pembelajaran selama satu tahun ajaran penuh.
                    </p>

                </div>

                <div class="badge bg-white text-primary px-3 py-2 fs-6">
                    <i class="bi bi-shield-check me-2"></i>
                    Sinergi-MJ
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
                'text' => 'Menunggu Persetujuan',
            ],
            'rejected' => [
                'class' => 'danger',
                'icon' => 'bi-x-circle',
                'text' => 'Ditolak',
            ],
            'approved' => [
                'class' => 'success',
                'icon' => 'bi-check-circle',
                'text' => 'Disetujui',
            ],
        ];
    @endphp

    <div class="alert alert-{{ $statusConfig[$approvalStatus]['class'] }} border-0 rounded-4 shadow-sm mb-4">
        <i class="bi {{ $statusConfig[$approvalStatus]['icon'] }} me-2"></i>
        <strong>Status :</strong>
        {{ $statusConfig[$approvalStatus]['text'] }}
    </div>

    {{-- TABLE --}}
    <x-table.card
        title="Daftar Kenaikan Kelas Siswa"
        subtitle="Pilih keputusan kenaikan kelas dan tambahkan catatan apabila diperlukan."
    >
        <x-slot:badge>
            <span class="badge bg-primary rounded-pill px-2 py-1">
                {{ $totalSiswa }}
                <span class="text-badge">
                    Siswa
                </span>
            </span>
        </x-slot:badge>                                    

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
                                    <span class="badge bg-light text-dark rounded-circle">
                                        {{ $i + 1 }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div
                                            class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center me-3"
                                                style="width: 48px; height: 48px;"
                                            >
                                            <i class="bi bi-person-fill fs-4"></i>
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
                                        class="form-control"
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
            <div class="d-flex justify-content-end mt-4 gap-2">
                @if($approvalStatus == 'draft' || $approvalStatus == 'rejected')
                    <button type="submit" class="btn btn-success rounded-3">
                        Simpan <i class="bi bi-save ms-1"></i>
                    </button>
                @elseif($approvalStatus == 'pending')
                    <button
                        type="button"
                        class="btn btn-warning rounded-3"
                        data-bs-toggle="modal"
                        data-bs-target="#modalCancelSubmission">
                        <i class="bi bi-arrow-counterclockwise me-2"></i>
                        Batalkan Pengajuan
                    </button>
                @elseif($approvalStatus == 'approved')
                    <span class="badge bg-success fs-6 px-4 py-3">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        Disetujui
                    </span>
                @endif
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
    </x-table.card>
</div>

</div>

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